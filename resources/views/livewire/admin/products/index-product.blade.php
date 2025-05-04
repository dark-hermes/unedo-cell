<div>
    <livewire:admin.partials.page-heading title="Daftar Produk" subtitle="" :breadcrumbs="[['label' => 'Produk', 'href' => '/admins/products']]" />

    <div class="row mb-3">
        <div class="col-12 col-md-6">
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                <i class="bi bi-plus"></i> Tambah Produk
            </a>
        </div>
        <div class="col-12 col-md-6 d-flex justify-content-md-end align-items-center">
            <div class="input-group w-75">
                <input type="text" class="form-control" wire:model.live.debounce.500ms="search" placeholder="Cari produk..." name="search" />
                <button class="btn btn-primary" type="button">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-content">
            <div class="card-body">
                <div class="table-responsive" wire:loading.remove>
                    <table class="table table-hover table-lg align-middle">
                        <thead>
                            <tr>
                                <th>Gambar</th>
                                <th>Nama</th>
                                <th>Kategori</th>
                                <th>SKU</th>
                                <th>Harga Jual</th>
                                <th>Harga Beli</th>
                                <th>Diskon (%)</th>
                                <th>Harga Diskon</th>
                                <th>Min. Stok</th>
                                <th>Stok</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr wire:key="product-{{ $product->id }}">
                                    <td>
                                        <img src="{{ $product->image_url }}"
                                            alt="{{ $product->name }}" 
                                            class="img-thumbnail" 
                                            style="width: 64px; height: 64px; object-fit: cover;" />
                                    </td>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->category->name ?? '-' }}</td>
                                    <td>{{ $product->sku }}</td>
                                    <td>
                                        Rp{{ number_format($product->sale_price, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        Rp{{ number_format($product->buy_price, 0, ',', '.') }}
                                    </td>
                                    <td>{{ $product->discount ?? 0 }}%</td>
                                    <td>
                                        Rp{{ number_format($product->price_after_discount, 0, ',', '.') }}
                                    </td>
                                    <td>{{ $product->min_stock ?? 0 }}</td>
                                    <td>
                                        @if ($product->stock <= $product->min_stock)
                                            <span class="badge bg-danger">{{ $product->stock }}</span>
                                        @else
                                            {{ $product->stock }}
                                        @endif
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox"
                                                wire:change="toggleStatus({{ $product->id }})"
                                                @checked($product->show) role="switch"
                                                id="switch-{{ $product->id }}">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots"></i>
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton" role="menu">
                                                <li><a class="dropdown-item" href="{{ route('admin.products.edit', ['product' => $product->id]) }}">Edit Produk</a></li>
                                                <li><a class="dropdown-item" href="{{ route('admin.products.history-stock.index', ['product' => $product->id]) }}">Riwayat Stok</a></li>
                                                <li><a class="dropdown-item" href="{{ route('admin.products.stock-entries.index', ['product' => $product->id]) }}">Stok Masuk</a></li>
                                                <li><a class="dropdown-item" href="{{ route('admin.products.stock-outputs.index', ['product' => $product->id]) }}">Stok Keluar</a></li>
                                                <li><a class="dropdown-item text-danger" href="#" onclick="confirmDelete({{ $product->id }})">Delete</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-3">
                        {{ $products->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmDelete(productId) {
            Swal.fire({
                title: 'Hapus Produk',
                text: 'Apakah Anda yakin ingin menghapus produk ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('deleteProduct', productId);
                }
            });
        }
        
    </script>
@endpush
