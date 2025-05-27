<div>
    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <div class="row justify-content-between align-items-center">
                <div class="col-md-6">
                    <h1 class="page-title">Edit Alamat</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('address.index') }}">Alamat Saya</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Alamat</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-md-6 text-end">
                    <a href="{{ route('address.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="page-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card">
                        <div class="card-body p-4 p-lg-5">
                            <form wire:submit.prevent="save">
                                <!-- Map Section -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold d-block mb-2">Pinpoint Lokasi</label>

                                    <div class="mb-3">
                                        <div class="input-group">
                                            <input wire:model.debounce.500ms="searchLocation" type="text"
                                                class="form-control" placeholder="Cari lokasi..."
                                                id="searchLocationInput" />
                                            <button type="button" class="btn btn-outline-secondary"
                                                id="btnSearchLocation">
                                                <i class="bi bi-search"></i> Cari
                                            </button>
                                        </div>
                                        <div id="searchResults" class="dropdown-menu w-100" style="display: none;">
                                            <!-- Search results will appear here -->
                                        </div>
                                    </div>

                                    <div class="map-container mb-3" wire:ignore>
                                        <div id="map"
                                            style="height: 400px; width: 100%; border-radius: 8px; border: 1px solid #ddd;">
                                        </div>
                                    </div>

                                    <div class="row g-2">
                                        <div class="col-md-6">
                                            <label class="form-label">Latitude</label>
                                            <input wire:model="latitude" type="text" class="form-control" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Longitude</label>
                                            <input wire:model="longitude" type="text" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="text-center mt-2">
                                        <button type="button" class="btn btn-outline-primary"
                                            wire:click="setCurrentLocation" wire:loading.attr="disabled">
                                            <i class="bi bi-geo-alt"></i>
                                            <span wire:loading.remove>Gunakan Lokasi Saya</span>
                                            <span wire:loading>
                                                <span class="spinner-border spinner-border-sm"></span> Memproses...
                                            </span>
                                        </button>
                                    </div>
                                    @error('latitude')
                                        <div class="text-danger small mt-1">Silakan pilih lokasi di peta</div>
                                    @enderror
                                </div>

                                <div class="mb-3" id="distance-info">
                                    @if ($distance && $duration)
                                        <div class="alert alert-info mb-0">
                                            <i class="bi bi-signpost-split"></i> Jarak: {{ $distance }} km
                                            <br>
                                            <i class="bi bi-clock-history"></i> Perkiraan Waktu: {{ $duration }}
                                            menit
                                        </div>
                                    @endif
                                </div>

                                <!-- Address Details -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Nama Alamat</label>
                                    <input wire:model="name" type="text" class="form-control"
                                        placeholder="Contoh: Rumah, Kantor, Kos, dll">
                                    @error('name')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Nama Penerima</label>
                                    <input wire:model="recipient_name" type="text" class="form-control"
                                        placeholder="Nama orang yang menerima">
                                    @error('recipient_name')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Nomor Telepon</label>
                                    <input wire:model="phone" type="tel" class="form-control"
                                        placeholder="Nomor telepon penerima">
                                    @error('phone')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Alamat Lengkap</label>
                                    <textarea wire:model="address_text" class="form-control" rows="3"
                                        placeholder="Detail alamat (jalan, nomor rumah, RT/RW, dll)"></textarea>
                                    @error('address_text')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold">Catatan (Opsional)</label>
                                    <textarea wire:model="note" class="form-control" rows="2" placeholder="Contoh: Warna rumah, patokan, dll"></textarea>
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary btn-lg"
                                        wire:loading.attr="disabled">
                                        <span wire:loading.remove>Simpan Perubahan</span>
                                        <span wire:loading>
                                            <span class="spinner-border spinner-border-sm"></span> Menyimpan...
                                        </span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('livewire:init', () => {
                let map;
                let marker;
                let popup;
                let searchResults = [];
                let routeLayer = null;
                let storeMarker = null;

                // Fungsi inisialisasi peta
                function initMap() {
                    console.log('Initializing map...');

                    // Gunakan nilai dari Livewire
                    const initialLat = @js($latitude);
                    const initialLng = @js($longitude);

                    // Inisialisasi peta
                    map = L.map('map').setView([initialLat, initialLng], 15);

                    // Tambahkan tile layer
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                    }).addTo(map);

                    // Tambahkan marker toko jika ada
                    addStoreMarker();

                    // Tambahkan marker alamat pelanggan
                    updateMarker(initialLat, initialLng);

                    // Handle klik peta
                    map.on('click', async function(e) {
                        await updateMarker(e.latlng.lat, e.latlng.lng);
                        @this.set('latitude', e.latlng.lat);
                        @this.set('longitude', e.latlng.lng);
                        @this.calculateRoute();
                    });

                    // Perbaiki ukuran peta setelah render
                    setTimeout(() => map.invalidateSize(), 100);
                }

                // Fungsi untuk menambahkan marker toko
                function addStoreMarker() {
                    const storeCoordinate = @js($storeCoordinate);
                    if (!storeCoordinate || !storeCoordinate.value) return;

                    const [storeLat, storeLng] = storeCoordinate.value.split(',').map(coord => parseFloat(coord
                        .trim()));

                    // Hapus marker toko lama jika ada
                    if (storeMarker) {
                        map.removeLayer(storeMarker);
                    }

                    // Buat marker toko dengan ikon khusus
                    storeMarker = L.marker([storeLat, storeLng], {
                        icon: L.icon({
                            iconUrl: '{{ asset('images/icons/pop-up-shop.png') }}',
                            iconSize: [32, 32],
                            iconAnchor: [16, 32],
                            popupAnchor: [0, -32]
                        }),
                        draggable: false
                    }).addTo(map);

                    // Tambahkan popup untuk toko
                    storeMarker.bindPopup(`
            <div class="store-popup">
                <div class="fw-bold">Lokasi Toko</div>
                <div class="small">Lat: ${storeLat.toFixed(6)}, Lng: ${storeLng.toFixed(6)}</div>
            </div>
        `);

                    // Buka popup secara default
                    storeMarker.openPopup();
                }

                // Fungsi update marker alamat pelanggan
                async function updateMarker(lat, lng) {
                    if (!map) {
                        console.error('Map not initialized');
                        return;
                    }

                    // Dapatkan info alamat terbaru
                    const address = await getAddressInfo(lat, lng);
                    const popupContent = createPopupContent(address);

                    if (marker) {
                        // Update marker position
                        marker.setLatLng([lat, lng]);
                        // Update popup content
                        marker.setPopupContent(popupContent);
                    } else {
                        // Buat marker baru dengan popup
                        marker = L.marker([lat, lng], {
                            icon: L.icon({
                                iconUrl: 'https://cdn0.iconfinder.com/data/icons/small-n-flat/24/678111-map-marker-512.png', // Icon alamat
                                iconSize: [32, 32],
                                iconAnchor: [16, 32],
                                popupAnchor: [0, -32]
                            }),
                            draggable: true
                        }).addTo(map);

                        // Bind popup yang akan muncul saat hover
                        marker.bindPopup(popupContent, {
                            closeOnClick: false,
                            autoClose: false,
                            closeButton: false,
                            className: 'marker-popup'
                        });

                        // Buka popup secara permanen
                        marker.openPopup();

                        // Handle ketika marker dipindahkan
                        marker.on('dragend', async function(e) {
                            const newPos = e.target.getLatLng();
                            @this.set('latitude', newPos.lat);
                            @this.set('longitude', newPos.lng);

                            // Update address info ketika marker dipindahkan
                            const newAddress = await getAddressInfo(newPos.lat, newPos.lng);
                            marker.setPopupContent(createPopupContent(newAddress));

                            // Calculate route
                            @this.calculateRoute();
                        });
                    }

                    // Hitung rute ke toko
                    @this.calculateRoute();

                    // Sesuaikan view peta untuk menampilkan kedua marker
                    fitMapToMarkers();
                }

                // Fungsi untuk menyesuaikan view peta ke semua marker
                function fitMapToMarkers() {
                    if (!map) return;

                    const bounds = L.latLngBounds();

                    // Tambahkan marker alamat jika ada
                    if (marker) {
                        bounds.extend(marker.getLatLng());
                    }

                    // Tambahkan marker toko jika ada
                    if (storeMarker) {
                        bounds.extend(storeMarker.getLatLng());
                    }

                    // Tambahkan rute jika ada
                    if (routeLayer) {
                        bounds.extend(routeLayer.getBounds());
                    }

                    // Sesuaikan view peta jika ada marker
                    if (!bounds.isEmpty()) {
                        map.fitBounds(bounds.pad(0.2));
                    }
                }

                // Handle route updates from Livewire
                Livewire.on('update-route', (data) => {
                    // Clear previous route if exists
                    if (routeLayer) {
                        map.removeLayer(routeLayer);
                    }

                    // For OSRM
                    if (data.coordinates) {
                        try {
                            const coordinates = L.Polyline.fromEncoded(data.coordinates.route).getLatLngs();
                            routeLayer = L.polyline(coordinates, {
                                color: '#3388ff',
                                weight: 5,
                                opacity: 0.7,
                                dashArray: '10, 10'
                            }).addTo(map);

                            // Update distance and duration display
                            document.getElementById('distance-info').innerHTML = `
                    <div class="alert alert-info mb-0">
                        <i class="bi bi-signpost-split"></i> Jarak: ${data.distance} km
                    </div>
                `;

                            // Sesuaikan view peta
                            fitMapToMarkers();
                        } catch (e) {
                            console.error('Error drawing route:', e);
                        }
                    }
                });


                // Fungsi untuk mendapatkan informasi alamat dari koordinat
                async function getAddressInfo(lat, lng) {
                    try {
                        const response = await fetch(
                            `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`
                        );
                        const data = await response.json();

                        if (data.error) {
                            console.error('Error getting address:', data.error);
                            return {
                                display_name: 'Lokasi tidak dikenal',
                                address: {}
                            };
                        }

                        return data;
                    } catch (error) {
                        console.error('Error fetching address:', error);
                        return {
                            display_name: 'Gagal memuat alamat',
                            address: {}
                        };
                    }
                }

                // Fungsi untuk membuat konten popup
                function createPopupContent(addressData) {
                    const address = addressData.address || {};
                    return `
                        <div class="osm-info">
                            <div class="fw-bold">${address.road || 'Jalan tidak diketahui'} ${address.house_number || ''}</div>
                            <div>${address.neighbourhood ? address.neighbourhood + ', ' : ''}${address.village || address.suburb || ''}</div>
                            <div>${address.city_district || ''}${address.city_district && address.city ? ', ' : ''}${address.city || ''}</div>
                            <div>${address.state || ''}, ${address.postcode || ''}</div>
                            <div>${address.country || 'Indonesia'}</div>
                            <hr>
                            <div class="small text-muted">${addressData.display_name || 'Detail alamat tidak tersedia'}</div>
                        </div>
                    `;
                }

                // Fungsi update marker dengan popup hover
                async function updateMarker(lat, lng) {
                    if (!map) {
                        console.error('Map not initialized');
                        return;
                    }

                    // Dapatkan info alamat terbaru
                    const address = await getAddressInfo(lat, lng);
                    const popupContent = createPopupContent(address);

                    if (marker) {
                        // Update marker position
                        marker.setLatLng([lat, lng]);
                        // Update popup content
                        marker.setPopupContent(popupContent);
                    } else {
                        // Buat marker baru dengan popup
                        marker = L.marker([lat, lng], {
                            draggable: true
                        }).addTo(map);

                        // Bind popup yang akan muncul saat hover
                        marker.bindPopup(popupContent, {
                            closeOnClick: false,
                            autoClose: false,
                            closeButton: false,
                            className: 'marker-popup'
                        });

                        // Buka popup secara permanen
                        marker.openPopup();

                        // Handle hover untuk popup
                        marker.on('mouseover', function() {
                            this.openPopup();
                        });

                        marker.on('mouseout', function() {
                            // Jangan tutup popup, biarkan tetap terbuka
                        });

                        // Handle ketika marker dipindahkan
                        marker.on('dragend', async function(e) {
                            const newPos = e.target.getLatLng();
                            @this.set('latitude', newPos.lat);
                            @this.set('longitude', newPos.lng);

                            // Update address info ketika marker dipindahkan
                            const newAddress = await getAddressInfo(newPos.lat, newPos.lng);
                            marker.setPopupContent(createPopupContent(newAddress));

                            // Calculate route
                            @this.calculateRoute();
                        });
                    }

                    // Calculate route to store
                    @this.calculateRoute();

                    // Pusatkan peta ke marker
                    map.setView([lat, lng], map.getZoom());
                }

                // Handle route updates from Livewire
                Livewire.on('update-route', (data) => {
                    // Clear previous route if exists
                    if (routeLayer) {
                        map.removeLayer(routeLayer);
                    }

                    // For OSRM
                    if (data.coordinates) {
                        const coordinates = L.Polyline.fromEncoded(data.coordinates).getLatLngs();
                        routeLayer = L.polyline(coordinates, {
                            color: '#3388ff',
                            weight: 5,
                            opacity: 0.7,
                            dashArray: '10, 10'
                        }).addTo(map);
                    }

                    // For Google Maps (if using)
                    if (data.points) {
                        routeLayer = L.polyline(data.points, {
                            color: '#3388ff',
                            weight: 5,
                            opacity: 0.7,
                            dashArray: '10, 10'
                        }).addTo(map);
                    }

                    // Add store marker if not exists
                    if (!window.storeMarker) {
                        const storeLocation = @js($this->storeCoordinate->value);
                        window.storeMarker = L.marker([storeLocation.split(',')[0], storeLocation.split(',')[
                            1]], {
                                icon: L.icon({
                                    iconUrl: '{{ asset('images/icons/pop-up-shop.png') }}',
                                    iconSize: [32, 32],
                                    iconAnchor: [16, 32],
                                    popupAnchor: [0, -32]
                                }),
                                draggable: false
                            }).addTo(map);

                        window.storeMarker.bindPopup(`
                <div class="osm-info">
                    <div class="fw-bold">Lokasi Toko</div>
                    <div>${storeLocation.address || 'Alamat toko tidak tersedia'}</div>
                </div>
            `);
                    }

                    // Fit bounds to show both markers and route
                    if (marker && window.storeMarker) {
                        const group = new L.featureGroup([marker, window.storeMarker, routeLayer]);
                        map.fitBounds(group.getBounds().pad(0.2));
                    }

                    // Update distance and duration display
                    document.getElementById('distance-info').innerHTML = `
            <div class="alert alert-info mb-0">
                <i class="bi bi-signpost-split"></i> Jarak: ${data.distance} km
            </div>
        `;
                });

                // Fungsi untuk mengupdate field form jika kosong
                function updateFormFields(addressData) {
                    const address = addressData.address || {};

                    const fullAddress = [
                        address.road ? (address.road + (address.house_number ? ' No. ' + address.house_number :
                            '')) : '',
                        address.neighbourhood ? 'RT/RW ' + address.neighbourhood : '',
                        address.village ? 'Kel. ' + address.village : '',
                        address.city_district ? 'Kec. ' + address.city_district : '',
                        address.postcode ? 'Kode Pos: ' + address.postcode : ''
                    ].filter(Boolean).join(', ');

                    @this.set('address_text', fullAddress);

                    if (!@js($recipient_name) && @js(auth()->user())) {
                        @this.set('recipient_name', @js(auth()->user()->name));
                    }
                }

                // Inisialisasi peta saat komponen siap
                initMap();

                // Handle pencarian lokasi dengan dropdown hasil
                document.getElementById('btnSearchLocation')?.addEventListener('click', function() {
                    const query = document.getElementById('searchLocationInput').value.trim();
                    if (!query) return;

                    const resultsContainer = document.getElementById('searchResults');
                    resultsContainer.innerHTML =
                        '<div class="dropdown-item text-center py-2"><div class="spinner-border spinner-border-sm text-primary"></div> Mencari...</div>';
                    resultsContainer.style.display = 'block';

                    fetch(
                            `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&countrycodes=id&limit=5`
                        )
                        .then(res => res.json())
                        .then(data => {
                            resultsContainer.innerHTML = '';

                            if (data && data.length > 0) {
                                searchResults = data;

                                if (data.length === 1) {
                                    // Auto-select if only one result
                                    selectSearchResult(0);
                                    resultsContainer.style.display = 'none';
                                } else {
                                    // Show dropdown with results
                                    data.forEach((result, index) => {
                                        const item = document.createElement('a');
                                        item.className = 'dropdown-item py-2';
                                        item.href = '#';
                                        item.innerHTML = `
                                            <div class="fw-bold">${result.display_name.split(',')[0]}</div>
                                            <div class="small text-muted">${result.display_name.split(',').slice(1).join(',').trim()}</div>
                                        `;
                                        item.addEventListener('click', (e) => {
                                            e.preventDefault();
                                            selectSearchResult(index);
                                            resultsContainer.style.display = 'none';
                                        });
                                        resultsContainer.appendChild(item);
                                    });
                                }
                            } else {
                                const item = document.createElement('div');
                                item.className = 'dropdown-item text-center py-2 text-muted';
                                item.textContent = 'Lokasi tidak ditemukan';
                                resultsContainer.appendChild(item);
                            }
                        })
                        .catch(error => {
                            resultsContainer.innerHTML = '';
                            const item = document.createElement('div');
                            item.className = 'dropdown-item text-center py-2 text-danger';
                            item.textContent = 'Error saat mencari lokasi';
                            resultsContainer.appendChild(item);
                            console.error('Search error:', error);
                        });
                });

                // Fungsi untuk memilih hasil pencarian
                async function selectSearchResult(index) {
                    const result = searchResults[index];
                    if (!result) return;

                    const lat = parseFloat(result.lat);
                    const lng = parseFloat(result.lon);

                    // Update map and marker
                    await updateMarker(lat, lng);

                    // Update Livewire properties
                    @this.set('latitude', lat);
                    @this.set('longitude', lng);

                    // Update search input with selected location name
                    document.getElementById('searchLocationInput').value = result.display_name.split(',')[0];
                }

                // Hide dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!e.target.closest('#searchResults') && !e.target.closest('#btnSearchLocation') && !e
                        .target.closest('#searchLocationInput')) {
                        document.getElementById('searchResults').style.display = 'none';
                    }
                });

                // Handle geolocation request dari Livewire
                // Handle geolocation request from Livewire
                Livewire.on('request-browser-location', async () => {
                    if (navigator.geolocation) {
                        const options = {
                            enableHighAccuracy: true, // Try to get the most accurate position
                            timeout: 15000, // 15 seconds timeout
                            maximumAge: 0 // Don't use cached position
                        };

                        const showError = (error) => {
                            console.error('Geolocation error:', error);
                            let message = 'Gagal mendapatkan lokasi Anda';

                            switch (error.code) {
                                case error.PERMISSION_DENIED:
                                    message =
                                        'Akses geolokasi ditolak. Mohon izinkan akses lokasi di pengaturan browser Anda.';
                                    break;
                                case error.POSITION_UNAVAILABLE:
                                    message = 'Informasi lokasi tidak tersedia.';
                                    break;
                                case error.TIMEOUT:
                                    message =
                                        'Waktu pencarian lokasi habis. Pastikan Anda memiliki koneksi internet yang baik.';
                                    break;
                                default:
                                    message = 'Terjadi kesalahan saat mengambil lokasi.';
                            }

                            // Show user-friendly error message
                            const errorElement = document.createElement('div');
                            errorElement.className = 'alert alert-danger mt-2 mb-0';
                            errorElement.innerHTML =
                                `<i class="bi bi-exclamation-triangle"></i> ${message}`;

                            const mapContainer = document.querySelector('.map-container');
                            mapContainer.insertBefore(errorElement, mapContainer.firstChild);

                            // Remove error after 5 seconds
                            setTimeout(() => {
                                errorElement.remove();
                            }, 5000);
                        };

                        navigator.geolocation.getCurrentPosition(
                            async (position) => {
                                    const lat = position.coords.latitude;
                                    const lng = position.coords.longitude;

                                    // Update UI to show we're processing
                                    const processingElement = document.createElement('div');
                                    processingElement.className = 'alert alert-info mt-2 mb-0';
                                    processingElement.innerHTML =
                                        '<i class="bi bi-gear"></i> Memproses lokasi Anda...';

                                    const mapContainer = document.querySelector('.map-container');
                                    mapContainer.insertBefore(processingElement, mapContainer
                                        .firstChild);

                                    try {
                                        await updateMarker(lat, lng);
                                        @this.set('latitude', lat);
                                        @this.set('longitude', lng);

                                        // Update search input with approximate location
                                        const address = await getAddressInfo(lat, lng);
                                        document.getElementById('searchLocationInput').value =
                                            address.address.road ||
                                            address.address.neighbourhood ||
                                            address.address.village ||
                                            'Lokasi Saya';

                                        processingElement.remove();
                                    } catch (error) {
                                        processingElement.remove();
                                        showError({
                                            code: 0,
                                            message: 'Gagal memproses lokasi'
                                        });
                                    }
                                },
                                showError,
                                options
                        );
                    } else {
                        const errorElement = document.createElement('div');
                        errorElement.className = 'alert alert-danger mt-2 mb-0';
                        errorElement.innerHTML =
                            '<i class="bi bi-exclamation-triangle"></i> Browser Anda tidak mendukung geolokasi';

                        const mapContainer = document.querySelector('.map-container');
                        mapContainer.insertBefore(errorElement, mapContainer.firstChild);
                    }
                });
            });
        </script>
    @endpush

    <style>
        #map {
            height: 400px;
            width: 100%;
            border-radius: 8px;
            background: #f5f5f5;
        }

        .leaflet-container {
            z-index: 1;
        }

        .map-container {
            position: relative;
            min-height: 400px;
            margin-bottom: 1rem;
        }

        .page-header {
            padding: 2rem 0;
            background-color: #f8f9fa;
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 1.75rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .osm-info {
            font-size: 0.875rem;
            line-height: 1.5;
        }

        .osm-info div {
            margin-bottom: 0.25rem;
        }

        .leaflet-popup-content {
            margin: 0.75rem;
            min-width: 200px;
        }

        /* Search results dropdown styles */
        #searchResults {
            max-height: 300px;
            overflow-y: auto;
            z-index: 1000;
            border: 1px solid rgba(0, 0, 0, .15);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, .175);
        }

        #searchResults .dropdown-item {
            white-space: normal;
            padding: 0.5rem 1rem;
        }

        #searchResults .dropdown-item:hover {
            background-color: #f8f9fa;
        }

        #searchResults .dropdown-item .small {
            font-size: 0.75rem;
        }

        /* Custom popup style */
        .marker-popup {
            bottom: 30px !important;
            left: -100px !important;
        }

        .marker-popup .leaflet-popup-content-wrapper {
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
            padding: 0;
        }

        .marker-popup .leaflet-popup-content {
            margin: 0;
            padding: 12px;
        }

        .marker-popup .leaflet-popup-tip-container {
            display: none;
        }

        /* Add to your existing styles */
        #distance-info .alert {
            padding: 0.75rem 1rem;
            margin-bottom: 0;
        }

        #distance-info i {
            margin-right: 0.5rem;
        }

        /* Style for store marker popup */
        .store-popup .leaflet-popup-content {
            margin: 0.5rem;
            min-width: 150px;
        }

        /* Add to your existing styles */
        #distance-info .alert {
            padding: 0.75rem 1rem;
            margin-bottom: 0;
        }

        #distance-info i {
            margin-right: 0.5rem;
        }

        .store-popup {
            font-size: 0.875rem;
            line-height: 1.4;
        }

        .store-popup .fw-bold {
            color: #d63384;
            margin-bottom: 0.25rem;
        }

        .store-popup .small {
            color: #6c757d;
            font-size: 0.75rem;
        }
    </style>
</div>
