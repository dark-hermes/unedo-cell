<div>
    <livewire:admin.partials.page-heading title="Buat Konten Beranda" subtitle="" 
        :breadcrumbs="[['label' => 'Konten Beranda', 'href' => '/home-contents'], ['label' => 'Create']]" />

    <section class="section">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <form>
                            <div class="form-group">
                                <label for="title">Judul</label>
                                <input type="text" id="title" placeholder="Masukkan Judul" name="title" required
                                    wire:model.defer="title" class="form-control @error('title') is-invalid @enderror">
                                @error('title')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="description">Deskripsi</label>
                                <textarea id="description" name="description" placeholder="Masukkan Deskripsi" rows="4"
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
                                <button type="submit" class="btn btn-primary" wire:click.prevent="store">
                                    Simpan
                                </button>
                                <a href="{{ route('admin.products.categories.index') }}" class="btn btn-secondary">Kembali</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
