<div class="card">
    <div class="card-header">
        <h4>Produk Terlaris</h4>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-lg">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Kategori</th>
                        <th>Terjual</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topProducts as $product)
                    <tr>
                        <td class="col-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-md">
                                    <img src="{{ $product->product->image_url }}" />
                                </div>
                                <p class="font-bold ms-3 mb-0">{{ $product->product->name }}</p>
                            </div>
                        </td>
                        <td>{{ $product->product->category->name ?? '-' }}</td>
                        <td>{{ number_format($product->total_sold) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>