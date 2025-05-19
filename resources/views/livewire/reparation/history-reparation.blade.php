<div>
    <!-- breadcrumb -->
    <div class="container">
        <div class="bread-crumb flex-w p-l-25 p-r-15 p-t-30 p-lr-0-lg">
            <a href="{{ route('reparations.form') }}" class="stext-109 cl8 hov-cl1 trans-04">
                Reparasi
                <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
            </a>

            <span class="stext-109 cl4">
                Riwayat Reparasi
            </span>
        </div>
    </div>

    <!-- Shoping Cart -->
    <div class="bg0 p-t-75 p-b-85">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 col-xl-10 m-lr-auto m-b-50">
                    <div class="m-l-25 m-r--38 m-lr-0-xl">
                        @if($reparations->isEmpty())
                            <div class="text-center p-t-50 p-b-50">
                                <i class="zmdi zmdi-info-outline cl3" style="font-size: 48px;"></i>
                                <h4 class="mtext-105 cl2 p-t-33">Belum ada riwayat reparasi</h4>
                                <p class="stext-115 cl6 p-t-10">Anda belum memiliki permintaan reparasi</p>
                                <a href="{{ route('reparations.form') }}" class="flex-c-m stext-101 cl0 size-116 bg3 bor14 hov-btn3 p-lr-15 trans-04 pointer m-t-20" style="max-width: 300px; margin: 0 auto;">
                                    Buat Permintaan Reparasi
                                </a>
                            </div>
                        @else
                            <div class="table-responsive">
                                <div class="wrap-table-shopping-cart">
                                    <table class="table-shopping-cart">
                                        <thead>
                                            <tr class="table_head">
                                                <th class="column-9">Tanggal</th>
                                                <th class="column-9">Nama Barang</th>
                                                <th class="column-9">Jenis</th>
                                                <th class="column-9">Deskripsi</th>
                                                <th class="column-9">Harga</th>
                                                <th class="column-9">Status</th>
                                                <th class="column-9">Aksi</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach($reparations as $reparation)
                                            <tr class="table_row">
                                                <td class="column-9">
                                                    {{ $reparation->created_at->format('d M Y') }}
                                                </td>
                                                <td class="column-9">
                                                    {{ $reparation->item_name }}
                                                    @if($reparation->item_brand)
                                                        <br><small class="text-muted">{{ $reparation->item_brand }}</small>
                                                    @endif
                                                </td>
                                                <td class="column-9">
                                                    {{ match($reparation->item_type) {
                                                        'phone' => 'Ponsel',
                                                        'laptop' => 'Laptop',
                                                        'tablet' => 'Tablet',
                                                        'other' => 'Lainnya',
                                                        default => $reparation->item_type
                                                    } }}
                                                </td>
                                                <td class="column-9">
                                                    {{ Str::limit($reparation->description, 50) }}
                                                </td>
                                                <td class="column-9">
                                                    @if($reparation->price)
                                                        Rp {{ number_format($reparation->price, 0, ',', '.') }}
                                                    @else
                                                        <span class="text-muted">Belum ada harga</span>
                                                    @endif
                                                </td>
                                                <td class="column-9">
                                                    <span class="status-label status-{{ str_replace('_', '-', $reparation->status) }}">
                                                        {{ $reparation->status_label }}
                                                    </span>
                                                </td>
                                                <td class="column-9">
                                                    @if($reparation->status === 'confirmed' && $reparation->price)
                                                        <a href="#" 
                                                           class="flex-c-m stext-101 cl0 size-105 bg1 bor1 hov-btn1 p-lr-15 trans-04 pointer" 
                                                           style="font-size: 0.8rem; padding: 6px 6px; line-height: 1; height: 35px; display: inline-flex; align-items: center;"
                                                           wire:click.prevent="payReparation({{ $reparation->id }})">
                                                            Bayar
                                                        </a>
                                                    @elseif(in_array($reparation->status, ['completed', 'cancelled']))
                                                        <button class="flex-c-m stext-101 cl0 size-105 bor1 p-lr-15 trans-04" 
                                                                style="background-color: #060606a8; font-size: 0.8rem; padding: 6px 6px; line-height: 1; height: 35px; display: inline-flex; align-items: center; opacity: 1; cursor: not-allowed;" 
                                                                disabled>
                                                            {{ $reparation->status === 'completed' ? 'Selesai' : 'Dibatalkan' }}
                                                        </button>
                                                    @else
                                                        {{-- <button class="flex-c-m stext-101 cl0 size-105 bor1 p-lr-15 trans-04" 
                                                                style="background-color: #060606a8; font-size: 0.8rem; padding: 6px 6px; line-height: 1; height: 35px; display: inline-flex; align-items: center; opacity: 1; cursor: not-allowed;" 
                                                                disabled>
                                                            Menunggu
                                                        </button> --}}
                                                        {{-- Detail Button action --}}
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .status-label {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            text-transform: capitalize;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-confirmed {
            background-color: #cce5ff;
            color: #004085;
        }
        .status-in-progress {
            background-color: #e2e3e5;
            color: #383d41;
        }
        .status-completed {
            background-color: #d4edda;
            color: #155724;
        }
        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
    @endpush
</div>