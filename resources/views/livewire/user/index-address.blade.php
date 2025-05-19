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
                <input type="text" class="form-control" 
                       wire:model.live.debounce.300ms="search" 
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
                        @if($address->is_default)
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
                            @if($address->latitude && $address->longitude)
                                <i class="bi bi-geo-alt-fill"></i> <strong>Sudah Pinpoint</strong>
                            @endif
                            
                            {{-- <a href="#" wire:click.prevent="$dispatch('openModal', { component: 'user.edit-address', arguments: { address: {{ $address->id }} }})">
                                Ubah Alamat
                            </a> --}}

                            <a href="{{ route('address.edit', $address->id) }}" class="text-warning">
                                Edit Alamat
                            </a>
                            
                            @if(!$address->is_default)
                                <a href="#" wire:click.prevent="confirmDelete({{ $address->id }})">Hapus</a>
                            @endif
                        </div>
                        
                        @if(!$address->is_default)
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
                    {{-- <button class="btn btn-warning text-white"
                            wire:click="$dispatch('openModal', { component: 'user.create-address' })">
                        Tambah Alamat Pertama
                    </button> --}}
                </div>
            @endforelse
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    {{-- <x-modal wire:model="confirmingDeletion">
        <x-card title="Hapus Alamat">
            <p class="text-center">Apakah Anda yakin untuk menghapus alamat ini? Anda tidak dapat mengembalikan alamat yang sudah dihapus.</p>

            <x-slot name="footer">
                <div class="d-flex justify-content-center gap-3">
                    <x-button flat label="Batal" wire:click="$toggle('confirmingDeletion')" />
                    <x-button negative label="Hapus" wire:click="deleteAddress" />
                </div>
            </x-slot>
        </x-card>
    </x-modal> --}}

    @push('scripts')
        <script>
            document.addEventListener('livewire:load', function() {
                // Initialize any necessary scripts here
            });
        </script>
    @endpush
</div>