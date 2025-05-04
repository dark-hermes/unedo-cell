<div>
    <section id="hero" class="hero section dark-background">
        <!-- Konten hero section -->
    </section>

    <!-- Content page -->
    <section class="bg0 p-t-75 p-b-120">
        <div class="container">
            @foreach ($homeContents as $homeContent)
                <div class="row p-b-148">
                    @if ($loop->index % 2 == 0)
                        <div class="col-md-7 col-lg-8">
                            <div class="p-t-7 p-r-85 p-r-15-lg p-r-0-md">
                                <h3 class="mtext-111 cl2 p-b-16">
                                    {{ $homeContent->title }}
                                </h3>

                                <p class="stext-113 cl6 p-b-26">
                                <p class="stext-113 cl6 p-b-26">
                                    {{ $homeContent->description }}
                                </p>
                            </div>
                        </div>

                        <div class="col-11 col-md-5 col-lg-4 m-lr-auto">
                            <div class="how-bor1">
                                <div class="hov-img0">
                                    <img src="{{ $homeContent->image_url }}" alt="{{ $homeContent->title }}"
                                        class="img-fluid">
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="col-md-7 col-lg-8 p-b-30 order-md-2">
                            <div class="p-t-7 p-l-85 p-l-15-lg p-l-0-md">
                                <h3 class="mtext-111 cl2 p-b-16">
                                    {{ $homeContent->title }}
                                </h3>

                                <p class="stext-113 cl6 p-b-26">
                                    {{ $homeContent->description }}
                                </p>
                            </div>
                        </div>

                        <div class="col-11 col-md-5 col-lg-4 m-lr-auto order-md-1">
                            <div class="how-bor2">
                                <div class="hov-img0">
                                    <img src="{{ $homeContent->image_url }}" alt="{{ $homeContent->title }}"
                                        class="img-fluid">
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </section>
</div>
