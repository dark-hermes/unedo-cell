<div>
    <div class="container-fluid p-0 m-0">
        <div class="row no-gutters vh-100">
            <!-- Sidebar kiri -->
            <div class="col-md-3 border-right bg-white">
                <div class="d-flex flex-column align-items-center text-center p-3 py-5">
                    <img class="rounded-circle mt-5" width="150px" src="{{ $user->image_url }}">
                    @if ($showPhotoInput)
                        <div class="mt-3">
                            <input type="file" accept="image/*" wire:model="newImage" class="form-control mb-2">
                            @error('newImage')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    @endif

                    <div class="btn-group mt-2">
                        <button type="button" wire:click="$toggle('showPhotoInput')"
                            class="btn btn-sm {{ $showPhotoInput ? 'btn-outline-secondary' : 'btn-outline-primary' }}">
                            {{ $showPhotoInput ? 'Batal' : 'Ubah Foto' }}
                        </button>

                        @if ($user->image)
                            <button type="button" wire:click="removePhoto" class="btn btn-sm btn-outline-danger">
                                Hapus Foto
                            </button>
                        @endif
                    </div>

                    <span class="font-weight-bold mt-3">{{ $user->name }}</span>
                    <span class="text-black-50">{{ $user->email }}</span>
                </div>
            </div>

            <!-- Form tengah -->
            <div class="col-md-5 border-right bg-white">
                <div class="p-3 py-5">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="text-right">Pengaturan Profil</h4>
                    </div>

                    <form wire:submit.prevent="update">
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <label class="labels">Nama lengkap</label>
                                <input wire:model.defer="name" type="text"
                                    class="form-control @error('name') is-invalid @enderror"
                                    placeholder="Masukkan nama lengkap">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-12">
                                <label class="labels">Email</label>
                                <input wire:model.defer="email" type="text"
                                    class="form-control @error('email') is-invalid @enderror"
                                    placeholder="Masukkan email">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-12">
                                <label class="labels">Nomor telepon</label>
                                <input wire:model.defer="phone" type="text"
                                    class="form-control @error('phone') is-invalid @enderror"
                                    placeholder="Masukkan nomor telepon">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-12">
                                <label class="labels">Alamat</label>
                                <div class="alamat-wrapper-profile">
                                    <input type="text" class="form-control alamat-input-profile"
                                        value="{{ $user->addresses()->where('is_default', true)->first()->name ?? '' }}"
                                        readonly>
                                    <a href="{{ route('address.index') }}" class="alamat-ubah-link-profile">Ubah</a>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 text-center">
                            <button class="btn btn-warning text-white profile-button" type="submit">
                                Simpan profil
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('livewire:load', function() {
                Livewire.on('show-toast', (data) => {
                    Toast.fire({
                        icon: data.type,
                        title: data.message
                    });
                });
            });
        </script>
    @endpush
</div>
