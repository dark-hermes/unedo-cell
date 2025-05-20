<div class="card">
    <div class="card-header">
        <h4>5 Permintaan Reparasi Baru</h4>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-lg">
                <thead>
                    <tr>
                        <th>Pelanggan</th>
                        <th>Item</th>
                        <th>Deskripsi</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reparations as $repair)
                    <tr>
                        <td class="col-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-md">
                                    <img src="{{ $repair->user->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($repair->user->name).'&background=random' }}" />
                                </div>
                                <p class="font-bold ms-3 mb-0">{{ $repair->user->name }}</p>
                            </div>
                        </td>
                        <td>{{ $repair->item_name }}</td>
                        <td>{{ Str::limit($repair->description, 50) }}</td>
                        <td>{{ $repair->status_label }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>