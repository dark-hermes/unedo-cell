<div>
    <livewire:admin.partials.page-heading 
        title="Buat Produk" 
        :breadcrumbs="[['label' => 'Produk', 'href' => '/products'], ['label' => 'Create']]" />

    <section class="section">
        <div class="card">
            <div class="card-body">
                <form wire:submit.prevent="store">
                    <div class="form-group">
                        <label for="category_id">Kategori</label>
                        <select wire:model.defer="category_id" id="category_id" class="form-control @error('category_id') is-invalid @enderror">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label for="name">Nama Produk</label>
                        <input wire:model.defer="name" id="name" type="text" class="form-control @error('name') is-invalid @enderror">
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label for="price">Harga</label>
                        <input wire:model.defer="price" id="price" type="number" step="0.01" class="form-control @error('price') is-invalid @enderror">
                        @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label for="discount">Diskon (%)</label>
                        <input wire:model.defer="discount" id="discount" type="number" max="100" class="form-control @error('discount') is-invalid @enderror">
                        @error('discount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label for="min_stock">Stok Minimum</label>
                        <input wire:model.defer="min_stock" id="min_stock" type="number" class="form-control @error('min_stock') is-invalid @enderror">
                        @error('min_stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label for="description">Deskripsi</label>
                        <textarea wire:model.defer="description" id="description" class="form-control @error('description') is-invalid @enderror"></textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label for="image">Gambar</label>
                        <input wire:model="image" type="file" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                        @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        @if ($image)
                            <div class="mt-2">
                                <img src="{{ $image->temporaryUrl() }}" width="200" class="img-thumbnail">
                            </div>
                        @endif
                    </div>

                    <div class="button-group mt-4">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
