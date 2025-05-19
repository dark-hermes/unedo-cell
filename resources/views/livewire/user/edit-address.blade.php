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
                                    <button type="submit" class="btn btn-primary btn-lg" wire:loading.attr="disabled">
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

                    // Tambahkan marker awal
                    updateMarker(initialLat, initialLng);

                    // Handle klik peta
                    map.on('click', function(e) {
                        updateMarker(e.latlng.lat, e.latlng.lng);
                        @this.set('latitude', e.latlng.lat);
                        @this.set('longitude', e.latlng.lng);
                    });

                    // Perbaiki ukuran peta setelah render
                    setTimeout(() => map.invalidateSize(), 100);
                }

                // Fungsi update marker
                function updateMarker(lat, lng) {
                    if (!map) {
                        console.error('Map not initialized');
                        return;
                    }

                    if (marker) {
                        marker.setLatLng([lat, lng]);
                    } else {
                        marker = L.marker([lat, lng], {
                            draggable: true
                        }).addTo(map);
                        marker.on('dragend', function(e) {
                            const newPos = e.target.getLatLng();
                            @this.set('latitude', newPos.lat);
                            @this.set('longitude', newPos.lng);
                        });
                    }
                    map.setView([lat, lng], map.getZoom());
                }

                // Inisialisasi peta saat komponen siap
                initMap();

                // Handle pencarian lokasi
                document.getElementById('btnSearchLocation')?.addEventListener('click', function() {
                    const query = document.getElementById('searchLocationInput').value.trim();
                    if (!query) return;

                    fetch(
                            `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&countrycodes=id&limit=1`)
                        .then(res => res.json())
                        .then(data => {
                            if (data && data.length > 0) {
                                const lat = parseFloat(data[0].lat);
                                const lng = parseFloat(data[0].lon);
                                updateMarker(lat, lng);
                                @this.set('latitude', lat);
                                @this.set('longitude', lng);
                            } else {
                                alert('Lokasi tidak ditemukan');
                            }
                        });
                });

                // Handle geolocation request dari Livewire
                Livewire.on('request-browser-location', () => {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(
                            (position) => {
                                const lat = position.coords.latitude;
                                const lng = position.coords.longitude;
                                updateMarker(lat, lng);
                                @this.set('latitude', lat);
                                @this.set('longitude', lng);
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
    </style>
</div>