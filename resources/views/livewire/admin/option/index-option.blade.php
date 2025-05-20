<div>
    <livewire:admin.partials.page-heading title="Pengaturan Umum" subtitle="" :breadcrumbs="[['label' => 'Pengaturan Umum', 'href' => '/admins/options']]" />

    <div class="row mb-3">
        <div class="col-12 col-md-6">
        </div>
        <div class="col-12 col-md-6 d-flex justify-content-md-end align-items-center">
            <div class="input-group w-75">
                <input type="text" class="form-control" wire:model.live.debounce.500ms="search"
                    placeholder="Cari produk..." name="search" />
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
                                <th>Key</th>
                                <th>Value</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($options as $option)
                                <tr wire:key="option-{{ $option->id }}">
                                    <td>{{ $option->key }}</td>
                                    <td>
                                        @if ($option->type == 'text')
                                            {{ Str::limit($option->value, 50) }}
                                        @elseif ($option->type == 'image')
                                            <img src="{{ $option->image_url }}" alt="{{ $option->key }}"
                                                class="img-thumbnail"
                                                style="width: 64px; height: 64px; object-fit: cover;" />
                                        @elseif ($option->type == 'file')
                                            <a href="{{ asset($option->value) }}" target="_blank"
                                                class="btn btn-primary btn-sm">Lihat File</a>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-secondary dropdown-toggle" type="button"
                                                id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots"></i>
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton"
                                                role="menu">
                                                <li><a class="dropdown-item"
                                                        href="{{ route('admin.options.edit', ['option' => $option->id]) }}">Edit</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-3">
                        {{ $options->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        function confirmDelete(productId) {
            Swal.fire({
                title: 'Hapus Produk',
                text: 'Apakah Anda yakin ingin menghapus produk ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('deleteProduct', productId);
                }
            });
        }
    </script>
@endpush
