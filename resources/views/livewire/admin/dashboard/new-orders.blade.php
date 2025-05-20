<div class="card">
    <div class="card-header">
        <h4>5 Pesanan Masuk Baru</h4>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-lg">
                <thead>
                    <tr>
                        <th>Pelanggan</th>
                        <th>Produk</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td class="col-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-md">
                                    <img src="{{ $order->user->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($order->user->name).'&background=random' }}" />
                                </div>
                                <p class="font-bold ms-3 mb-0">{{ $order->user->name }}</p>
                            </div>
                        </td>
                        <td class="col-auto">
                            @foreach($order->orderItems as $item)
                            <p class="mb-0">{{ $item->product->name }} ({{ $item->quantity }})</p>
                            @endforeach
                        </td>
                        <td>Rp {{ number_format($order->total_price) }}</td>
                        <td>{{ $order->order_status }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>