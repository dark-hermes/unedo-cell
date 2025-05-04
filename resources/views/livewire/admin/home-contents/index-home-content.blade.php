<div>
    <livewire:admin.partials.page-heading title="Daftar Konten Beranda" subtitle="" :breadcrumbs="[['label' => 'Konten Beranda', 'href' => '/admins/home-contents']]" />

    <div class="row">
        <div class="col-12 col-md-6">
            <a href="{{ route('admin.home-contents.create') }}" class="btn btn-primary"><i class="bi bi-plus"></i>
                Tambah Konten</a>
        </div>
        <div class="col-12 col-md-6 d-flex justify-content-md-end align-items-center">
            <div class="input-group w-75">
                <input type="text" class="form-control" wire:model.live.debounce.500ms="search"
                    placeholder="Cari konten..." name="search" />
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
                                <th>Gambar</th>
                                <th>Judul</th>
                                <th>Deskripsi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($homeContents as $homeContent)
                                <tr wire:key="homeContent-{{ $homeContent->id }}">
                                    <td>
                                            <img src="{{ $homeContent->image_url }}" alt="{{ $homeContent->name }}" 
                                                class="img-fluid" style="width: 64px; height: 64px;" />
                                    </td>
                                    <td>{{ $homeContent->title }}</td>
                                    <td>{{ Str::words(strip_tags($homeContent->description), 10, '...') }}</td>
                                    <td>
                                        <a href="{{ route('admin.home-contents.edit', ['homeContent' => $homeContent->id]) }}"
                                            class="btn btn-warning">
                                            Edit
                                        </a>
                                        <a href="#" class="btn btn-danger"
                                            onclick="confirmDelete({{ $homeContent->id }})">
                                            Hapus
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $homeContents->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        function confirmDelete(homeContentId) {
            Swal.fire({
                title: 'Hapus Konten',
                text: 'Apakah Anda yakin ingin menghapus konten ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('deleteHomeContent', homeContentId);
                }
            });
        }
    </script>
@endpush
