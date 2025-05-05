<div class="container py-4">
    <h2 class="mb-4">Riwayat Pesanan</h2>

    @if ($orders->isEmpty())
        <div class="alert alert-info">
            Anda belum memiliki pesanan.
        </div>
    @else
        <div class="row">
            @foreach ($orders as $order)
                <div class="col-md-12 mb-4">
                    <div class="card order-card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <!-- Gambar produk pertama -->
                                <div class="col-md-1">
                                    @if ($order->orderItems->isNotEmpty())
                                        <img src="{{ $order->orderItems->first()->product->image_url ?? asset('images/default-product.jpg') }}"
                                            alt="{{ $order->orderItems->first()->name }}" class="img-fluid rounded"
                                            style="max-height: 60px;">
                                    @else
                                        <img src="{{ asset('images/default-product.jpg') }}" alt="No image"
                                            class="img-fluid rounded" style="max-height: 60px;">
                                    @endif
                                </div>

                                <!-- Nama produk (+n more) -->
                                <div class="col-md-3">
                                    @if ($order->orderItems->isNotEmpty())
                                        <h6 class="mb-1">{{ $order->orderItems->first()->name }}</h6>
                                        @if ($order->orderItems->count() > 1)
                                            <small class="text-muted">+{{ $order->orderItems->count() - 1 }} produk
                                                lainnya</small>
                                        @endif
                                    @else
                                        <h6 class="mb-1">Produk tidak tersedia</h6>
                                    @endif
                                </div>

                                <!-- Alamat pengiriman -->
                                <div class="col-md-2">
                                    <small class="text-muted">Alamat</small>
                                    <p class="mb-0 text-truncate" style="max-width: 150px;"
                                        title="{{ $order->address }}">
                                        {{ $order->address }}
                                    </p>
                                </div>

                                <!-- Metode pengiriman -->
                                <div class="col-md-2">
                                    <small class="text-muted">Pengiriman</small>
                                    <p class="mb-0">
                                        @switch($order->shipping_method)
                                            @case('self_pickup')
                                                Ambil di Toko
                                            @break

                                            @case('unedo')
                                                Kurir Unedo
                                            @break

                                            @case('courier')
                                                Kurir Ekspedisi
                                            @break

                                            @default
                                                {{ $order->shipping_method }}
                                        @endswitch
                                    </p>
                                </div>

                                <!-- Total harga -->
                                <div class="col-md-2">
                                    <small class="text-muted">Total</small>
                                    <p class="mb-0 font-weight-bold">
                                        Rp{{ number_format($order->total_price, 0, ',', '.') }}
                                    </p>
                                </div>

                                <!-- Status order -->
                                <div class="col-md-2">
                                    <small class="text-muted">Status</small>
                                    <p class="mb-0">
                                        <span
                                            class="text
                                                @if ($order->order_status == 'pending') text-warning
                                                @elseif ($order->order_status == 'confirmed') text-primary
                                                @elseif ($order->order_status == 'shipped') text-info
                                                @elseif ($order->order_status == 'delivered') text-success
                                                @elseif ($order->order_status == 'cancelled') text-danger
                                                @else text-secondary @endif">
                                            @if ($order->order_status == 'pending')
                                                Menunggu Konfirmasi
                                            @elseif ($order->order_status == 'confirmed')
                                                @if ($order->transaction->transaction_status == 'pending')
                                                    Menunggu Pembayaran
                                                @elseif ($order->transaction->transaction_status == 'settlement')
                                                    Pembayaran Berhasil
                                                @endif
                                            @elseif ($order->order_status == 'shipped')
                                                Dalam Pengiriman
                                            @elseif ($order->order_status == 'delivered')
                                                Selesai
                                            @elseif ($order->order_status == 'cancelled')
                                                Dibatalkan
                                            @else
                                                {{ ucfirst($order->order_status) }}
                                            @endif
                                        </span>
                                    </p>
                                </div>
                            </div>

                            <!-- Action buttons -->
                            <div class="mt-2">
                                @if ($order->order_status === 'pending')
                                    <button wire:click.prevent="cancelOrder('{{ $order->id }}')"
                                        class="btn btn-sm btn-danger">
                                        Batalkan Pesanan
                                    </button>
                                @elseif ($order->order_status === 'confirmed' && $order->transaction->transaction_status === 'pending')
                                {{-- with loading --}}
                                    <a href="{{ route('orders.payment', $order->id) }}"
                                        class="btn btn-sm btn-primary">
                                        Bayar Sekarang
                                    </a>
                                @elseif ($order->order_status === 'delivered')
                                    <button wire:click.prevent="completeOrder('{{ $order->id }}')"
                                        class="btn btn-sm btn-success">
                                        Tandai Selesai
                                    </button>
                                @endif
                            </div>

                            <!-- Tombol toggle detail -->
                            <div class="text-center mt-3">
                                <button class="btn btn-sm btn-outline-primary toggle-detail"
                                    wire:click="$toggle('showDetails.{{ $order->id }}')">
                                    <span wire:loading.remove wire:target="$toggle('showDetails.{{ $order->id }}')">
                                        {{ $showDetails[$order->id] ?? false ? 'Sembunyikan Detail' : 'Lihat Detail' }}
                                    </span>
                                    <span wire:loading wire:target="$toggle('showDetails.{{ $order->id }}')">
                                        <i class="fas fa-spinner fa-spin"></i>
                                    </span>
                                </button>
                            </div>

                            <!-- Detail order (expandable) -->
                            @if ($showDetails[$order->id] ?? false)
                                <div class="mt-3 border-top pt-3">
                                    <h5>Detail Pesanan</h5>
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Produk</th>
                                                    <th>Harga</th>
                                                    <th>Jumlah</th>
                                                    <th>Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($order->orderItems as $item)
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <img src="{{ $item->product->image_url ?? asset('images/default-product.jpg') }}"
                                                                    alt="{{ $item->name }}" class="img-thumbnail"
                                                                    style="width: 50px; height: 50px; object-fit: cover;">
                                                                <div class="ml-2">
                                                                    <p class="mb-0">{{ $item->name }}</p>
                                                                    <small class="text-muted">SKU:
                                                                        {{ $item->sku }}</small>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>Rp{{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                                        <td>{{ $item->quantity }}</td>
                                                        <td>Rp{{ number_format($item->total_price, 0, ',', '.') }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="3" class="text-right"><strong>Subtotal
                                                            Produk</strong></td>
                                                    <td>Rp{{ number_format($order->orderItems->sum('total_price'), 0, ',', '.') }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3" class="text-right"><strong>Biaya
                                                            Pengiriman</strong></td>
                                                    <td>Rp{{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3" class="text-right"><strong>Total
                                                            Pembayaran</strong></td>
                                                    <td class="font-weight-bold">
                                                        Rp{{ number_format($order->total_price, 0, ',', '.') }}</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>

                                    <!-- Informasi tambahan -->
                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <h6>Informasi Pengiriman</h6>
                                            <p class="mb-1"><strong>Penerima:</strong> {{ $order->recipient_name }}
                                            </p>
                                            <p class="mb-1"><strong>Telepon:</strong> {{ $order->recipient_phone }}
                                            </p>
                                            <p class="mb-1"><strong>Alamat:</strong> {{ $order->address }}</p>
                                            <p class="mb-1"><strong>Metode:</strong>
                                                @switch($order->shipping_method)
                                                    @case('self_pickup')
                                                        Ambil di Toko
                                                    @break

                                                    @case('unedo')
                                                        Kurir Unedo
                                                    @break

                                                    @case('courier')
                                                        Kurir Ekspedisi
                                                    @break

                                                    @default
                                                        {{ $order->shipping_method }}
                                                @endswitch
                                            </p>
                                            @if (in_array($order->order_status, ['shipped', 'delivered']) && $order->shipping_method === 'courier')
                                                <p class="mb-1"><strong>No. Resi:</strong>
                                                    {{ $order->receipt_number ?? 'Belum tersedia' }}</p>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Informasi Pesanan</h6>
                                            <p class="mb-1"><strong>No. Pesanan:</strong> {{ $order->id }}</p>
                                            <p class="mb-1"><strong>Tanggal:</strong>
                                                {{ $order->created_at->format('d M Y H:i') }}</p>
                                            <p class="mb-1"><strong>Status:</strong>
                                                <span
                                                    class="text
                                                @if ($order->order_status == 'pending') text-warning
                                                @elseif ($order->order_status == 'confirmed') text-primary
                                                @elseif ($order->order_status == 'shipped') text-info
                                                @elseif ($order->order_status == 'delivered') text-success
                                                @elseif ($order->order_status == 'cancelled') text-danger
                                                @else text-secondary @endif">
                                                    @if ($order->order_status == 'pending')
                                                        Menunggu Konfirmasi
                                                    @elseif ($order->order_status == 'confirmed')
                                                        @if ($order->transaction->transaction_status == 'pending')
                                                            Menunggu Pembayaran
                                                        @elseif ($order->transaction->transaction_status == 'settlement')
                                                            Pembayaran Berhasil
                                                        @endif
                                                    @elseif ($order->order_status == 'shipped')
                                                        Dalam Pengiriman
                                                    @elseif ($order->order_status == 'delivered')
                                                        Selesai
                                                    @elseif ($order->order_status == 'cancelled')
                                                        Dibatalkan
                                                    @else
                                                        {{ ucfirst($order->order_status) }}
                                                    @endif
                                                </span>
                                            </p>
                                            @if ($order->note)
                                                <p class="mb-1"><strong>Catatan:</strong> {{ $order->note }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

@push('styles')
    <style>
        .order-card {
            transition: all 0.3s ease;
            border: 1px solid #e0e0e0;
        }

        .order-card:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .toggle-detail {
            transition: all 0.3s ease;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
    </style>
@endpush
