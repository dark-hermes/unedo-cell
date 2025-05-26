<div>
    <livewire:admin.partials.page-heading 
        title="Edit Produk" 
        :breadcrumbs="[['label' => 'Produk', 'href' => '/admin/products'], ['label' => 'Edit']]" />

    <section class="section">
        <div class="card">
            <div class="card-body">
                <form enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="category_id">Kategori</label>
                        <select wire:model.defer="category_id" id="category_id" class="form-control @error('category_id') is-invalid @enderror">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ $category->id == $product->category_id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label for="name">Nama Produk</label>
                        <input wire:model.defer="name" id="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $product->name) }}">
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label for="sale_price">Harga Jual</label>
                        <input wire:model.defer="sale_price" id="sale_price" type="number" step="0.01" class="form-control @error('sale_price') is-invalid @enderror" value="{{ old('sale_price', $product->sale_price) }}">
                        @error('sale_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label for="buy_price">Harga Beli</label>
                        <input wire:model.defer="buy_price" id="buy_price" type="number" step="0.01" class="form-control @error('buy_price') is-invalid @enderror" value="{{ old('buy_price', $product->buy_price) }}">
                        @error('buy_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label for="discount">Diskon (%)</label>
                        <input wire:model.defer="discount" id="discount" type="number" max="100" class="form-control @error('discount') is-invalid @enderror" value="{{ old('discount', $product->discount) }}">
                        @error('discount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label for="min_stock">Stok Minimum</label>
                        <input wire:model.defer="min_stock" id="min_stock" type="number" class="form-control @error('min_stock') is-invalid @enderror" value="{{ old('min_stock', $product->min_stock) }}">
                        @error('min_stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label for="description">Deskripsi</label>
                        <textarea wire:model.defer="description" id="description" class="form-control @error('description') is-invalid @enderror">{{ old('description', $product->description) }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group mt-4">
                        <label for="image">Foto </label>
                        <div class="d-flex align-items-start gap-4 flex-wrap">
                            <div>
                                @if ($newImage)
                                    {{-- Preview gambar baru --}}
                                    <img src="{{ $newImage->temporaryUrl() }}" alt="Foto Baru"
                                        class="img-thumbnail rounded" style="width: 120px; height: 120px; object-fit: cover;">
                                    <div class="text-muted small mt-1">Foto baru</div>
                                @else
                                    {{-- Preview gambar lama --}}
                                    <img src="{{ $product->image_url }}" alt="Foto Lama"
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
                        <button type="submit" class="btn btn-primary" wire:click.prevent="update">Simpan Perubahan</button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
