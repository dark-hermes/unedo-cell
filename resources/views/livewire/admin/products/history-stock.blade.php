<div>
    <livewire:admin.partials.page-heading title="Riwayat Stok Keluar-Masuk" subtitle="" :breadcrumbs="[['label' => 'Produk', 'href' => '/admins/products'], ['label' => 'Stok Keluar-Masuk']]" />

    <div class="row">
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
                                <th>Tanggal</th>
                                <th>Tanggal Input</th>
                                <th>Terakhir Edit</th>
                                <th>Oleh</th>
                                <th>Alasan</th>
                                <th>Jumlah</th>
                                <th>Catatan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($histories as $history)
                                <tr wire:key="stock-entry-{{ $history->id }}">
                                    <td>
                                        {{ $history->received_at?->format('d-m-Y') ?? $history->output_date?->format('d-m-Y') }}
                                    </td>
                                    <td>
                                        {{ $history->created_at->format('d-m-Y H:i') }}
                                    </td>
                                    <td>
                                        {{ $history->updated_at->format('d-m-Y H:i') }}
                                    </td>
                                    <td>
                                        {{ $history->user->name ?? '-' }}
                                    </td>
                                    <td>
                                        {{ $history->source_label ?? $history->reason_label }}
                                    </td>
                                    <td>
                                        {{-- <span class="text-success">
                                            +{{ $history->quantity }}
                                        </span> --}}

                                        @if ($history->received_at)
                                            <span class="text-success">
                                                +{{ $history->quantity }}
                                            </span>
                                        @else
                                            <span class="text-danger">
                                                -{{ $history->quantity }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ Str::limit($history->note, 20, '...') }}
                                    </td>
                                    <td>
                                        {{-- <a href="{{ route('admin.products.stock-entries.edit', ['stock_entry' => $history->id]) }}"
                                            class="btn btn-warning">
                                            Edit
                                        </a> --}}
                                        <a href="#" class="btn btn-danger"
                                            onclick="confirmDelete({{ $history->id }})">
                                            Hapus
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{-- <div class="mt-4">
                        {{ $histories->links('pagination::bootstrap-5') }}
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        function confirmDelete(stockEntryId) {
            Swal.fire({
                title: 'Hapus Stok Keluar-Masuk?',
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
