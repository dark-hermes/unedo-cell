<footer class="bg3 p-t-75 p-b-32 m-t-8">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-lg-3 p-b-50">
                <h4 class="stext-301 cl0 p-b-30">
                    Kategori
                </h4>

                <ul>
                    <li class="p-b-10">
                        <a href="#" class="stext-107 cl7 hov-cl1 trans-04">
                            Headset
                        </a>
                    </li>

                    <li class="p-b-10">
                        <a href="#" class="stext-107 cl7 hov-cl1 trans-04">
                            Charger
                        </a>
                    </li>

                    <li class="p-b-10">
                        <a href="#" class="stext-107 cl7 hov-cl1 trans-04">
                            Case
                        </a>
                    </li>

                    <li class="p-b-10">
                        <a href="#" class="stext-107 cl7 hov-cl1 trans-04">
                            Accessories
                        </a>
                    </li>
                </ul>
            </div>

            <div class="col-sm-6 col-lg-3 p-b-50">
                <h4 class="stext-301 cl0 p-b-30">
                    Bantuan
                </h4>

                <ul>
                    <li class="p-b-10">
                        <a href="#" class="stext-107 cl7 hov-cl1 trans-04">
                            Lacak Order
                        </a>
                    </li>

                    <li class="p-b-10">
                        <a href="#" class="stext-107 cl7 hov-cl1 trans-04">
                            Pengembalian
                        </a>
                    </li>

                    <li class="p-b-10">
                        <a href="#" class="stext-107 cl7 hov-cl1 trans-04">
                            Pengiriman
                        </a>
                    </li>

                    <li class="p-b-10">
                        <a href="#" class="stext-107 cl7 hov-cl1 trans-04">
                            FAQs
                        </a>
                    </li>
                </ul>
            </div>

            <div class="col-sm-6 col-lg-3 p-b-50">
                <h4 class="stext-301 cl0 p-b-30">
                    HUBUNGI KAMI
                </h4>

                <p class="stext-107 cl7 size-201">
                    Ada pertanyaan? Anda bisa menghubungi kami lewat nomor <br> {{ $store_phone->value }} <br> atau media sosial kami di bawah ini
                </p>

                <div class="p-t-27">
                    <a class="fs-18 cl7 hov-cl1 trans-04 m-r-16" href="{{ $store_facebook->value }}">
                        <i class="fa fa-facebook"></i>
                    </a>

                    <a class="fs-18 cl7 hov-cl1 trans-04 m-r-16" href="{{ $store_instagram->value }}">
                        <i class="fa fa-instagram"></i>
                    </a>

                </div>
            </div>

            <div class="col-sm-6 col-lg-3 p-b-50">
                <h4 class="stext-301 cl0 p-b-30">
                    TEMUKAN KAMI
                </h4>

                <form>
                    <p class="stext-107 cl7 size-201">
                        {{ $store_address->value }}
                    </p>
                </form>
            </div>
        </div>

        <div class="p-t-40">
            {{-- <div class="flex-c-m flex-w p-b-18">
                <a href="#" class="m-all-1">
                    <img src="{{ asset('images/icons/icon-pay-01.png') }}" alt="ICON-PAY">
                </a>

                <a href="#" class="m-all-1">
                    <img src="{{ asset('images/icons/icon-pay-02.png') }}" alt="ICON-PAY">
                </a>

                <a href="#" class="m-all-1">
                    <img src="{{ asset('images/icons/icon-pay-03.png') }}" alt="ICON-PAY">
                </a>

                <a href="#" class="m-all-1">
                    <img src="{{ asset('images/icons/icon-pay-04.png') }}" alt="ICON-PAY">
                </a>

                <a href="#" class="m-all-1">
                    <img src="{{ asset('images/icons/icon-pay-05.png') }}" alt="ICON-PAY">
                </a>
            </div> --}}

            <p class="stext-107 cl6 txt-center">
                <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | Unedo Cell</a>
                <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
            </p>
        </div>
    </div>
</footer>