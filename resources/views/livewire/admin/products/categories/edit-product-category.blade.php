<div>
    <livewire:admin.partials.page-heading title="Edit Kategori Produk"
        :breadcrumbs="[['label' => 'Kategori Produk', 'href' => '/admin/products/categories'], ['label' => 'Edit']]" />

    <section class="section">
        <div class="card">
            <div class="card-body">
                <form wire:submit.prevent="update">
                    <div class="form-group">
                        <label for="name">Nama Kategori</label>
                        <input id="name" type="text" wire:model.defer="name"
                            class="form-control @error('name') is-invalid @enderror">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="code">Kode</label>
                        <input id="code" type="text" wire:model.defer="code"
                            class="form-control @error('code') is-invalid @enderror">
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mt-3">
                        <label for="description">Deskripsi</label>
                        <textarea id="description" wire:model.defer="description" rows="3"
                            class="form-control @error('description') is-invalid @enderror"></textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mt-4">
                        <label for="image">Foto Kategori</label>
                        <div class="d-flex align-items-start gap-4 flex-wrap">
                            <div>
                                @if ($newImage)
                                    {{-- Preview gambar baru --}}
                                    <img src="{{ $newImage->temporaryUrl() }}" alt="Foto Baru"
                                        class="img-thumbnail rounded" style="width: 120px; height: 120px; object-fit: cover;">
                                    <div class="text-muted small mt-1">Foto baru</div>
                                @else
                                    {{-- Preview gambar lama --}}
                                    <img src="{{ $category->image_url }}" alt="Foto Lama"
                                        class="img-thumbnail rounded"
                                        style="width: 120px; height: 120px; object-fit: cover;">
                                    <div class="text-muted small mt-1">Foto lama</div>
                                @endif
                            </div>

                            <div class="flex-grow-1">
                                @if ($showPhotoInput)
                                    <input type="file" accept="image/*" wire:model="newImage" class="form-control mb-2">
                                    @error('newImage')
                                        <div class="text-danger">{{ $message ?? 'Error occurred' }}</div>
                                    @enderror
                                @endif

                                <div class="btn-group">
                                    <button type="button" wire:click="$toggle('showPhotoInput')"
                                        class="btn btn-sm btn-outline-primary">
                                        {{ $showPhotoInput ? 'Batal' : 'Ubah Foto' }}
                                    </button>

                                    @if ($category->image)
                                        <button type="button" wire:click="removePhoto" class="btn btn-sm btn-outline-danger">
                                            Hapus Foto
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="button-group mt-4">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        <a href="{{ route('admin.products.categories.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
