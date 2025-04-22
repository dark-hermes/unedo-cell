<div>
    <livewire:admin.partials.page-heading title="Buat Kategori Produk" subtitle="" 
        :breadcrumbs="[['label' => 'Kategori Produk', 'href' => '/products/categories'], ['label' => 'Create']]" />

    <section class="section">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <form wire:submit.prevent="store">
                            <div class="form-group">
                                <label for="name">Nama Kategori</label>
                                <input type="text" id="name" placeholder="Masukkan Nama Kategori" name="name" required
                                    wire:model.defer="name" class="form-control @error('name') is-invalid @enderror">
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="name">Kode</label>
                                <input type="text" id="code" placeholder="Masukkan Kode" name="code" required
                                    wire:model.defer="code" class="form-control @error('code') is-invalid @enderror">
                                @error('code')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="description">Deskripsi</label>
                                <textarea id="description" name="description" placeholder="Deskripsi Kategori" rows="4"
                                    wire:model.defer="description"
                                    class="form-control @error('description') is-invalid @enderror"></textarea>
                                @error('description')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="image">Gambar</label>
                                <input type="file" id="image" name="image" 
                                    wire:model="image" accept="image/*"
                                    class="form-control @error('image') is-invalid @enderror">
                                @error('image')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                                @if ($image)
                                    <div class="mt-2">
                                        <label>Preview:</label><br>
                                        <img src="{{ $image->temporaryUrl() }}" alt="Preview Gambar" class="img-thumbnail" width="200">
                                    </div>
                                @endif
                            </div>

                            <div class="button-group mt-4">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <a href="{{ route('admin.products.categories.index') }}" class="btn btn-secondary">Kembali</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
