<div class="card">
    <div class="card-header">
        <h4>Produk Stok Sedikit</h4>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-lg">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Minimal Stok</th>
                        <th>Stok</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lowStockProducts as $product)
                    <tr>
                        <td class="col-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-md">
                                    <img src="{{ $product->image_url }}" />
                                </div>
                                <p class="font-bold ms-3 mb-0">{{ $product->name }}</p>
                            </div>
                        </td>
                        <td>{{ $product->min_stock }}</td>
                        <td><p class="text-danger">{{ $product->stock }}</p></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>