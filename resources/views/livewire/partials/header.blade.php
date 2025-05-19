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
                            <a href="{{ route('reparations.form') }}">Servis</a>
                        </li>

                        <li class="{{ request()->is('contact') ? 'active-menu' : '' }}">
                            <a href="{{ route('home') }}">Kontak</a>
                        </li>

                        @role('admin')
                            <li class="{{ request()->is('admin') ? 'active-menu' : '' }}">
                                <a href="{{ route('admin.home-contents.index') }}">Admin</a>
                            </li>
                        @endrole
                    </ul>
                </div>

                <!-- Icon header -->
                <div class="wrap-icon-header flex-w flex-r-m">
                    <div class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 js-show-modal-search">
                        <i class="zmdi zmdi-search"></i>
                    </div>

                    <a href="{{ route('cart.index') }}"
                        class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 icon-header-noti">
                        <i class="zmdi zmdi-shopping-cart"></i>
                    </a>

                    <a href="{{ route('wishlist.index') }}"
                        class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 icon-header-noti">
                        <i class="zmdi zmdi-favorite-outline"></i>
                    </a>

                    <!-- Profile Dropdown -->
                    <div class="dropdown">
                        <a href="#" class="dropdown-toggle icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11"
                            data-toggle="dropdown">
                            <i class="zmdi zmdi-account-circle"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right">
                            @auth
                                <li><a href="{{ route('profile') }}">Profil Saya</a></li>
                                <li>
                                    <a href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                         document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                    </form>
                                </li>
                            @endauth
                            @guest
                                <li><a href="{{ route('login') }}">Masuk</a></li>
                            @endguest
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </div>

    <!-- Header Mobile, Menu Mobile, dan Modal Search -->
    <div class="wrap-header-mobile bg-dark">
        <!-- Logo moblie -->
        <div class="logo-mobile">
            <a href="{{ route('home') }}"><img src="{{ asset('images/png2.png') }}" alt="IMG-LOGO"></a>
        </div>

        <!-- Icon header -->
        <div class="wrap-icon-header flex-w flex-r-m m-r-15">
            <div class="icon-header-item cl2 hov-cl1 trans-04 p-r-11 js-show-modal-search">
                <i class="zmdi zmdi-search"></i>
            </div>

            <a href="{{ route('cart.index') }}" div class="icon-header-item cl2 hov-cl1 trans-04js-show-cart">
                <i class="zmdi zmdi-shopping-cart"></i>
        </div>

        <a href="{{ route('wishlist.index') }}" class="dis-block icon-header-item cl2 hov-cl1 p-r-11 trans-04">
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
                <a href="{{ route('home') }}">Beranda</a>
            </li>

            <li>
                <a href="{{ route('shop.index') }}">Belanja</a>
            </li>

            <li>
                <a href="{{ route('reparations.form') }}" class="label1 rs1" data-label1="hot">Reparasi</a>
            </li>

            <li>
                <a href="contact">Contact</a>
            </li>

            <li>
                <a href="{{ route('profile') }}">Profil Saya</a>
            </li>

            <li>
                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                             document.getElementById('logout-form-mobile').submit();">
                    Logout
                </a>
                <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
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

    <style>
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-toggle {
            cursor: pointer;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            background-color: #fff;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 1;
            border-radius: 4px;
            padding: 0;
            margin-top: 5px;
        }

        .dropdown-menu li {
            list-style: none;
        }

        .dropdown-menu a {
            color: #333;
            padding: 10px 15px;
            text-decoration: none;
            display: block;
        }

        .dropdown-menu a:hover {
            background-color: #f5f5f5;
        }

        .dropdown:hover .dropdown-menu {
            display: block;
        }
    </style>
</header>
