<div>
    <!-- Product -->
    <div class="bg0 p-b-140">
        <div class="container">
            <div class="flex-w flex-sb-m p-b-52">
                <div class="flex-w flex-l-m filter-tope-group m-tb-10">
                    <button 
                        class="stext-106 cl6 hov1 bor3 trans-04 m-r-32 m-tb-5 how-active1">
                        Wishlist Saya
                    </button>
                </div>

                <div class="flex-w flex-c-m m-tb-10">
                    <div
                        class="flex-c-m stext-106 cl6 size-105 bor4 pointer hov-btn3 trans-04 m-r-8 m-tb-4 js-show-search">
                        <i class="icon-search cl2 m-r-6 fs-15 trans-04 zmdi zmdi-search"></i>
                        <i class="icon-close-search cl2 m-r-6 fs-15 trans-04 zmdi zmdi-close dis-none"></i>
                        Search
                    </div>
                </div>

                <!-- Search product -->
                <div class="dis-none panel-search w-full p-t-10 p-b-15">
                    <div class="bor8 dis-flex p-l-15">
                        <button class="size-113 flex-c-m fs-16 cl2 hov-cl1 trans-04">
                            <i class="zmdi zmdi-search"></i>
                        </button>

                        <input wire:model.live.debounce.300ms="search" class="mtext-107 cl2 size-114 plh2 p-r-15"
                            type="text" name="search-product" placeholder="Search">
                    </div>
                </div>
            </div>

            <div class="row isotope-grid">
                @foreach ($products as $product)
                    <div class="col-sm-6 col-md-4 col-lg-3 p-b-35 isotope-item {{ $product->category->slug ?? '' }}" wire:key="product-{{ $product->id }}">
                        <!-- Block2 -->
                        <div class="block2">
                            <div class="block2-pic hov-img0">
                                <a href="{{ route('shop.show', $product->slug) }}" style="cursor: pointer;">
                                    <img src="{{ $product->image_url ?? 'images/default-product.png' }}"
                                        alt="{{ $product->name }}">
                                </a>
                            </div>

                            <div class="block2-txt flex-w flex-t p-t-14">
                                <div class="block2-txt-child1 flex-col-l">
                                    <a href="{{ route('shop.index', $product->slug) }}"
                                        class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6">
                                        {{ $product->category->name ?? '-' }}
                                    </a>

                                    <span class="stext-105 cl3">
                                        {{ $product->name }}
                                    </span>

                                    <span class="stext-105 cl3">
                                        @if ($product->discount > 0)
                                            <span
                                                class="text-decoration-line-through">Rp{{ number_format($product->sale_price, 0, ',', '.') }}</span>
                                            <span
                                                class="text-danger">Rp{{ number_format($product->price_after_discount, 0, ',', '.') }}</span>
                                        @else
                                            Rp{{ number_format($product->sale_price, 0, ',', '.') }}
                                        @endif
                                    </span>
                                </div>

                                <div class="block2-txt-child2 flex-r p-t-3">
                                    @auth
                                        <button wire:click="toggleWishlist({{ $product->id }})"
                                            class="btn-addwish-b2 dis-block pos-relative js-addwish-b2">
                                            @if ($product->isInWishlist())
                                                <img class="icon-heart2 dis-block trans-04"
                                                    src="{{ asset('images/icons/heart02.png') }}" alt="ICON">
                                            @else
                                                <img class="icon-heart dis-block trans-04"
                                                    src="{{ asset('images/icons/heart01.png') }}" alt="ICON">
                                            @endif
                                        </button>
                                    @endauth
                                    @guest
                                        <a href="{{ route('login') }}"
                                            class="btn-addwish-b2 dis-block pos-relative js-addwish-b2">
                                            <img class="icon-heart1 dis-block trans-04"
                                                src="{{ asset('images/icons/heart01.png') }}" alt="ICON">
                                        </a>
                                    @endguest
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="flex-c-m flex-w w-full p-t-45">
                {{ $products->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

</div>

@push('scripts')
    <script>
        // Initialize isotope filtering
        document.addEventListener('livewire:init', () => {
            Livewire.on('updateIsotope', () => {
                $('.isotope-grid').isotope({
                    itemSelector: '.isotope-item',
                    layoutMode: 'fitRows'
                });
            });
        });
    </script>
@endpush
