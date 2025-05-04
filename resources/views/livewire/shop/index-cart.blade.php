<div>
    <form class="bg0 p-t-75 p-b-85">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 col-xl-7 m-lr-auto m-b-50">
                    <div class="m-l-25 m-r--38 m-lr-0-xl">
                        <div class="wrap-table-shopping-cart">
                            <table class="table-shopping-cart">
                                <tr class="table_head">
                                    <th class="column-1">Barang</th>
                                    <th class="column-2"></th>
                                    <th class="column-3">Harga</th>
                                    <th class="column-4">Jumlah</th>
                                    <th class="column-5">Subtotal</th>
                                    <th class="column-6"></th>
                                </tr>

                                @forelse ($cartItems as $item)
                                    <tr class="table_row">
                                        <td class="column-1">
                                            <div class="how-itemcart1">
                                                <img src="{{ $item->product->image_url }}"
                                                    alt="{{ $item->product->name }}">
                                                <a href="{{ route('shop.show', ['slug' => $item->product->slug]) }}"
                                                    class="stext-102 cl2">
                                                    {{ Str::limit($item->product->name, 16) }}
                                                </a>
                                            </div>
                                        </td>
                                        <td class="column-2"></td>
                                        <td class="column-3">
                                            @if ($item->product->discount > 0)
                                                <span class="stext-110 cl2">
                                                    Rp{{ number_format($item->product->price_after_discount, 0, ',', '.') }}
                                                </span>
                                                <span class="stext-110 cl9 text-decoration-line-through">
                                                    Rp{{ number_format($item->product->sale_price, 0, ',', '.') }}
                                                </span>
                                            @else
                                                <span class="stext-110 cl2">
                                                    Rp{{ number_format($item->product->price_after_discount, 0, ',', '.') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="column-4">
                                            <div class="wrap-num-product flex-w m-l-auto m-r-0">
                                                <!-- Tombol Minus -->
                                                <div class="btn-num-product-down cl8 hov-btn3 trans-04 flex-c-m"
                                                    wire:click="decrement({{ $item->id }})"
                                                    wire:loading.attr="disabled"
                                                    @if ($quantities[$item->id] <= 1) disabled @endif>
                                                    <span wire:loading.remove
                                                        wire:target="decrement({{ $item->id }})">
                                                        <i class="fs-16 zmdi zmdi-minus"></i>
                                                    </span>
                                                    <span wire:loading wire:target="decrement({{ $item->id }})">
                                                        <i class="fa fa-spinner fa-spin"></i>
                                                    </span>
                                                </div>

                                                <!-- Input Quantity -->
                                                <input class="mtext-104 cl3 txt-center num-product" type="number"
                                                    wire:model.lazy="quantities.{{ $item->id }}"
                                                    wire:change="updateQuantity({{ $item->id }}, $event.target.value)"
                                                    wire:loading.attr="disabled" min="1"
                                                    max="{{ $item->product->stock }}">

                                                <!-- Tombol Plus -->
                                                <div class="btn-num-product-up cl8 hov-btn3 trans-04 flex-c-m"
                                                    wire:click="increment({{ $item->id }})"
                                                    wire:loading.attr="disabled"
                                                    @if ($quantities[$item->id] >= $item->product->stock) disabled @endif>
                                                    <span wire:loading.remove
                                                        wire:target="increment({{ $item->id }})">
                                                        <i class="fs-16 zmdi zmdi-plus"></i>
                                                    </span>
                                                    <span wire:loading wire:target="increment({{ $item->id }})">
                                                        <i class="fa fa-spinner fa-spin"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <span class="stext-105 cl3">
                                                Stok: {{ $item->product->stock }}
                                            </span>
                                        </td>
                                        <td class="column-5">
                                            <span class="stext-110 cl2">
                                                Rp{{ number_format($item->product->price_after_discount * $quantities[$item->id], 0, ',', '.') }}
                                            </span>
                                        </td>
                                        <td class="column-6">
                                            <button type="button"
                                                class="cl8 hov-btn3 trans-04 flex-c-m btn-remove-product"
                                                wire:click="removeFromCart({{ $item->id }})"
                                                wire:loading.attr="disabled"
                                                style="width: 45px; height: 45px; border: 1px solid #e6e6e6; border-radius: 3px;">
                                                <span wire:loading.remove
                                                    wire:target="removeFromCart({{ $item->id }})">
                                                    <i class="fs-16 zmdi zmdi-close"></i>
                                                </span>
                                                <span wire:loading wire:target="removeFromCart({{ $item->id }})">
                                                    <i class="fa fa-spinner fa-spin"></i>
                                                </span>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center p-t-30 p-b-30">
                                            <span class="stext-110 cl2">
                                                Keranjang belanja Anda kosong
                                            </span>
                                        </td>
                                    </tr>
                                @endforelse
                            </table>
                        </div>
                    </div>
                </div>

                @if ($cartItems->isNotEmpty())
                    <div class="col-sm-10 col-lg-7 col-xl-5 m-lr-auto m-b-50">
                        <div class="bor10 p-lr-40 p-t-30 p-b-40 m-l-63 m-r-40 m-lr-0-xl p-lr-15-sm">
                            <h4 class="mtext-109 cl2 p-b-30">
                                Cart Totals
                            </h4>

                            <div class="flex-w flex-t bor12 p-b-13">
                                <div class="size-208">
                                    <span class="stext-110 cl2">
                                        Total:
                                    </span>
                                </div>

                                <div class="size-209">
                                    <span class="mtext-110 cl2">
                                        Rp{{ number_format($totalPrice, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>

                            <div class="flex-w flex-t bor12 p-t-15 p-b-30">
                                <div class="size-208 w-full-ssm m-t-9">
                                    <span class="stext-110 cl2">
                                        Pengiriman:
                                    </span>
                                </div>

                                <div class="size-209 p-r-18 p-r-0-sm w-full-ssm">
                                    <div class="p-t-0">
                                        <!-- Shipping Method - Ganti dengan dropdown Bootstrap -->
                                        <div class="form-group m-b-12">
                                            <select class="form-control" wire:model.live="shippingMethod">
                                                <option value="self_pickup">Ambil di Unedo Cell</option>
                                                <option value="unedo">Kurir Unedo</option>
                                                <option value="courir">Kurir Ekspedisi</option>
                                            </select>
                                        </div>

                                        <!-- Address Select - Hanya tampil jika bukan self_pickup -->
                                        @if ($shippingMethod !== 'self_pickup')
                                            <div class="form-group m-b-12 m-t-12">
                                                <select class="form-control" wire:model.live="selectedAddress" required>
                                                    <option value="">Pilih Alamat Pengiriman</option>
                                                    @foreach ($addresses as $address)
                                                        <option value="{{ $address->id }}"
                                                            {{ $address->id == $selectedAddress ? 'selected' : '' }}>
                                                            {{ $address->name }} - {{ $address->address }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="flex-w flex-t p-t-27 p-b-33">
                                <div class="size-208">
                                    <span class="mtext-101 cl2">
                                        Total:
                                    </span>
                                </div>

                                <div class="size-209 p-t-1">
                                    <span class="mtext-110 cl2">
                                        Rp{{ number_format($totalPrice, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>

                            <button type="button"
                                class="flex-c-m stext-101 cl0 size-116 bg3 bor14 hov-btn3 p-lr-15 trans-04 pointer"
                                wire:click="checkout" @if ($shippingMethod !== 'self_pickup' && empty($selectedAddress)) disabled @endif>
                                Bayar
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </form>

    @push('scripts')
        <script>
            document.addEventListener('livewire:init', () => {
                Livewire.on('initSelect2', () => {
                    $('.js-select2').select2({
                        minimumResultsForSearch: -1
                    });
                });

                $('select[name="shipping"]').on('change', function() {
                    Livewire.dispatch('shippingMethodChanged');
                });

                // Handle address select
                $('select[name="address"]').on('change', function() {
                    let selectedValue = $(this).val();
                    @this.set('selectedAddress', selectedValue);
                });
            });

            // Initialize Select2 on page load
            $(document).ready(function() {
                $('.js-select2').select2({
                    minimumResultsForSearch: -1
                });
            });
        </script>
    @endpush
</div>
