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
                                <th>Harga</th>
                                <th>Diskon (%)</th>
                                <th>Min. Stok</th>
                                <th>Stok</th>
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
                                        @if ($product->discount > 0)
                                            <span class="text-danger text-decoration-line-through">
                                                Rp {{ number_format($product->price, 0, ',', '.') }}
                                            </span>
                                            <br>
                                            <strong>Rp {{ number_format($product->price_after_discount, 0, ',', '.') }}</strong>
                                        @else
                                            <strong>Rp {{ number_format($product->price, 0, ',', '.') }}</strong>
                                        @endif
                                    </td>
                                    <td>{{ $product->discount ?? 0 }}%</td>
                                    <td>{{ $product->minimum_stock ?? 0 }}</td>
                                    <td>0</td> {{-- Placeholder stok --}}
                                    <td>
                                        <a href="{{ route('admin.products.edit', ['product' => $product->id]) }}" class="btn btn-warning">
                                            Edit
                                        </a>
                                        <a href="#" class="btn btn-danger" onclick="confirmDelete({{ $product->id }})">
                                            Hapus
                                        </a>
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
