<div>
    <livewire:admin.partials.page-heading title="Edit Akun Buyer"
        :breadcrumbs="[['label' => 'Users', 'href' => '/users'], ['label' => 'Edit']]" />

    <section class="section">
        <div class="card">
            <div class="card-body">
                <div class="text-center mb-4">
                    @if ($newImage)
                        {{-- Preview gambar baru --}}
                        <img src="{{ $newImage->temporaryUrl() }}" alt="Foto Baru"
                            class="rounded-circle img-thumbnail" style="width: 150px; height: 150px; object-fit: cover;">
                        <div class="text-muted small mt-1">Foto baru</div>
                    @else
                        {{-- Preview gambar lama --}}
                        <img src="{{ $user->image_url }}" alt="Foto Lama"
                            class="rounded-circle img-thumbnail"
                            style="width: 150px; height: 150px; object-fit: cover;">
                        <div class="text-muted small mt-1">Foto lama</div>
                    @endif

                    <div class="mt-2">
                        <button type="button" wire:click="$toggle('showPhotoInput')"
                            class="btn btn-sm btn-outline-primary">
                            {{ $showPhotoInput ? 'Batal' : 'Ubah Foto' }}
                        </button>

                        @if ($user->image)
                            <button type="button" wire:click="removePhoto" class="btn btn-sm btn-outline-danger">
                                Hapus Foto
                            </button>
                        @endif
                    </div>

                    @if ($showPhotoInput)
                        <div class="mt-3">
                            <input type="file" accept="image/*" wire:model="newImage" class="form-control">
                            @error('newImage')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    @endif
                </div>

                <form wire:submit.prevent="update">
                    <div class="form-group">
                        <label for="name">Nama</label>
                        <input id="name" type="text" wire:model.defer="name"
                            class="form-control @error('name') is-invalid @enderror">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input id="email" type="email" wire:model.defer="email"
                            class="form-control @error('email') is-invalid @enderror">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="phone">No HP</label>
                        <input id="phone" type="text" wire:model.defer="phone"
                            class="form-control @error('phone') is-invalid @enderror">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Status Aktif</label>
                        <select wire:model.defer="is_active" class="form-control">
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                    </div>

                    <div class="button-group mt-4">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Kembali</a>
                        <a href="#" class="btn btn-warning">Ubah Password</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
