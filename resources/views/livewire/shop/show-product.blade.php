<div>
    <div class="container">
        <div class="bread-crumb flex-w p-l-25 p-r-15 p-t-30 p-lr-0-lg">
            <a href="{{ route('shop.index') }}" class="stext-109 cl8 hov-cl1 trans-04">
                Belanja
                <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
            </a>

            <span class="stext-109 cl4">
                Detail Produk
            </span>
        </div>
    </div>

    <!-- Product Detail -->
    <section class="sec-product-detail bg0 p-t-65 p-b-60">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-lg-7 p-b-30">
                    <div class="p-l-25 p-r-30 p-lr-0-lg">
                        <div class="wrap-slick3 flex-sb flex-w">
                            <div class="wrap-slick3-dots"></div>
                            <div class="wrap-slick3-arrows flex-sb-m flex-w"></div>

                            <div class="slick3 gallery-lb">
                                {{-- @foreach ($product->images as $image) --}}
                                <div class="item-slick3" data-thumb="{{ $product->image_url }}">
                                    <div class="wrap-pic-w pos-relative">
                                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                            class="img-fluid">

                                        <a class="flex-c-m size-108 how-pos1 bor0 fs-16 cl10 bg0 hov-btn3 trans-04"
                                            href="{{ $product->image_url }}" data-lightbox="gallery">
                                            <i class="fa fa-expand"></i>
                                        </a>
                                    </div>
                                </div>
                                {{-- @endforeach --}}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-5 p-b-30">
                    <div class="p-r-50 p-t-5 p-lr-0-lg">
                        <h4 class="mtext-105 cl2 js-name-detail p-b-14">
                            {{ $product->name }}
                        </h4>

                        <span class="mtext-106 cl2">
                            {{-- Rp {{ number_format($product->price, 0, ',', '.') }} --}}
                            @if ($product->discount > 0)
                                <span class="text-danger">
                                    Rp {{ number_format($product->price_after_discount, 0, ',', '.') }}
                                </span>
                                <span class="text-muted" style="text-decoration: line-through;">
                                    Rp {{ number_format($product->sale_price, 0, ',', '.') }}
                                </span>
                            @else
                                Rp {{ number_format($product->sale_price, 0, ',', '.') }}
                            @endif
                        </span>

                        <p class="stext-102 cl3 p-t-23">
                            {{ $product->description }}
                        </p>

                        <p class="stext-102 cl3 p-t-23">
                            Stok: {{ $product->stock }}
                        </p>

                        <!-- Product Options -->
                        <div class="p-t-33">
                            {{-- @if ($product->variants->isNotEmpty())
                            <div class="flex-w p-b-10" style="align-items: center;">
                                <div class="stext-110" style="min-width: 100px;">
                                    Varian
                                </div>

                                <div style="flex: 1;">
                                    <div class="rs1-select2 bor8 bg0">
                                        <select class="js-select2" name="variant" wire:model="selectedVariant">
                                            <option value="">Pilih varian</option>
                                            @foreach ($product->variants as $variant)
                                            <option value="{{ $variant->id }}">{{ $variant->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="dropDownSelect2"></div>
                                    </div>
                                </div>
                            </div>
                            @endif --}}

                            <div class="flex-w flex-r-m p-b-10">
                                <div class="size-204 flex-w flex-m respon6-next">
                                    <div class="wrap-num-product flex-w m-r-20 m-tb-10">
                                        <div class="btn-num-product-down cl8 hov-btn3 trans-04 flex-c-m"
                                            wire:click="decrement">
                                            <i class="fs-16 zmdi zmdi-minus"></i>
                                        </div>

                                        <input class="mtext-104 cl3 txt-center num-product" type="number"
                                            name="num-product" value="{{ $quantity }}" min="1"
                                            max="{{ $product->stock }}" wire:model="quantity">

                                        <div class="btn-num-product-up cl8 hov-btn3 trans-04 flex-c-m"
                                            wire:click="increment">
                                            <i class="fs-16 zmdi zmdi-plus"></i>
                                        </div>
                                    </div>

                                    @auth
                                    <button class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04"
                                        wire:click="addToCart" @if ($product->stock < 1) disabled @endif>
                                        @if ($product->stock < 1)
                                            Stok Habis
                                        @else
                                            Tambah ke Keranjang
                                        @endif
                                    </button>
                                    @endauth
                                    @guest
                                    <a href="{{ route('login') }}"
                                        class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04">
                                        Silakan login untuk membeli
                                    </a>
                                    @endguest
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bor10 m-t-50 p-t-43 p-b-40">
                <!-- Tab01 -->
                <div class="tab01">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item p-b-10">
                            <a class="nav-link active" data-toggle="tab" href="#information" role="tab">Informasi
                                Tambahan</a>
                        </li>

                        {{-- <li class="nav-item p-b-10">
                            <a class="nav-link" data-toggle="tab" href="#reviews" role="tab">Ulasan ({{ $product->reviews->count() }})</a>
                        </li> --}}
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content p-t-43">
                        <!-- Additional Information -->
                        <div class="tab-pane fade show active" id="information" role="tabpanel">
                            <div class="row">
                                <div class="col-sm-10 col-md-8 col-lg-6 m-lr-auto">
                                    <ul class="p-lr-28 p-lr-15-sm">
                                        <li class="flex-w flex-t p-b-7">
                                            <span class="stext-102 cl3 size-205">
                                                Berat
                                            </span>

                                            <span class="stext-102 cl6 size-206">
                                                {{ $product->weight }} gram
                                            </span>
                                        </li>

                                        <li class="flex-w flex-t p-b-7">
                                            <span class="stext-102 cl3 size-205">
                                                Dimensi
                                            </span>

                                            <span class="stext-102 cl6 size-206">
                                                {{ $product->dimensions }}
                                            </span>
                                        </li>

                                        <li class="flex-w flex-t p-b-7">
                                            <span class="stext-102 cl3 size-205">
                                                Bahan
                                            </span>

                                            <span class="stext-102 cl6 size-206">
                                                {{ $product->materials ?? '-' }}
                                            </span>
                                        </li>

                                        @if ($product->colors)
                                            <li class="flex-w flex-t p-b-7">
                                                <span class="stext-102 cl3 size-205">
                                                    Warna
                                                </span>

                                                <span class="stext-102 cl6 size-206">
                                                    {{ $product->colors }}
                                                </span>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Reviews -->
                        {{-- <div class="tab-pane fade" id="reviews" role="tabpanel">
                            <div class="row">
                                <div class="col-sm-10 col-md-8 col-lg-6 m-lr-auto">
                                    <div class="p-b-30 m-lr-15-sm">
                                        @foreach ($product->reviews as $review)
                                        <div class="flex-w flex-t p-b-68">
                                            <div class="wrap-pic-s size-109 bor0 of-hidden m-r-18 m-t-6">
                                                <img src="{{ $review->user->avatar_url ?? asset('images/avatar-default.png') }}" alt="AVATAR">
                                            </div>

                                            <div class="size-207">
                                                <div class="flex-w flex-sb-m p-b-17">
                                                    <span class="mtext-107 cl2 p-r-20">
                                                        {{ $review->user->name }}
                                                    </span>

                                                    <span class="fs-18 cl11">
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            @if ($i <= $review->rating)
                                                            <i class="zmdi zmdi-star"></i>
                                                            @elseif($i == ceil($review->rating) && ($review->rating - floor($review->rating) > 0)
                                                            <i class="zmdi zmdi-star-half"></i>
                                                            @else
                                                            <i class="zmdi zmdi-star-outline"></i>
                                                            @endif
                                                        @endfor
                                                    </span>
                                                </div>

                                                <p class="stext-102 cl6">
                                                    {{ $review->comment }}
                                                </p>
                                            </div>
                                        </div>
                                        @endforeach

                                        @auth
                                        <!-- Add review form -->
                                        <form class="w-full" wire:submit.prevent="submitReview">
                                            <h5 class="mtext-108 cl2 p-b-7">
                                                Tambah Ulasan
                                            </h5>

                                            <div class="flex-w flex-m p-t-50 p-b-23">
                                                <span class="stext-102 cl3 m-r-16">
                                                    Rating Anda
                                                </span>

                                                <span class="wrap-rating fs-18 cl11 pointer">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <i class="item-rating pointer zmdi zmdi-star-outline"
                                                           wire:click="$set('rating', {{ $i }})"
                                                           style="{{ $i <= $rating ? 'color: #ffc107;' : '' }}"></i>
                                                    @endfor
                                                    <input class="dis-none" type="number" name="rating" wire:model="rating">
                                                </span>
                                            </div>

                                            <div class="row p-b-25">
                                                <div class="col-12 p-b-5">
                                                    <label class="stext-102 cl3" for="review">Ulasan Anda</label>
                                                    <textarea class="size-110 bor8 stext-102 cl2 p-lr-20 p-tb-10" 
                                                              id="review" 
                                                              name="review"
                                                              wire:model="comment"></textarea>
                                                    @error('comment') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                            </div>

                                            <button type="submit"
                                                class="flex-c-m stext-101 cl0 size-112 bg7 bor11 hov-btn3 p-lr-15 trans-04 m-b-10">
                                                Kirim Ulasan
                                            </button>
                                        </form>
                                        @else
                                        <div class="alert alert-info">
                                            Silakan <a href="{{ route('login') }}">login</a> untuk menambahkan ulasan.
                                        </div>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
