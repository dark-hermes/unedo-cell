<div>
    <livewire:admin.partials.page-heading title="Daftar Pesanan Masuk" subtitle="" :breadcrumbs="[['label' => 'Pesanan Masuk', 'href' => '/admins/orders']]" />

    <div class="row mb-3">
        <div class="col-12 col-md-6">
            {{-- <a href="{{ route('admin.orders.create') }}" class="btn btn-primary">
                <i class="bi bi-plus"></i> Tambah Produk
            </a> --}}
        </div>
        <div class="col-12 col-md-6 d-flex justify-content-md-end align-items-center">
            <div class="input-group w-75">
                <input type="text" class="form-control" wire:model.live.debounce.500ms="search"
                    placeholder="Cari produk..." name="search" />
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
                                <th>Waktu</th>
                                <th>Barang</th>
                                <th>Metode Pengiriman</th>
                                <th>Nama Pembeli</th>
                                <th>Kontak Pembeli</th>
                                <th>Nominal</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr wire:key="order-{{ $order->id }}">
                                    <td>
                                        {{ $order->created_at->format('d-m-Y H:i') }}
                                    </td>
                                    <td>
                                        <img src="{{ $order->orderItems->first()->product->image_url }}"
                                            alt="{{ $order->orderItems->first()->product->name }}" class="img-thumbnail"
                                            style="width: 64px; height: 64px; object-fit: cover;" />

                                        {{ $order->orderItems->first()->product->name }}

                                        @if ($order->orderItems->count() > 1)
                                            <span class="badge bg-secondary">
                                                +{{ $order->orderItems->count() - 1 }} produk lainnya
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $order->shipping_method }}
                                    </td>
                                    <td>
                                        {{ $order->user->name }}    
                                    </td>    
                                    <td>
                                        {{-- {{ $order->user->phone ?? '-' }} --}}
                                        <a href="https://wa.me/{{ $order->user->phone ?? '-' }}"
                                            target="_blank">
                                            {{ $order->user->phone ?? '-' }}
                                        </a>
                                        <br>
                                        <a href="mailto:{{ $order->user->email ?? '-' }}">
                                            {{ $order->user->email ?? '-' }}
                                        </a>
                                    </td>
                                    <td>
                                        Rp{{ number_format($order->total_price, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        {{-- pending, confirmed, shipped, completed, cancelled --}}
                                        @if ($order->order_status == 'pending')
                                            <span class="badge bg-warning text-dark">{{ $order->order_status }}</span>
                                        @elseif ($order->order_status == 'confirmed')
                                            <span class="badge bg-primary">{{ $order->order_status }}</span>
                                        @elseif ($order->order_status == 'shipped')
                                            <span class="badge bg-info">{{ $order->order_status }}</span>
                                        @elseif ($order->order_status == 'completed')
                                            <span class="badge bg-success">{{ $order->order_status }}</span>
                                        @elseif ($order->order_status == 'cancelled')
                                            <span class="badge bg-danger">{{ $order->order_status }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-secondary dropdown-toggle" type="button"
                                                id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots"></i>
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton"
                                                role="menu">
                                                <li><a class="dropdown-item"
                                                        href="{{ route('admin.orders.show', ['order' => $order->id]) }}">Detail
                                                        Pesanan</a></li>
                                                {{-- <li><a class="dropdown-item"
                                                        href="{{ route('admin.orders.history-stock.index', ['order' => $order->id]) }}">Riwayat
                                                        Stok</a></li>
                                                <li><a class="dropdown-item"
                                                        href="{{ route('admin.orders.stock-entries.index', ['order' => $order->id]) }}">Stok
                                                        Masuk</a></li>
                                                <li><a class="dropdown-item"
                                                        href="{{ route('admin.orders.stock-outputs.index', ['order' => $order->id]) }}">Stok
                                                        Keluar</a></li>
                                                <li><a class="dropdown-item text-danger" href="#"
                                                        onclick="confirmDelete({{ $order->id }})">Delete</a></li> --}}
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-3">
                        {{ $orders->links('pagination::bootstrap-5') }}
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
