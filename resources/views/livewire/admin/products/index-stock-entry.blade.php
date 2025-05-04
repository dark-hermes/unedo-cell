<div>
    <livewire:admin.partials.page-heading title="Riwayat Stok Masuk" subtitle="" :breadcrumbs="[['label' => 'Produk', 'href' => '/admins/products'], ['label' => 'Stok Masuk']]" />

    <div class="row">
        <div class="col-12 col-md-6">
            <a href="{{ route('admin.products.stock-entries.create', ['product' => $product->id]) }}" class="btn btn-primary"><i class="bi bi-plus"></i>
                Buat Stok Masuk</a>
        </div>
        <div class="col-12 col-md-6 d-flex justify-content-md-end align-items-center">
            <div class="input-group w-75">
                <input type="text" class="form-control" wire:model.live.debounce.500ms="search"
                    placeholder="Cari kategori..." name="search" />
                <button class="btn btn-primary" type="button">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-content">
            <div class="card-body">
                <div class="table-responsive" wire:loading.remove>
                    <table class="table table-lg table-hover">
                        <thead>
                            <tr>
                                <th>Tanggal Diterima</th>
                                <th>Tanggal Input</th>
                                <th>Terakhir Edit</th>
                                <th>Oleh</th>
                                <th>Sumber</th>
                                <th>Jumlah</th>
                                <th>Catatan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($stockEntries as $stockEntry)
                                <tr wire:key="stock-entry-{{ $stockEntry->id }}">
                                    <td>
                                        {{ $stockEntry->received_at->format('d-m-Y') }}
                                    </td>
                                    <td>
                                        {{ $stockEntry->created_at->format('d-m-Y H:i') }}
                                    </td>
                                    <td>
                                        {{ $stockEntry->updated_at->format('d-m-Y H:i') }}
                                    </td>
                                    <td>
                                        {{ $stockEntry->user->name ?? '-' }}
                                    </td>
                                    <td>
                                        {{ $stockEntry->source_label }}
                                    </td>
                                    <td>
                                        <span class="text-success">
                                            +{{ $stockEntry->quantity }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ Str::limit($stockEntry->note, 20, '...') }}
                                    </td>
                                    <td>
                                        {{-- <a href="{{ route('admin.products.stock-entries.edit', ['stock_entry' => $stockEntry->id]) }}"
                                            class="btn btn-warning">
                                            Edit
                                        </a> --}}
                                        <a href="#" class="btn btn-danger"
                                            onclick="confirmDelete({{ $stockEntry->id }})">
                                            Hapus
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $stockEntries->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        function confirmDelete(stockEntryId) {
            Swal.fire({
                title: 'Hapus Stok Masuk?',
                text: 'Apakah Anda yakin ingin menghapus kategori ini? Tindakan ini sangat krusial dan #',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('deleteStockEntry', stockEntryId);
                }
            });
        }
    </script>
@endpush
