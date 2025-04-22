<div>
    <livewire:admin.partials.page-heading title="Daftar Kategori Produk" subtitle="" :breadcrumbs="[['label' => 'Kategori Produk', 'href' => '/admins/products/categories']]" />

    <div class="row">
        <div class="col-12 col-md-6">
            <a href="{{ route('admin.products.categories.create') }}" class="btn btn-primary"><i class="bi bi-plus"></i>
                Tambah Kategori</a>
        </div>
        <div class="col-12 col-md-6 d-flex justify-content-md-end align-items-center">
            <div class="input-group w-75">
                <input type="text" class="form-control" wire:model.live.debounce.500ms="search"
                    placeholder="Cari kategori..." name="search" />
                <button class="btn btn-primary" type="button">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-content">
            <div class="card-body">
                <div class="table-responsive" wire:loading.remove>
                    <table class="table table-lg table-hover">
                        <thead>
                            <tr>
                                <th>Gambar</th>
                                <th>Nama</th>
                                <th>Deskripsi</th>
                                <th>Jumlah Produk</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $category)
                                <tr wire:key="category-{{ $category->id }}">
                                    <td>
                                            <img src="{{ $category->image_url }}" alt="{{ $category->name }}" 
                                                class="img-fluid" style="width: 64px; height: 64px;" />
                                    </td>
                                    <td>{{ $category->name }}</td>
                                    <td>{{ \Illuminate\Support\Str::words(strip_tags($category->description), 20, '...') }}</td>
                                    <td>{{ $category->products_count }} produk</td>
                                    <td>
                                        <a href="{{ route('admin.products.categories.edit', ['category' => $category->id]) }}"
                                            class="btn btn-warning">
                                            Edit
                                        </a>
                                        <a href="#" class="btn btn-danger"
                                            onclick="confirmDelete({{ $category->id }})">
                                            Hapus
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $categories->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        function confirmDelete(categoryId) {
            Swal.fire({
                title: 'Hapus Kategori',
                text: 'Apakah Anda yakin ingin menghapus kategori ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('deleteCategory', categoryId);
                }
            });
        }
    </script>
@endpush
