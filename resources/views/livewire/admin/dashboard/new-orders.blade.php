<div class="card">
    <div class="card-header">
        <h4>Pesanan Masuk Baru</h4>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-lg">
                <thead>
                    <tr>
                        <th>Pelanggan</th>
                        <th>Produk</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($orders->count() > 0)
                        @foreach ($orders as $order)
                            <tr>
                                <td class="col-3">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-md">
                                            <img
                                                src="{{ $order->user->image_url }}"/>
                                        </div>
                                        <p class="font-bold ms-3 mb-0">{{ $order->user->name }}</p>
                                    </div>
                                </td>
                                <td class="col-auto">
                                    @foreach ($order->orderItems as $item)
                                        <p class="mb-0">{{ $item->product->name }} ({{ $item->quantity }})</p>
                                    @endforeach
                                </td>
                                <td>Rp {{ number_format($order->total_price) }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="3" class="text-center">
                                <p class="text-muted">Tidak ada pesanan baru</p>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
