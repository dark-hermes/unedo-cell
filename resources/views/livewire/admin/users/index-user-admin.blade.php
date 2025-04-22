<div>
    <livewire:admin.partials.page-heading title="Daftar Akun Admin" subtitle="" :breadcrumbs="[['label' => 'Admins', 'href' => '/admin/admins']]" />

    <div class="row">
        <div class="col-12 col-md-6">
            <a href="{{ route('admin.admins.create') }}" class="btn btn-primary"><i class="bi bi-plus"></i>
                Tambah Akun</a>
        </div>
        <div class="col-12 col-md-6 d-flex justify-content-md-end align-items-center">
            <div class="input-group w-75">
                <input type="text" class="form-control" wire:model.live.debounce.500ms="search"
                    placeholder="Cari pengguna..." name="search" />
                <button class="btn btn-primary" type="button">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-content">
            <div class="card-body">
                <!-- Table with outer spacing -->
                <div class="table-responsive" wire:loading.remove>
                    <table class="table table-lg table-hover">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>No. HP</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr wire:key="user-{{ $user->id }}">
                                    <td>
                                        <div class="avatar avatar-lg">
                                            <img src="{{ $user->image_url }}" alt="{{ $user->name }}" />
                                        </div>
                                    </td>
                                    <td>{{ $user->name }}</td>
                                    <td><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
                                    <td>{{ $user->phone }}</td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox"
                                                wire:change="toggleStatus({{ $user->id }})"
                                                @checked($user->is_active) role="switch"
                                                id="switch-{{ $user->id }}">
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.users.edit', ['user' => $user->id]) }}"
                                            class="btn btn-warning">
                                            Edit
                                        </a>
                                        <a href="#" class="btn btn-danger"
                                            onclick="confirmDelete({{ $user->id }})">
                                            Hapus
                                        </a>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $users->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        function confirmDelete(userId) {
            Swal.fire({
                title: 'Hapus Pengguna',
                text: 'Apakah Anda yakin ingin menghapus pengguna ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('deleteUser', userId);
                }
            });
        }
    </script>
@endpush
