<div>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Persebaran Lokasi Pelanggan</h4>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="position-relative">
                        <div class="input-group">
                            <input wire:model.debounce.500ms="searchQuery" type="text" class="form-control"
                                placeholder="Cari berdasarkan alamat/nama penerima...">
                            <button wire:click.prevent="search" class="btn btn-outline-primary" type="button">
                                <i class="bi bi-search"></i> Cari
                            </button>
                        </div>

                        @if (!empty($locations) && strlen($searchQuery) > 0)
                            <div id="customerSearchResults"
                                class="list-group position-absolute bg-white w-100 mt-1 rounded shadow"
                                style="z-index: 999;">
                                @foreach ($locations as $index => $loc)
                                    <button class="list-group-item list-group-item-action"
                                        wire:click.prevent="selectLocation({{ $index }})"
                                        wire:key="result-{{ $index }}">
                                        <div class="fw-bold">{{ $loc['recipient_name'] ?? 'Pelanggan' }}</div>
                                        <div class="small text-muted">{{ $loc['address'] }}</div>
                                        @if (isset($loc['latitude']) && isset($loc['longitude']))
                                            <div class="small text-muted">
                                                <i class="bi bi-geo-alt"></i>
                                                {{ number_format($loc['latitude'], 4) }},
                                                {{ number_format($loc['longitude'], 4) }}
                                            </div>
                                        @endif
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-md-6 text-end">
                    <button wire:click.prevent="resetSearch" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset Pencarian
                    </button>
                </div>
            </div>

            <div class="map-container" wire:ignore>
                <div id="customerDistributionMap" style="height: 600px; width: 100%; border-radius: 8px;"></div>
            </div>

            <div class="mt-3">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i>
                    @if ($searchQuery)
                        Menampilkan {{ $locations->count() }} hasil untuk pencarian "{{ $searchQuery }}"
                    @else
                        Menampilkan seluruh lokasi pelanggan ({{ $locations->count() }})
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let map;
                let customerMarkers = [];
                let storeMarker;
                let routingControl = null;

                // Initialize map
                function initMap() {
                    // Default center (Medan)
                    const defaultCenter = [3.5897, 98.6738];

                    // Create map
                    map = L.map('customerDistributionMap').setView(defaultCenter, 12);

                    // Add tile layer
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                    }).addTo(map);

                    // Load initial data
                    loadMapData();

                    // Handle Livewire search updates
                    Livewire.on('updateMapData', () => {
                        clearMarkers();
                        clearRoute();
                        loadMapData();
                    });
                }

                // Load data from Livewire
                function loadMapData() {
                    // Load store locations
                    const storeLocations = @json($storeLocations);
                    if (storeLocations && storeLocations.length > 0) {
                        const [lat, lng] = storeLocations[0].value.split(',').map(Number);
                        storeMarker = L.marker([lat, lng], {
                            icon: L.icon({
                                iconUrl: '{{ asset('images/icons/pop-up-shop.png') }}',
                                iconSize: [32, 32],
                                iconAnchor: [16, 32],
                                popupAnchor: [0, -32]
                            }),
                            zIndexOffset: 1000
                        }).addTo(map);

                        storeMarker.bindPopup(`
                            <div class="store-popup">
                                <div class="fw-bold">Lokasi Toko</div>
                                <div>Lat: ${lat.toFixed(6)}, Lng: ${lng.toFixed(6)}</div>
                            </div>
                        `);
                    }

                    // Load customer locations
                    const locations = @json($locations);
                    if (locations && locations.length > 0) {
                        locations.forEach(customer => {
                            if (customer.latitude && customer.longitude) {
                                const marker = L.marker([customer.latitude, customer.longitude], {
                                    icon: L.icon({
                                        iconUrl: 'https://cdn0.iconfinder.com/data/icons/small-n-flat/24/678111-map-marker-512.png',
                                        iconSize: [32, 32],
                                        iconAnchor: [16, 32],
                                        popupAnchor: [0, -32]
                                    })
                                }).addTo(map);

                                // Calculate distance to store if exists
                                let distanceInfo = '';
                                if (storeMarker) {
                                    const storeLatLng = storeMarker.getLatLng();
                                    const distance = calculateDistance(
                                        storeLatLng.lat, storeLatLng.lng,
                                        customer.latitude, customer.longitude
                                    );
                                    distanceInfo =
                                        `<div><i class="bi bi-signpost-split"></i> Jarak dari toko: ${distance.toFixed(2)} km</div>`;
                                }

                                // Add click handler to show route
                                const routeButton = storeMarker ?
                                    `<button class="btn btn-sm btn-primary mt-2 show-route-btn" 
                                        data-from-lat="${storeMarker.getLatLng().lat}" 
                                        data-from-lng="${storeMarker.getLatLng().lng}"
                                        data-to-lat="${customer.latitude}" 
                                        data-to-lng="${customer.longitude}">
                                        <i class="bi bi-signpost"></i> Tampilkan Rute
                                    </button>` : '';

                                marker.bindPopup(`
                                    <div class="customer-popup">
                                        <div class="fw-bold">${customer.recipient_name || 'Pelanggan'}</div>
                                        <div>${customer.address}</div>
                                        <div>Telp: ${customer.recipient_phone || '-'}</div>
                                        ${distanceInfo}
                                        ${customer.latitude}, ${customer.longitude}
                                        <div class="small text-muted mt-1">
                                            No. Pesan: ${customer.receipt_number || '-'}
                                        </div>
                                        ${routeButton}
                                    </div>
                                `);

                                // Add click event after popup is opened
                                marker.on('popupopen', function() {
                                    document.querySelectorAll('.show-route-btn').forEach(btn => {
                                        btn.addEventListener('click', function() {
                                            const fromLat = parseFloat(this.dataset
                                                .fromLat);
                                            const fromLng = parseFloat(this.dataset
                                                .fromLng);
                                            const toLat = parseFloat(this.dataset
                                                .toLat);
                                            const toLng = parseFloat(this.dataset
                                                .toLng);

                                            showRoute(fromLat, fromLng, toLat, toLng);
                                        });
                                    });
                                });

                                customerMarkers.push(marker);
                            }
                        });

                        // Fit map to show all markers
                        fitMapToMarkers();
                    }
                }

                // Show route between two points
                // Update the showRoute function to include styling options
                function showRoute(fromLat, fromLng, toLat, toLng) {
                    // Clear any existing route
                    clearRoute();

                    // Create new route with transparent styling
                    routingControl = L.Routing.control({
                        waypoints: [
                            L.latLng(fromLat, fromLng),
                            L.latLng(toLat, toLng)
                        ],
                        routeWhileDragging: false,
                        showAlternatives: false,
                        addWaypoints: false,
                        draggableWaypoints: false,
                        fitSelectedRoutes: true,
                        show: true,
                        lineOptions: {
                            styles: [{
                                color: '#4285F4',
                                opacity: 0.7,
                                weight: 5
                            }]
                        },
                        createMarker: function() {
                            return null;
                        },
                        formatter: new L.Routing.Formatter({
                            language: 'en',
                            unit: 'metric'
                        })
                    }).addTo(map);

                    // Apply transparent styling to the routing container
                    setTimeout(() => {
                        const container = document.querySelector('.leaflet-routing-container');
                        if (container) {
                            container.style.backgroundColor = 'rgba(255, 255, 255, 0.8)';
                            container.style.backdropFilter = 'blur(5px)';
                            container.style.border = '1px solid rgba(0, 0, 0, 0.1)';
                            container.style.borderRadius = '8px';
                            container.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.1)';
                            container.style.padding = '10px';
                        }
                    }, 100);

                    // Zoom to fit the route
                    const bounds = L.latLngBounds(
                        L.latLng(fromLat, fromLng),
                        L.latLng(toLat, toLng)
                    );
                    map.fitBounds(bounds, {
                        padding: [50, 50]
                    });
                }

                // Clear the current route
                function clearRoute() {
                    if (routingControl) {
                        map.removeControl(routingControl);
                        routingControl = null;
                    }
                }

                // Clear all markers
                function clearMarkers() {
                    customerMarkers.forEach(marker => map.removeLayer(marker));
                    customerMarkers = [];
                    if (storeMarker) {
                        map.removeLayer(storeMarker);
                        storeMarker = null;
                    }
                }

                // Fit map to show all markers
                function fitMapToMarkers() {
                    if (customerMarkers.length === 0 && !storeMarker) return;

                    const bounds = L.latLngBounds();

                    // Add customer markers
                    customerMarkers.forEach(marker => {
                        bounds.extend(marker.getLatLng());
                    });

                    // Add store marker if exists
                    if (storeMarker) {
                        bounds.extend(storeMarker.getLatLng());
                    }

                    // Fit bounds with padding
                    map.fitBounds(bounds, {
                        padding: [50, 50]
                    });
                }

                // Calculate distance between two points in km
                function calculateDistance(lat1, lon1, lat2, lon2) {
                    const R = 6371; // Radius of the earth in km
                    const dLat = deg2rad(lat2 - lat1);
                    const dLon = deg2rad(lon2 - lon1);
                    const a =
                        Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                        Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) *
                        Math.sin(dLon / 2) * Math.sin(dLon / 2);
                    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                    return R * c; // Distance in km
                }

                function deg2rad(deg) {
                    return deg * (Math.PI / 180);
                }

                document.addEventListener('zoomToLocation', function(event) {
                    const {
                        latitude,
                        longitude,
                        recipient_name,
                        address
                    } = event.detail;

                    if (latitude && longitude && map) {
                        // Zoom ke lokasi yang dipilih
                        map.setView([latitude, longitude], 16);

                        // Cari marker yang sudah ada
                        const existingMarker = customerMarkers.find(marker => {
                            const latlng = marker.getLatLng();
                            return latlng.lat === latitude && latlng.lng === longitude;
                        });

                        if (existingMarker) {
                            // Buka popup jika marker sudah ada
                            existingMarker.openPopup();
                        } else {
                            // Buat popup sementara jika marker belum ada
                            L.popup()
                                .setLatLng([latitude, longitude])
                                .setContent(`
                        <div class="search-result-popup">
                            <div class="fw-bold">${recipient_name}</div>
                            <div>${address}</div>
                        </div>
                    `)
                                .openOn(map);
                        }
                    }
                });

                // Initialize the map
                initMap();
            });
        </script>
    @endpush


    @push('styles')
        <style>
            #customerDistributionMap {
                height: 600px;
                width: 100%;
                border-radius: 8px;
                background: #f5f5f5;
            }

            .map-container {
                position: relative;
                margin-bottom: 1rem;
            }

            .customer-popup {
                font-size: 0.875rem;
                line-height: 1.5;
                min-width: 250px;
            }

            .customer-popup .fw-bold {
                color: #dc3545;
                margin-bottom: 0.25rem;
            }

            .customer-popup .small {
                font-size: 0.75rem;
                color: #6c757d;
            }

            .store-popup {
                font-size: 0.875rem;
                line-height: 1.5;
            }

            .store-popup .fw-bold {
                color: #0d6efd;
                margin-bottom: 0.25rem;
            }

            .search-result-popup {
                font-size: 0.875rem;
                line-height: 1.5;
            }

            .search-result-popup .fw-bold {
                color: #198754;
                margin-bottom: 0.25rem;
            }

            #customerSearchResults {
                max-height: 300px;
                overflow-y: auto;
                z-index: 1000;
                border: 1px solid rgba(0, 0, 0, .15);
                box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, .175);
            }

            #customerSearchResults .dropdown-item {
                white-space: normal;
                padding: 0.5rem 1rem;
            }

            #customerSearchResults .dropdown-item:hover {
                background-color: #f8f9fa;
            }

            #customerSearchResults .dropdown-item .small {
                font-size: 0.75rem;
            }

            .list-group-item-action {
                cursor: pointer;
                transition: background-color 0.2s;
            }

            .list-group-item-action:hover {
                background-color: #f8f9fa;
            }

            .search-result-popup {
                font-size: 0.875rem;
                line-height: 1.5;
                min-width: 200px;
            }

            .search-result-popup .fw-bold {
                color: #198754;
                margin-bottom: 0.25rem;
            }

            /* Add styles for the route button */
            .show-route-btn {
                font-size: 0.75rem;
                padding: 0.25rem 0.5rem;
            }

            /* Style for the route information */
            .leaflet-routing-container {
                background: white;
                padding: 10px;
                border-radius: 5px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
                max-height: 200px;
                overflow-y: auto;
            }

            .leaflet-routing-alt {
                max-height: 150px;
                overflow-y: auto;
            }

            .leaflet-routing-container {
                background-color: rgba(255, 255, 255, 0.8) !important;
                backdrop-filter: blur(5px);
                border: 1px solid rgba(0, 0, 0, 0.1) !important;
                border-radius: 8px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                padding: 10px;
                max-height: 300px;
                overflow-y: auto;
                font-size: 0.9rem;
            }

            .leaflet-routing-container h3 {
                margin-top: 0;
                font-size: 1rem;
                color: #000000;
            }

            .leaflet-routing-container .route-info {
                margin-bottom: 10px;
                font-size: 0.9rem;
                color: #000000;
            }

            .leaflet-routing-alt {
                max-height: 200px;
                overflow-y: auto;
                background: transparent !important;
            }

            .leaflet-routing-alt table {
                width: 100%;
                border-collapse: collapse;
                background: transparent !important;
            }

            .leaflet-routing-alt tr {
                background: transparent !important;
                border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            }

            .leaflet-routing-alt tr:hover {
                background: rgba(0, 0, 0, 0.02) !important;
            }

            .leaflet-routing-alt td {
                padding: 6px 0;
                background: transparent !important;
            }

            .leaflet-bar {
                background-color: rgba(255, 255, 255, 0.8) !important;
                backdrop-filter: blur(5px);
                border-radius: 4px !important;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1) !important;
            }

            .leaflet-bar a {
                border-bottom: 1px solid rgba(0, 0, 0, 0.1) !important;
            }

            .leaflet-bar a:last-child {
                border-bottom: none !important;
            }

            /* Route line styling */
            .leaflet-routing-line {
                stroke: #4285F4;
                stroke-opacity: 0.7;
                stroke-width: 5;
                stroke-linecap: round;
                stroke-linejoin: round;
            }
        </style>
    @endpush
</div>
