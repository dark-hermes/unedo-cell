<div>
    <livewire:admin.partials.page-heading title="Edit Option" :breadcrumbs="[['label' => 'Options', 'href' => '/admin/options'], ['label' => 'Edit']]" />

    <section class="section">
        <div class="card">
            <div class="card-body">
                <form>
                    <div class="form-group">
                        <label for="key">Key</label>
                        <input wire:model="key" id="key" type="text" class="form-control" readonly>
                    </div>

                    <div class="form-group">
                        <label for="type">Type</label>
                        <select wire:model="type" id="type" class="form-control" disabled>
                            @foreach ($types as $typeValue => $typeLabel)
                                <option value="{{ $typeValue }}">{{ $typeLabel }}</option>
                            @endforeach
                        </select>
                    </div>

                    @if ($key === 'STORE_COORDINATE')
                        <div class="form-group">
                            <label>Location Coordinates</label>
                            <p id="coord-display" class="form-control mb-3" style="background: #e9ecef;">
                                @if ($value)
                                    Lat: {{ explode(',', $value)[0] }}, Lng: {{ explode(',', $value)[1] }}
                                @else
                                    No coordinates set
                                @endif
                            </p>

                            <div class="mb-3">
                                <div class="input-group">
                                    <input wire:model.debounce.500ms="searchLocation" type="text"
                                        class="form-control" placeholder="Search location..."
                                        id="searchLocationInput" />
                                    <button type="button" class="btn btn-outline-secondary"
                                        id="btnSearchLocation">Search</button>
                                </div>
                            </div>

                            <button type="button" class="btn btn-outline-primary mb-3" id="btnUseCurrentLocation">
                                <i class="bi bi-geo-alt"></i> Use Current Location
                            </button>

                            <div wire:ignore id="map-container">
                                <div id="map" style="height: 400px; width: 100%;"></div>
                            </div>
                        </div>
                    @elseif ($type === 'image')
                        <div class="form-group">
                            <label for="value">Image</label>
                            @if ($value)
                                <div class="mb-3">
                                    <img src="{{ Storage::url($value) }}" alt="Current image" class="img-thumbnail"
                                        style="max-height: 200px;">
                                    <button type="button" class="btn btn-danger btn-sm ms-2" wire:click="removePhoto">
                                        Remove
                                    </button>
                                </div>
                            @endif
                            @if ($showPhotoInput || !$value)
                                <input wire:model="newImage" type="file" class="form-control" id="newImage">
                            @else
                                <button type="button" class="btn btn-secondary"
                                    wire:click="$set('showPhotoInput', true)">
                                    Change Image
                                </button>
                            @endif
                        </div>
                    @else
                        <div class="form-group">
                            <label for="value">Value</label>
                            <input wire:model="value" id="value" type="text" class="form-control">
                        </div>
                    @endif

                    <div class="button-group mt-4">
                        <button type="submit" class="btn btn-primary" wire:click.prevent="save">Save Changes</button>
                        <a href="{{ route('admin.options.index') }}" class="btn btn-secondary">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </section>

    @push('scripts')
        <script>
            document.addEventListener('livewire:init', () => {
                let map;
                let marker;
                let mapInitialized = false;
                let hoverTimeout;

                async function getLocationInfo(lat, lng) {
                    try {
                        const response = await fetch(
                            `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`
                            );
                        const data = await response.json();

                        if (data.error) {
                            return "Location information not available";
                        }

                        const address = data.address;
                        let info = '<div class="osm-info">';

                        if (address.road) info += `<div><strong>Jalan:</strong> ${address.road}</div>`;
                        if (address.village) info += `<div><strong>Desa:</strong> ${address.village}</div>`;
                        if (address.suburb) info += `<div><strong>Suburb:</strong> ${address.suburb}</div>`;
                        if (address.city) info += `<div><strong>Kota:</strong> ${address.city}</div>`;
                        if (address.county) info += `<div><strong>Kabupaten:</strong> ${address.county}</div>`;
                        if (address.state) info += `<div><strong>Provinsi:</strong> ${address.state}</div>`;
                        if (address.country) info += `<div><strong>Negara:</strong> ${address.country}</div>`;
                        if (address.postcode) info += `<div><strong>Kode Pos:</strong> ${address.postcode}</div>`;

                        info += '</div>';
                        return info;
                    } catch (error) {
                        console.error('Error fetching location info:', error);
                        return "Could not load location information";
                    }
                }

                async function setMarker(lat, lng) {
                    if (marker) {
                        map.removeLayer(marker);
                    }

                    marker = L.marker([lat, lng]).addTo(map);

                    // Add hover effect with debounce
                    marker.on('mouseover', async function() {
                        clearTimeout(hoverTimeout);
                        hoverTimeout = setTimeout(async () => {
                            const locationInfo = await getLocationInfo(lat, lng);
                            marker.bindPopup(locationInfo, {
                                className: 'osm-popup',
                                closeButton: false,
                                autoClose: false,
                                closeOnClick: false
                            }).openPopup();
                        }, 300);
                    });

                    marker.on('mouseout', function() {
                        clearTimeout(hoverTimeout);
                        marker.closePopup();
                    });

                    // Also show info on click
                    marker.on('click', async function() {
                        const locationInfo = await getLocationInfo(lat, lng);
                        marker.bindPopup(locationInfo, {
                            className: 'osm-popup',
                            closeButton: true
                        }).openPopup();
                    });

                    map.setView([lat, lng], 15);
                    updateCoordinateDisplay(lat, lng);
                }

                function initializeMap() {
                    if (mapInitialized) return;

                    const initialCoords = @json($mapCenter);
                    const validLatLng = c => c && typeof c.lat === 'number' && typeof c.lng === 'number';

                    map = L.map('map').setView(
                        validLatLng(initialCoords) ? [initialCoords.lat, initialCoords.lng] : [-6.2088, 106.8456],
                        15
                    );

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                    }).addTo(map);

                    // Add initial marker if coordinates exist
                    if (validLatLng(initialCoords)) {
                        setMarker(initialCoords.lat, initialCoords.lng);
                    }

                    mapInitialized = true;

                    // Map click event
                    map.on('click', function(e) {
                        const {
                            lat,
                            lng
                        } = e.latlng;
                        setMarker(lat, lng);
                        @this.call('updateLocation', lat, lng);
                    });
                }

                // Initialize the map when the component mounts
                initializeMap();

                // Handle location updates from Livewire
                Livewire.on('locationUpdated', (data) => {
                    const {
                        lat,
                        lng
                    } = data;
                    if (typeof lat === 'number' && typeof lng === 'number') {
                        updateMap(lat, lng);
                    }
                });

                // Search location functionality
                const searchInput = document.getElementById('searchLocationInput');
                const searchBtn = document.getElementById('btnSearchLocation');

                searchBtn.addEventListener('click', () => {
                    const query = searchInput.value.trim();
                    if (!query) return;

                    fetch(
                            `https://api.mapbox.com/geocoding/v5/mapbox.places/${encodeURIComponent(
                            query
                        )}.json?access_token={{ config('services.mapbox.token') }}`
                        )
                        .then(res => res.json())
                        .then(data => {
                            if (data.features && data.features.length > 0) {
                                const [lng, lat] = data.features[0].center;
                                updateMap(lat, lng);
                                @this.call('updateLocation', lat, lng);
                            } else {
                                alert('Location not found!');
                            }
                        });
                });

                // Current location functionality
                const btnUseCurrentLocation = document.getElementById('btnUseCurrentLocation');
                btnUseCurrentLocation.addEventListener('click', () => {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(
                            position => {
                                const {
                                    latitude,
                                    longitude
                                } = position.coords;
                                updateMap(latitude, longitude);
                                @this.call('updateLocation', latitude, longitude);
                            },
                            () => alert(
                                'Could not get your current location. Please enable location services.')
                        );
                    } else {
                        alert('Geolocation is not supported by this browser.');
                    }
                });
            });
        </script>
    @endpush
</div>
