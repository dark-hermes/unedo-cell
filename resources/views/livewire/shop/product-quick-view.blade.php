<div>
    @if ($showModal)
        <!-- The modal structure -->
        <div class="wrap-modal1 js-modal1 p-t-60 p-b-20" style="display: block;">
            <div class="overlay-modal1" wire:click="closeModal"></div>

            <div class="container">
                <div class="bg0 p-t-60 p-b-30 p-lr-15-lg how-pos3-parent">
                    <button class="how-pos3 hov3 trans-04" wire:click="closeModal">
                        <img src="{{ asset('images/icons/icon-close.png') }}" alt="CLOSE">
                    </button>

                    <div class="row">
                        <div class="col-md-6 col-lg-7 p-b-30">
                            <div class="p-l-25 p-r-30 p-lr-0-lg">
                                <div class="wrap-slick3 flex-sb flex-w">
                                    <div class="slick3 gallery-lb">
                                        <div class="item-slick3" data-thumb="#">
                                            <div class="wrap-pic-w pos-relative">
                                                <img src="{{ asset('images/product-detail-01.jpg') }}"
                                                    alt="IMG-PRODUCT">
                                                <a class="flex-c-m size-108 how-pos1 bor0 fs-16 cl10 bg0 hov-btn3 trans-04"
                                                    href="{{ asset('images/product-detail-01.jpg') }}">
                                                    <i class="fa fa-expand"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-5 p-b-30">
                            <div class="p-r-50 p-t-5 p-lr-0-lg">
                                <h4 class="mtext-105 cl2 p-b-14">
                                    {{ $product['name'] ?? 'Product Name' }}
                                </h4>

                                <span class="mtext-106 cl2">
                                    {{ $product['price'] ?? '$0.00' }}
                                </span>

                                <p class="stext-102 cl3 p-t-23">
                                    {{ $product['description'] ?? 'Product description goes here.' }}
                                </p>

                                <p class="stext-102 cl3 p-t-23">
                                    Stock: {{ $product['stock'] ?? 'In Stock' }}
                                </p>

                                <div class="p-t-33">
                                    <div class="flex-w p-b-10" style="align-items: center;">
                                        <div class="stext-110" style="min-width: 100px;">
                                            Type
                                        </div>

                                        <div style="flex: 1;">
                                            <div class="rs1-select2 bor8 bg0">
                                                <select class="js-select2" name="time">
                                                    <option>Choose an option</option>
                                                    <option>Red</option>
                                                    <option>Blue</option>
                                                    <option>White</option>
                                                    <option>Grey</option>
                                                </select>
                                                <div class="dropDownSelect2"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex-w flex-r-m p-b-10">
                                        <div class="size-204 flex-w flex-m respon6-next">
                                            <div class="wrap-num-product flex-w m-r-20 m-tb-10">
                                                <div class="btn-num-product-down cl8 hov-btn3 trans-04 flex-c-m">
                                                    <i class="fs-16 zmdi zmdi-minus"></i>
                                                </div>

                                                <input class="mtext-104 cl3 txt-center num-product" type="number"
                                                    name="num-product" value="1">

                                                <div class="btn-num-product-up cl8 hov-btn3 trans-04 flex-c-m">
                                                    <i class="fs-16 zmdi zmdi-plus"></i>
                                                </div>
                                            </div>

                                            <button
                                                class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04">
                                                Add to cart
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
