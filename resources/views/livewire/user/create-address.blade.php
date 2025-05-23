<div>
    <!-- Page Header (unchanged) -->
    <div class="page-header">
        <div class="container">
            <div class="row justify-content-between align-items-center">
                <div class="col-md-6">
                    <h1 class="page-title">Tambah Alamat Baru</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('address.index') }}">Alamat Saya</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Tambah Baru</li>
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
                                    <textarea wire:model="address" class="form-control" rows="3"
                                        placeholder="Detail alamat (jalan, nomor rumah, RT/RW, dll)"></textarea>
                                    @error('address')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold">Catatan (Opsional)</label>
                                    <textarea wire:model="note" class="form-control" rows="2" placeholder="Contoh: Warna rumah, patokan, dll"></textarea>
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary btn-lg" wire:loading.attr="disabled">
                                        <span wire:loading.remove>Simpan Alamat</span>
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

                // Fungsi inisialisasi peta
                function initMap() {
                    console.log('Initializing map...');

                    // Default coordinates (Jakarta)
                    const defaultLat = -6.2088;
                    const defaultLng = 106.8456;

                    // Gunakan nilai dari Livewire atau default
                    const initialLat = @js($latitude) || defaultLat;
                    const initialLng = @js($longitude) || defaultLng;

                    // Inisialisasi peta
                    map = L.map('map').setView([initialLat, initialLng], 15);

                    // Tambahkan tile layer
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                    }).addTo(map);

                    // Tambahkan marker awal
                    updateMarker(initialLat, initialLng);

                    // Handle klik peta
                    map.on('click', async function(e) {
                        await updateMarker(e.latlng.lat, e.latlng.lng);
                        @this.set('latitude', e.latlng.lat);
                        @this.set('longitude', e.latlng.lng);
                    });

                    // Perbaiki ukuran peta setelah render
                    setTimeout(() => map.invalidateSize(), 100);
                }

                // Fungsi untuk mendapatkan informasi alamat dari koordinat
                async function getAddressInfo(lat, lng) {
                    try {
                        const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`);
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
                            
                            // Update form fields if empty
                            updateFormFields(newAddress);
                        });
                    }
                    
                    // Update form fields if empty
                    updateFormFields(address);
                    
                    // Pusatkan peta ke marker
                    map.setView([lat, lng], map.getZoom());
                }

                // Fungsi untuk mengupdate field form jika kosong
                function updateFormFields(addressData) {
                    const address = addressData.address || {};
                    
                    if (!@js($address)) {
                        const fullAddress = [
                            address.road ? (address.road + (address.house_number ? ' No. ' + address.house_number : '')) : '',
                            address.neighbourhood ? 'RT/RW ' + address.neighbourhood : '',
                            address.village ? 'Kel. ' + address.village : '',
                            address.city_district ? 'Kec. ' + address.city_district : '',
                            address.postcode ? 'Kode Pos: ' + address.postcode : ''
                        ].filter(Boolean).join(', ');
                        
                        @this.set('address', fullAddress);
                    }
                    
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
                    resultsContainer.innerHTML = '<div class="dropdown-item text-center py-2"><div class="spinner-border spinner-border-sm text-primary"></div> Mencari...</div>';
                    resultsContainer.style.display = 'block';

                    fetch(
                            `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&countrycodes=id&limit=5`)
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
                    if (!e.target.closest('#searchResults') && !e.target.closest('#btnSearchLocation') && !e.target.closest('#searchLocationInput')) {
                        document.getElementById('searchResults').style.display = 'none';
                    }
                });

                // Handle geolocation request dari Livewire
                Livewire.on('request-browser-location', async () => {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(
                            async (position) => {
                                const lat = position.coords.latitude;
                                const lng = position.coords.longitude;
                                await updateMarker(lat, lng);
                                @this.set('latitude', lat);
                                @this.set('longitude', lng);
                                
                                // Update search input with approximate location
                                const address = await getAddressInfo(lat, lng);
                                document.getElementById('searchLocationInput').value = address.address.road || 
                                    address.address.neighbourhood || 
                                    address.address.village || 
                                    'Lokasi Saya';
                            },
                            (error) => {
                                console.error('Geolocation error:', error);
                                alert('Gagal mendapatkan lokasi: ' + error.message);
                            }, {
                                enableHighAccuracy: true,
                                timeout: 5000,
                                maximumAge: 0
                            }
                        );
                    } else {
                        alert('Browser Anda tidak mendukung geolokasi');
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
            border: 1px solid rgba(0,0,0,.15);
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,.175);
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

        .marker-popup {
            bottom: 30px !important;
            left: -100px !important;
        }
        
        .marker-popup .leaflet-popup-content-wrapper {
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.2);
            padding: 0;
        }
        
        .marker-popup .leaflet-popup-content {
            margin: 0;
            padding: 12px;
        }
        
        .marker-popup .leaflet-popup-tip-container {
            display: none;
        }
    </style>
</div>