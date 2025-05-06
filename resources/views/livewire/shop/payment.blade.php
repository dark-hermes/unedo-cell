<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    Pembayaran Pesanan #{{ $order->id }}
                </div>

                <div class="card-body">
                    @if($paymentStatus === 'initial')
                        <div class="alert alert-info">
                            <h5 class="mb-3">Total Pembayaran: Rp{{ number_format($order->total_price, 0, ',', '.') }}</h5>
                            <p>Silakan klik tombol di bawah untuk memulai proses pembayaran.</p>
                            
                            <button wire:click="initializePayment" 
                                    wire:loading.attr="disabled"
                                    class="btn btn-primary">
                                <span wire:loading.remove wire:target="initializePayment">
                                    Mulai Pembayaran
                                </span>
                                <span wire:loading wire:target="initializePayment">
                                    <i class="fas fa-spinner fa-spin"></i> Memproses...
                                </span>
                            </button>
                        </div>
                    @elseif($paymentStatus === 'ready')
                        <div class="alert alert-success">
                            <h5 class="mb-3">Pembayaran Siap Diproses</h5>
                            <p>Anda akan diarahkan ke halaman pembayaran Midtrans.</p>
                            
                            <button wire:click="redirectToPayment" 
                                    class="btn btn-success">
                                Lanjutkan ke Pembayaran
                            </button>
                            
                            <div class="mt-3">
                                <small class="text-muted">
                                    Jika tidak otomatis diarahkan dalam 5 detik, klik tombol di atas.
                                </small>
                            </div>
                            
                            @script
                            <script>
                                // Auto redirect after 5 seconds
                                setTimeout(() => {
                                    window.location.href = @js($paymentUrl);
                                }, 5000);
                            </script>
                            @endscript
                        </div>
                    @elseif($paymentStatus === 'failed')
                        <div class="alert alert-danger">
                            <h5 class="mb-3">Gagal Memproses Pembayaran</h5>
                            <p>{{ $errors->first('payment') }}</p>
                            
                            <button wire:click="initializePayment" 
                                    class="btn btn-primary">
                                Coba Lagi
                            </button>
                            
                            <a href="{{ route('orders.history') }}" class="btn btn-secondary">
                                Kembali ke Riwayat
                            </a>
                        </div>
                    @endif

                    <div class="mt-4">
                        <h5>Detail Pesanan:</h5>
                        <ul class="list-group mb-3">
                            @foreach($order->orderItems as $item)
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>
                                        {{ $item->name }} ({{ $item->quantity }} x Rp{{ number_format($item->unit_price, 0, ',', '.') }})
                                    </span>
                                    <span>
                                        Rp{{ number_format($item->total_price, 0, ',', '.') }}
                                    </span>
                                </li>
                            @endforeach
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Biaya Pengiriman</span>
                                <span>Rp{{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between font-weight-bold">
                                <span>Total Pembayaran</span>
                                <span>Rp{{ number_format($order->total_price, 0, ',', '.') }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>