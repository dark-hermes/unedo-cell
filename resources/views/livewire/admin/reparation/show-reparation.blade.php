<div>
    <a href="{{ route('admin.reparations.index') }}" class="btn btn-outline-secondary mb-3">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
    <livewire:admin.partials.page-heading title="Detail Perbaikan #{{ $reparation->id }}" subtitle=""
        :breadcrumbs="[
            ['label' => 'Daftar Perbaikan', 'href' => route('admin.reparations.index')],
            ['label' => 'Detail Perbaikan', 'href' => ''],
        ]" />

    <div class="card">
        <div class="card-content">
            <div class="card-body">
                <div class="row">
                    <!-- Informasi Perbaikan -->
                    <div class="col-md-6">
                        <div class="border rounded p-3 mb-3">
                            <h5 class="mb-3">Informasi Perbaikan</h5>
                            <div class="row">
                                <div class="col-6">
                                    <p class="mb-1"><strong>Nomor Perbaikan</strong></p>
                                    <p>{{ $reparation->id }}</p>
                                </div>
                                <div class="col-6">
                                    <p class="mb-1"><strong>Tanggal Masuk</strong></p>
                                    <p>{{ $reparation->created_at->format('d M Y H:i') }}</p>
                                </div>
                                <div class="col-6">
                                    <p class="mb-1"><strong>Status</strong></p>
                                    <p>
                                        @if ($reparation->status == 'pending')
                                            <span
                                                class="badge bg-warning text-dark">{{ $reparation->status_label }}</span>
                                        @elseif ($reparation->status == 'confirmed')
                                            <span class="badge bg-primary">{{ $reparation->status_label }}</span>
                                        @elseif ($reparation->status == 'in_progress')
                                            <span class="badge bg-info">{{ $reparation->status_label }}</span>
                                        @elseif ($reparation->status == 'completed')
                                            {{-- <span class="badge bg-success">{{ $reparation->status_label }}</span> --}}
                                            @if ($reparation->reparationTransaction->transaction_status == 'pending')
                                                <span class="badge bg-warning text-dark">Menunggu Pembayaran</span>
                                            @else
                                                <span class="badge bg-success">{{ $reparation->status_label }}</span>
                                            @endif
                                        @elseif ($reparation->status == 'cancelled')
                                            <span class="badge bg-danger">{{ $reparation->status_label }}</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-6">
                                    <p class="mb-1"><strong>Biaya Perbaikan</strong></p>
                                    <p class="fw-bold">
                                        @if ($reparation->price)
                                            Rp{{ number_format($reparation->price, 0, ',', '.') }}
                                        @else
                                            <span class="text-muted">Belum ditentukan</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Barang -->
                        <div class="border rounded p-3 mb-3">
                            <h5 class="mb-3">Informasi Barang</h5>
                            <div class="row">
                                <div class="col-6">
                                    <p class="mb-1"><strong>Nama Barang</strong></p>
                                    <p>{{ $reparation->item_name }}</p>
                                </div>
                                <div class="col-6">
                                    <p class="mb-1"><strong>Tipe Barang</strong></p>
                                    <p>{{ ucfirst($reparation->item_type) }}</p>
                                </div>
                                <div class="col-6">
                                    <p class="mb-1"><strong>Merek</strong></p>
                                    <p>{{ $reparation->item_brand ?? '-' }}</p>
                                </div>
                                <div class="col-12">
                                    <p class="mb-1"><strong>Deskripsi Kerusakan</strong></p>
                                    <p>{{ $reparation->description ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Pelanggan -->
                    <div class="col-md-6">
                        <div class="border rounded p-3 mb-3">
                            <h5 class="mb-3">Informasi Pelanggan</h5>
                            <div class="row">
                                <div class="col-12">
                                    <p class="mb-1"><strong>Nama Pelanggan</strong></p>
                                    <p>{{ $reparation->user->name }}</p>
                                </div>
                                <div class="col-6">
                                    <p class="mb-1"><strong>Email</strong></p>
                                    <p>
                                        <a
                                            href="mailto:{{ $reparation->user->email }}">{{ $reparation->user->email }}</a>
                                    </p>
                                </div>
                                <div class="col-6">
                                    <p class="mb-1"><strong>Telepon</strong></p>
                                    <p>
                                        <a href="https://wa.me/{{ $reparation->user->phone }}" target="_blank">
                                            {{ $reparation->user->phone }}
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Aksi Perbaikan -->
                        <div class="border rounded p-3">
                            <h5 class="mb-3">Aksi Perbaikan</h5>
                            <div class="d-flex flex-wrap gap-2">
                                @if ($reparation->status == 'pending')
                                    <button wire:click.prevent="confirmReparation" wire:loading.attr="disabled"
                                        wire:target="confirmReparation"
                                        @if ($isProcessing) disabled @endif class="btn btn-primary">
                                        <span wire:loading.remove wire:target="confirmReparation">
                                            <i class="bi bi-check-circle"></i> Konfirmasi
                                        </span>
                                        <span wire:loading wire:target="confirmReparation">
                                            <span class="spinner-border spinner-border-sm" role="status"></span>
                                            Memproses...
                                        </span>
                                    </button>

                                    <button wire:click.prevent="cancelReparation" wire:loading.attr="disabled"
                                        wire:target="cancelReparation" class="btn btn-danger">
                                        <span wire:loading.remove wire:target="cancelReparation">
                                            <i class="bi bi-x-circle"></i> Batalkan
                                        </span>
                                        <span wire:loading wire:target="cancelReparation">
                                            <span class="spinner-border spinner-border-sm" role="status"></span>
                                            Memproses...
                                        </span>
                                    </button>
                                @elseif($reparation->status == 'confirmed')
                                    <button wire:click.prevent="startReparation" class="btn btn-info">
                                        <i class="bi bi-tools"></i> Mulai Perbaikan
                                    </button>
                                    <button wire:click.prevent="cancelReparation" class="btn btn-danger">
                                        <i class="bi bi-x-circle"></i> Batalkan
                                    </button>
                                @elseif($reparation->status == 'in_progress')
                                    <button wire:click.prevent="completeReparation" class="btn btn-success">
                                        <i class="bi bi-check-circle"></i> Selesaikan
                                    </button>
                                @elseif($reparation->status == 'completed')
                                    <button class="btn btn-success" disabled>
                                        <i class="bi bi-check-circle"></i> Selesai
                                    </button>
                                @elseif($reparation->status == 'cancelled')
                                    <button class="btn btn-secondary" disabled>
                                        <i class="bi bi-x-circle"></i> Dibatalkan
                                    </button>
                                @endif
                            </div>

                            <!-- Form Update Harga -->
                            @if (in_array($reparation->status, ['in_progress']))
                                <div class="border rounded p-3 mt-3">
                                    <h5 class="mb-3">Perbarui Biaya Perbaikan</h5>
                                    <form>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="price">Biaya Perbaikan</label>
                                                    <input type="number" class="form-control" id="price"
                                                        wire:model="price" placeholder="Masukkan biaya perbaikan"
                                                        min="0">
                                                </div>
                                            </div>
                                            <div class="col-md-8 d-flex align-items-end gap-2">
                                                <button type="submit" class="btn btn-primary"
                                                    wire:click.prevent="updatePrice" wire:loading.attr="disabled">
                                                    <span wire:loading.remove wire:target="updatePrice">
                                                        <i class="bi bi-save"></i> Simpan
                                                    </span>
                                                    <span wire:loading wire:target="updatePrice">
                                                        <span class="spinner-border spinner-border-sm"
                                                            role="status"></span>
                                                        Memproses...
                                                    </span>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Lampiran -->
                @if ($reparation->fileable->count() > 0)
                    <div class="border rounded p-3 mt-3">
                        <h5 class="mb-3">Lampiran</h5>
                        <div class="row">
                            @foreach ($reparation->fileable as $file)
                                <div class="col-md-3 mb-3">
                                    {{-- <div class="card">
                                        @if (in_array($file->file_type, ['image/jpeg', 'image/png', 'image/gif']))
                                            <img src="{{ $file->file_url }}" class="card-img-top" alt="Lampiran" style="height: 150px; object-fit: cover;">
                                        @else
                                            <div class="card-body text-center py-4">
                                                <i class="bi bi-file-earmark-text display-4 text-muted"></i>
                                                <p class="mt-2 mb-0">{{ $file->file_type }}</p>
                                            </div>
                                        @endif
                                        <div class="card-footer bg-white">
                                            <a href="{{ $file->file_url }}" target="_blank" class="btn btn-sm btn-outline-primary w-100">
                                                <i class="bi bi-download"></i> Unduh
                                            </a>
                                        </div>
                                    </div> --}}

                                    {{-- Show image/video instead of downloading --}}
                                    <div class="position-relative">
                                        @if (in_array($file->file_type, ['image/jpeg', 'image/png', 'image/gif']))
                                            <img src="{{ $file->file_url }}" class="img-fluid" alt="Lampiran"
                                                style="height: 150px; object-fit: cover;">
                                        @elseif(in_array($file->file_type, ['video/mp4', 'video/avi', 'video/mov']))
                                            <video controls class="img-fluid"
                                                style="height: 150px; object-fit: cover;">
                                                <source src="{{ $file->file_url }}" type="{{ $file->file_type }}">
                                                Your browser does not support the video tag.
                                            </video>
                                        @endif
                                        <a href="{{ $file->file_url }}" target="_blank"
                                            class="btn btn-sm btn-outline-primary position-absolute bottom-0 end-0 m-2">
                                            <i class="bi bi-download"></i> Unduh
                                        </a>

                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('styles')
    <style>
        .spinner {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            100% {
                transform: rotate(360deg);
            }
        }
    </style>
@endpush
