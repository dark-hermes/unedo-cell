<div>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary mb-3">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
    <livewire:admin.partials.page-heading title="Detail Pesanan #{{ $order->id }}" subtitle="" :breadcrumbs="[
        ['label' => 'Pesanan Masuk', 'href' => route('admin.orders.index')],
        ['label' => 'Detail Pesanan', 'href' => ''],
    ]" />

    <div class="card">
        <div class="card-content">
            <div class="card-body">
                <div class="row">
                    <!-- Informasi Pesanan -->
                    <div class="col-md-6">
                        <div class="border rounded p-3 mb-3">
                            <h5 class="mb-3">Informasi Pesanan</h5>
                            <div class="row">
                                <div class="col-6">
                                    <p class="mb-1"><strong>Nomor Pesanan</strong></p>
                                    <p>{{ $order->id }}</p>
                                </div>
                                <div class="col-6">
                                    <p class="mb-1"><strong>Tanggal Pesanan</strong></p>
                                    <p>{{ $order->created_at->format('d M Y H:i') }}</p>
                                </div>
                                <div class="col-6">
                                    <p class="mb-1"><strong>Status</strong></p>
                                    <p>
                                        @if ($order->order_status == 'pending')
                                            <span class="badge bg-warning text-dark">{{ $order->order_status }}</span>
                                        @elseif ($order->order_status == 'confirmed')
                                            @if ($order->transaction->transaction_status == 'pending')
                                                <span class="badge bg-warning text-dark">Menunggu Pembayaran</span>
                                            @elseif ($order->transaction->transaction_status == 'settlement')
                                                <span class="badge bg-success">Pembayaran Berhasil</span>
                                            @endif
                                        @elseif ($order->order_status == 'shipped')
                                            <span class="badge bg-info">{{ $order->order_status }}</span>
                                        @elseif ($order->order_status == 'delivered')
                                            <span class="badge bg-success">{{ $order->order_status }}</span>
                                        @elseif ($order->order_status == 'cancelled')
                                            <span class="badge bg-danger">{{ $order->order_status }}</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-6">
                                    <p class="mb-1"><strong>Total Pembayaran</strong></p>
                                    <p class="fw-bold">Rp{{ number_format($order->total_price, 0, ',', '.') }}</p>
                                </div>
                                <div class="col-12">
                                    <p class="mb-1"><strong>Catatan Pesanan</strong></p>
                                    <p>{{ $order->note ?? '-' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Pembeli -->
                        <div class="border rounded p-3 mb-3">
                            <h5 class="mb-3">Informasi Pembeli</h5>
                            <div class="row">
                                <div class="col-12">
                                    <p class="mb-1"><strong>Nama Pembeli</strong></p>
                                    <p>{{ $order->user->name }}</p>
                                </div>
                                <div class="col-6">
                                    <p class="mb-1"><strong>Email</strong></p>
                                    <p>
                                        <a href="mailto:{{ $order->user->email }}">{{ $order->user->email }}</a>
                                    </p>
                                </div>
                                <div class="col-6">
                                    <p class="mb-1"><strong>Telepon</strong></p>
                                    <p>
                                        <a href="https://wa.me/{{ $order->user->phone }}" target="_blank">
                                            {{ $order->user->phone }}
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Pengiriman -->
                    <div class="col-md-6">
                        <div class="border rounded p-3 mb-3">
                            <h5 class="mb-3">Informasi Pengiriman</h5>
                            <div class="row">
                                <div class="col-12">
                                    <p class="mb-1"><strong>Nama Penerima</strong></p>
                                    <p>{{ $order->recipient_name }}</p>
                                </div>
                                <div class="col-6">
                                    <p class="mb-1"><strong>Telepon Penerima</strong></p>
                                    <p>{{ $order->recipient_phone }}</p>
                                </div>
                                <div class="col-6">
                                    <p class="mb-1"><strong>Metode Pengiriman</strong></p>
                                    <p>
                                        @switch($order->shipping_method)
                                            @case('self_pickup')
                                                Ambil di Toko
                                            @break

                                            @case('unedo')
                                                Kurir Unedo
                                            @break

                                            @case('courir')
                                                Kurir Ekspedisi
                                            @break

                                            @default
                                                {{ $order->shipping_method }}
                                        @endswitch
                                    </p>
                                </div>
                                <div class="col-12">
                                    <p class="mb-1"><strong>Alamat Pengiriman</strong></p>
                                    <p>{{ $order->address }}</p>
                                </div>
                                @if ($order->receipt_number)
                                    <div class="col-12">
                                        <p class="mb-1"><strong>Nomor Resi</strong></p>
                                        <p>{{ $order->receipt_number }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Aksi Pesanan -->
                        <div class="border rounded p-3">
                            <h5 class="mb-3">Aksi Pesanan</h5>
                            <div class="d-flex flex-wrap gap-2">
                                @if ($order->order_status == 'pending')
                                    <button wire:click.prevent="confirmOrder" wire:loading.attr="disabled"
                                        wire:target="confirmOrder" @if ($isProcessing) disabled @endif
                                        class="btn btn-primary">
                                        <span wire:loading.remove wire:target="confirmOrder">
                                            <i class="bi bi-check-circle"></i> Konfirmasi Pesanan
                                        </span>
                                        <span wire:loading wire:target="confirmOrder">
                                            <span class="spinner-border spinner-border-sm" role="status"></span>
                                            Memproses...
                                        </span>
                                    </button>

                                    <button wire:click.prevent="cancelOrder" wire:loading.attr="disabled"
                                        wire:target="cancelOrder" class="btn btn-danger">
                                        <span wire:loading.remove wire:target="cancelOrder">
                                            <i class="bi bi-x-circle"></i> Batalkan Pesanan
                                        </span>
                                        <span wire:loading wire:target="cancelOrder">
                                            <span class="spinner-border spinner-border-sm" role="status"></span>
                                            Memproses...
                                        </span>
                                    </button>
                                @elseif($order->order_status == 'confirmed')
                                    <button wire:click.prevent="shipOrder" class="btn btn-info">
                                        <i class="bi bi-truck"></i> Tandai Dikirim
                                    </button>
                                    <button wire:click.prevent="cancelOrder" class="btn btn-danger">
                                        <i class="bi bi-x-circle"></i> Batalkan Pesanan
                                    </button>
                                @elseif($order->order_status == 'shipped')
                                    <button class="btn btn-success" disabled>
                                        <i class="bi bi-check-circle"></i> Pesanan Telah Dikirim
                                    </button>
                                @elseif($order->order_status == 'cancelled')
                                    <button class="btn btn-secondary" disabled>
                                        <i class="bi bi-x-circle"></i> Pesanan Dibatalkan
                                    </button>
                                @endif

                                @if (!$showShippingForm && $order->order_status == 'pending')
                                    <button wire:click.prevent="editShippingCost" class="btn btn-outline-primary">
                                        <i class="bi bi-pencil"></i> Ubah Ongkir
                                    </button>
                                @endif

                                @if ($showShippingForm)
                                    <div class="border rounded p-3 mt-3">
                                        <h5 class="mb-3">Ubah Biaya Pengiriman</h5>
                                        <form>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="shipping_cost">Biaya Pengiriman</label>
                                                        <input type="number" class="form-control" id="shipping_cost"
                                                            wire:model="shipping_cost"
                                                            placeholder="Masukkan biaya pengiriman" min="0">
                                                    </div>
                                                </div>
                                                <div class="col-md-8 d-flex align-items-end gap-2">
                                                    <button type="submit" class="btn btn-primary"
                                                        wire:click.prevent="updateShippingCost"
                                                        wire:loading.attr="disabled">
                                                        <span wire:loading.remove wire:target="updateShippingCost">
                                                            <i class="bi bi-save"></i> Simpan
                                                        </span>
                                                        <span wire:loading wire:target="updateShippingCost">
                                                            <span class="spinner-border spinner-border-sm"
                                                                role="status"></span>
                                                            Memproses...
                                                        </span>
                                                    </button>
                                                    <button type="button"
                                                        wire:click.prevent="$set('showShippingForm', false)"
                                                        class="btn btn-outline-secondary">
                                                        <i class="bi bi-x"></i> Batal
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                @endif




                            </div>
                        </div>
                    </div>
                </div>

                <!-- Daftar Produk -->
                <div class="border rounded p-3 mt-3">
                    <h5 class="mb-3">Daftar Produk</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th width="50px">No</th>
                                    <th>Produk</th>
                                    <th>Harga Satuan</th>
                                    <th>Jumlah</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->orderItems as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $item->product->image_url }}"
                                                    alt="{{ $item->product->name }}" class="img-thumbnail me-2"
                                                    style="width: 50px; height: 50px; object-fit: cover;">
                                                <div>
                                                    <p class="mb-0">{{ $item->product->name }}</p>
                                                    <small class="text-muted">SKU: {{ $item->product->sku }}</small>
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
                                    <td colspan="4" class="text-end"><strong>Subtotal Produk</strong></td>
                                    <td>Rp{{ number_format($order->orderItems->sum('total_price'), 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Biaya Pengiriman</strong></td>
                                    <td>Rp{{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Total Pembayaran</strong></td>
                                    <td class="fw-bold">Rp{{ number_format($order->total_price, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Form Update Resi (jika status shipped) -->
                @if ($order->order_status == 'shipped' && !$order->receipt_number)
                    <div class="border rounded p-3 mt-3">
                        <h5 class="mb-3">Update Nomor Resi</h5>
                        <form>
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="receipt_number">Nomor Resi Pengiriman</label>
                                        <input type="text" class="form-control" id="receipt_number"
                                            wire:model="receipt_number" placeholder="Masukkan nomor resi">
                                    </div>
                                </div>
                                <div class="col-md-4 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary"
                                        wire:click.prevent="inputReceiptNumber" wire:loading.attr="disabled"
                                        wire:target="inputReceiptNumber">
                                        <span wire:loading.remove wire:target="inputReceiptNumber">
                                            <i class="bi bi-save"></i> Simpan Resi
                                        </span>
                                        <span wire:loading wire:target="inputReceiptNumber">
                                            <span class="spinner-border spinner-border-sm" role="status"></span>
                                            Memproses...
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@push('styles')
    <style>
        .spinner {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            100% {
                transform: rotate(360deg);
            }
        }
    </style>
@endpush
