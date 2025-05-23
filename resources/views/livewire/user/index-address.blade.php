<div class="mb-8">
    <!-- Header Section -->
    <div class="container mt-5">
        <!-- Title and Add Address Button -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0"><strong>Daftar Alamat</strong></h4>
            <a href="{{ route('address.create') }}" class="btn btn-warning text-white fw-bold">
                Tambah Alamat <i class="bi bi-plus-circle ms-1"></i>
            </a>
        </div>

        <!-- Search Input -->
        <div class="mb-4">
            <div class="input-group">
                <span class="input-group-text"><i class="zmdi zmdi-search"></i></span>
                <input type="text" class="form-control" wire:model.live.debounce.300ms="search"
                    placeholder="Cari alamat...">
            </div>
        </div>

        <!-- Address List -->
        <div class="address-list">
            @forelse($addresses as $address)
                <div class="card-alamat mb-3 {{ $address->is_default ? 'alamat-utama' : 'bg-white shadow-sm border' }}">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div><strong>{{ $address->name }}</strong></div>
                        </div>
                        @if ($address->is_default)
                            <i class="bi bi-check2-circle icon-check"></i>
                        @endif
                    </div>

                    <div class="mt-2"><strong>{{ $address->recipient_name }}</strong></div>
                    <div>{{ $address->phone }}</div>

                    <div class="mt-2">
                        {{ $address->address }},
                        {{ $address->note }}
                    </div>

                    <div class="mt-3 d-flex justify-content-between align-items-center flex-wrap">
                        <div class="opsi-link text-warning">
                            @if ($address->latitude && $address->longitude)
                                <i class="bi bi-geo-alt-fill"></i> <strong>Sudah Pinpoint</strong>
                            @endif

                            <a href="{{ route('address.edit', $address->id) }}" class="text-warning">
                                Edit Alamat
                            </a>

                            @if (!$address->is_default)
                                <a href="#" onclick="confirmDelete({{ $address->id }})"
                                    class="text-danger">Hapus</a>
                            @endif
                        </div>

                        @if (!$address->is_default)
                            <button class="btn btn-warning btn-sm btn-pilih text-white"
                                wire:click.prevent="pickDefaultAddress({{ $address->id }})">
                                Pilih
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <i class="bi bi-map-fill text-muted" style="font-size: 3rem;"></i>
                    <p class="mt-3">Anda belum memiliki alamat</p>
                </div>
            @endforelse
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('livewire:init', () => {
                Livewire.on('swal:confirm', (data) => {
                    Swal.fire({
                        title: data.title,
                        text: data.text,
                        icon: data.icon,
                        showCancelButton: data.showCancelButton,
                        confirmButtonText: data.confirmButtonText,
                        cancelButtonText: data.cancelButtonText,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Livewire.dispatch(data.onConfirmed, {
                                params: data.params
                            });
                        }
                    });
                });

                Livewire.on('swal', (data) => {
                    Swal.fire({
                        title: data.title,
                        text: data.text || '',
                        icon: data.icon,
                        confirmButtonText: 'OK'
                    });
                });

            });
        </script>

        <script>
            function confirmDelete(addressId) {
                Swal.fire({
                    title: 'Hapus Alamat',
                    text: 'Apakah Anda yakin ingin menghapus alamat ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.call('deleteAddress', addressId);
                    }
                });
            }
        </script>
    @endpush
</div>
