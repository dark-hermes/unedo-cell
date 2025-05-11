<div>
    <livewire:admin.partials.page-heading title="Daftar Perbaikan" subtitle="" :breadcrumbs="[['label' => 'Perbaikan', 'href' => '/admins/reparations']]" />

    <div class="row mb-3">
        <div class="col-12 col-md-6">
        </div>
        <div class="col-12 col-md-6 d-flex justify-content-md-end align-items-center">
            <div class="input-group w-75">
                <input type="text" class="form-control" wire:model.live.debounce.500ms="search"
                    placeholder="Cari perbaikan..." name="search" />
                <button class="btn btn-primary" type="button">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-content">
            <div class="card-body">
                <div class="table-responsive" wire:loading.remove>
                    <table class="table table-hover table-lg align-middle">
                        <thead>
                            <tr>
                                <th wire:click="sortBy('item_name')">
                                    Nama Barang
                                    @if ($sortField === 'item_name')
                                        <i class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </th>
                                <th wire:click="sortBy('item_type')">
                                    Jenis Barang
                                    @if ($sortField === 'item_type')
                                        <i class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </th>
                                <th wire:click="sortBy('item_brand')">
                                    Merek
                                    @if ($sortField === 'item_brand')
                                        <i class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </th>
                                <th>Deskripsi Kerusakan</th>
                                <th wire:click="sortBy('status')">
                                    Status
                                    @if ($sortField === 'status')
                                        <i class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </th>
                                <th wire:click="sortBy('created_at')">
                                    Tanggal Dibuat
                                    @if ($sortField === 'created_at')
                                        <i class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reparations as $reparation)
                                <tr wire:key="reparation-{{ $reparation->id }}">
                                    <td>{{ $reparation->item_name }}</td>
                                    <td>{{ $reparation->item_type }}</td>
                                    <td>{{ $reparation->item_brand }}</td>
                                    <td>{{ Str::limit($reparation->description, 50) }}</td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'pending' => 'warning',
                                                'in_progress' => 'info',
                                                'completed' => 'success',
                                                'cancelled' => 'danger',
                                            ];
                                            $color = $statusColors[$reparation->status] ?? 'secondary';
                                        @endphp
                                        <span class="badge bg-{{ $color }}">
                                            {{ ucfirst(str_replace('_', ' ', $reparation->status)) }}
                                        </span>
                                    </td>
                                    <td>{{ $reparation->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-secondary dropdown-toggle" type="button"
                                                id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots"></i>
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton"
                                                role="menu">
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.reparations.show', ['reparation' => $reparation->id]) }}">
                                                        Detail
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-3">
                        {{ $reparations->links('pagination::bootstrap-5') }}
                    </div>
                </div>

                <div wire:loading class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Memuat data...</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmDelete(reparationId) {
            Swal.fire({
                title: 'Hapus Data Perbaikan',
                text: 'Apakah Anda yakin ingin menghapus data perbaikan ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('deleteReparation', reparationId);
                }
            });
        }
    </script>
@endpush
