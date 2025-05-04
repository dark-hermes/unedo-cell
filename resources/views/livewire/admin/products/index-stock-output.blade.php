<div>
    <livewire:admin.partials.page-heading title="Riwayat Stok Keluar" subtitle="" :breadcrumbs="[['label' => 'Produk', 'href' => '/admins/products'], ['label' => 'Stok Keluar']]" />

    <div class="row">
        <div class="col-12 col-md-6">
            <a href="{{ route('admin.products.stock-outputs.create', ['product' => $product->id]) }}" class="btn btn-primary"><i class="bi bi-plus"></i>
                Buat Stok Keluar</a>
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
                                <th>Tanggal Keluar</th>
                                <th>Tanggal Output</th>
                                <th>Terakhir Edit</th>
                                <th>Oleh</th>
                                <th>Alasan</th>
                                <th>Jumlah</th>
                                <th>Catatan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($stockOutputs as $stockOutput)
                                <tr wire:key="stock-entry-{{ $stockOutput->id }}">
                                    <td>
                                        {{ $stockOutput->output_date->format('d-m-Y') }}
                                    </td>
                                    <td>
                                        {{ $stockOutput->created_at->format('d-m-Y H:i') }}
                                    </td>
                                    <td>
                                        {{ $stockOutput->updated_at->format('d-m-Y H:i') }}
                                    </td>
                                    <td>
                                        {{ $stockOutput->user->name ?? '-' }}
                                    </td>
                                    <td>
                                        {{ $stockOutput->reason_label }}
                                    </td>
                                    <td>
                                        <span class="text-danger">
                                            -{{ $stockOutput->quantity }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ Str::limit($stockOutput->note, 20, '...') }}
                                    </td>
                                    <td>
                                        {{-- <a href="{{ route('admin.products.stock-outputs.edit', ['stock_entry' => $stockOutput->id]) }}"
                                            class="btn btn-warning">
                                            Edit
                                        </a> --}}
                                        <a href="#" class="btn btn-danger"
                                            onclick="confirmDelete({{ $stockOutput->id }})">
                                            Hapus
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $stockOutputs->links('pagination::bootstrap-5') }}
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
                title: 'Hapus Stok Keluar?',
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
