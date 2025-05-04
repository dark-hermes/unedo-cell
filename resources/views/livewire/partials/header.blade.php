<header class="header-v4">
    <!-- Header desktop -->
    <div class="container-menu-desktop">
        <div class="wrap-menu-desktop how-shadow1">
            <nav class="limiter-menu-desktop container">
                <!-- Logo desktop -->        
                <a href="/" class="logo">
                    <img src="{{ asset('images/png2.png') }}" alt="IMG-LOGO">
                </a>

                <!-- Menu desktop -->
                <div class="menu-desktop">
                    <ul class="main-menu">
                        <li class="{{ request()->is('/') ? 'active-menu' : '' }}">
                            <a href="/">Beranda</a>
                        </li>

                        <li class="{{ request()->is('shop') ? 'active-menu' : '' }}">
                            <a href="{{ route('shop.index') }}">Belanja</a>
                        </li>

                        <li class="{{ request()->is('reparation') ? 'active-menu' : '' }}">
                            <a href="{{ route('home') }}">Servis</a>
                        </li>

                        <li class="{{ request()->is('contact') ? 'active-menu' : '' }}">
                            <a href="{{ route('home') }}">Kontak</a>
                        </li>
                    </ul>
                </div>    

                <!-- Icon header -->
                <div class="wrap-icon-header flex-w flex-r-m">
                    <div class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 js-show-modal-search">
                        <i class="zmdi zmdi-search"></i>
                    </div>
                
                    {{-- <a href="{{ route('home') }}" class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 icon-header-noti" data-notify="{{ Livewire::get('cart-count') }}"> --}}
                        <a href="{{ route('cart.index') }}" class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 icon-header-noti">
                        <i class="zmdi zmdi-shopping-cart"></i>
                    </a>
                
                    {{-- <a href="{{ route('wishlists') }}" class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 icon-header-noti" data-notify="{{ Livewire::get('wishlist-count') }}"> --}}
                        <a href="{{ route('wishlist.index') }}" class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 icon-header-noti">
                        <i class="zmdi zmdi-favorite-outline"></i>
                    </a>
                    <a href="{{ route('home') }}" class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11">
                        <i class="zmdi zmdi-account-circle"></i>
                    </a>
                </div>                    
            </nav>
        </div>    
    </div>

    <!-- Header Mobile, Menu Mobile, dan Modal Search -->
    <!-- (Salin bagian ini dari kode asli Anda) -->

    <div class="wrap-header-mobile bg-dark">
        <!-- Logo moblie -->		
        <div class="logo-mobile">
            {{-- <a href="/"><img src="images/png2.png" alt="IMG-LOGO"></a> --}}
            <a href="{{ route('home') }}"><img src="{{ asset('images/png2.png') }}" alt="IMG-LOGO"></a>
        </div>

        <!-- Icon header -->
        <div class="wrap-icon-header flex-w flex-r-m m-r-15">
            <div class="icon-header-item cl2 hov-cl1 trans-04 p-r-11 js-show-modal-search">
                <i class="zmdi zmdi-search"></i>
            </div>

            <a href="{{route('home')}}" div class="icon-header-item cl2 hov-cl1 trans-04js-show-cart">
                <i class="zmdi zmdi-shopping-cart"></i>
            </div>

            <a href="{{route('home')}}" class="dis-block icon-header-item cl2 hov-cl1 p-r-11 trans-04">
                <i class="zmdi zmdi-favorite-outline"></i>
            </a>

            <!-- Button show menu -->
            <div class="btn-show-menu-mobile hamburger hamburger--squeeze">
                <span class="hamburger-box">
                    <span class="hamburger-inner"></span>
                </span>
            </div>
        </div>
    </div>

    <!-- Menu Mobile -->
    <div class="menu-mobile">

        <ul class="main-menu-m">

            <li>
                <a href="profile">My Account</a>
            </li>

            <li>
                <a href="#">Home</a>
            </li>

            <li>
                <a href="products">Shop</a>
            </li>

            <li>
                <a href="reparation" class="label1 rs1" data-label1="hot">Reparation</a>
            </li>

            <li>
                <a href="contact">Contact</a>
            </li>
        </ul>
    </div>

    <!-- Modal Search -->
    <div class="modal-search-header flex-c-m trans-04 js-hide-modal-search">
        <div class="container-search-header">
            <button class="flex-c-m btn-hide-modal-search trans-04 js-hide-modal-search">
                <img src="{{ asset('images/icons/icon-close2.png') }}" alt="CLOSE">
            </button>

            <form class="wrap-search-header flex-w p-l-15">
                <button class="flex-c-m trans-04">
                    <i class="zmdi zmdi-search"></i>
                </button>
                <input class="plh3" type="text" name="search" placeholder="Search...">
            </form>
        </div>
    </div>
</header>