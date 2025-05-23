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

                        <li class="{{ request()->is('reparations') ? 'active-menu' : '' }}">
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
                        class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 icon-header-noti"
                        data-notify="{{ $cartCount }}">
                        <i class="zmdi zmdi-shopping-cart"></i>
                    </a>

                    <a href="{{ route('wishlist.index') }}"
                        class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 icon-header-noti"
                        data-notify="{{ $wishlistCount }}">
                        <i class="zmdi zmdi-favorite-outline"></i>
                    </a>

                    @auth
                        <div class="dropdown notification-dropdown">
                            <a href="#"
                            class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 icon-header-noti"
                            data-notify="{{ $unreadCount }}">
                                <i class="zmdi zmdi-notifications"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-right notification-menu">
                                <li class="notification-header">
                                    <h6>Notifikasi</h6>
                                    @if ($unreadCount > 0)
                                        <a href="#" wire:click.prevent="markAllAsRead" class="mark-all-read">Tandai
                                            semua dibaca</a>
                                    @endif
                                </li>
                                @forelse($notifications as $notification)
                                    <li class="notification-item {{ is_null($notification->read_at) ? 'unread' : '' }}">
                                        <a href="{{ $notification->data['action_url'] ?? $notification->data['action'] }}"
                                            class="d-flex align-items-center">
                                            <div class="notification-icon">
                                                <i class="zmdi {{ $notification->data['icon'] ?? 'zmdi-info' }}"></i>
                                            </div>
                                            <div class="notification-content">
                                                <p class="notification-title">{{ $notification->data['title'] }}</p>
                                                <p class="notification-message">{{ $notification->data['message'] }}</p>
                                                <small
                                                    class="notification-time">{{ $notification->created_at->diffForHumans() }}</small>
                                            </div>
                                        </a>
                                    </li>
                                @empty
                                    <li class="empty-notification">
                                        <p>Tidak ada notifikasi</p>
                                    </li>
                                @endforelse
                            </ul>
                        </div>
                    @endauth

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

    @push('scripts')
        <script>
            document.addEventListener('livewire:initialized', () => {
                // Initialize dropdown
                const notificationDropdown = new bootstrap.Dropdown(
                    document.querySelector('.notification-dropdown .dropdown-toggle')
                );

                // Refresh notifications when dropdown is shown
                document.querySelector('.notification-dropdown').addEventListener('shown.bs.dropdown', () => {
                    @this.loadNotifications();
                });

                // Handle click outside
                document.addEventListener('click', (e) => {
                    if (!e.target.closest('.notification-dropdown')) {
                        const dropdown = bootstrap.Dropdown.getInstance(
                            document.querySelector('.notification-dropdown .dropdown-toggle')
                        );
                        if (dropdown) {
                            dropdown.hide();
                        }
                    }
                });
            });
        </script>
    @endpush

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

    <style>
        /* Notification Dropdown */
        .notification-dropdown {
            position: relative;
        }

        .notification-menu {
            width: 350px;
            max-height: 400px;
            overflow-y: auto;
            padding: 0;
            border: 1px solid #eee;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .notification-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 15px;
            background: #f8f9fa;
            border-bottom: 1px solid #eee;
        }

        .notification-header h6 {
            margin: 0;
            font-weight: 600;
        }

        .mark-all-read {
            font-size: 12px;
            color: #6c757d;
        }

        .notification-item {
            border-bottom: 1px solid #f1f1f1;
        }

        .notification-item.unread {
            background-color: #f8f9fa;
        }

        .notification-item a {
            display: flex;
            padding: 10px 15px;
            color: #333;
        }

        .notification-item a:hover {
            background-color: #f1f1f1;
            text-decoration: none;
        }

        .notification-icon {
            margin-right: 10px;
            color: #6c757d;
            font-size: 18px;
        }

        .notification-content {
            flex: 1;
        }

        .notification-title {
            font-weight: 500;
            margin-bottom: 5px;
        }

        .notification-message {
            color: #6c757d;
            font-size: 13px;
            margin-bottom: 5px;
        }

        .notification-time {
            color: #adb5bd;
            font-size: 11px;
        }

        .empty-notification {
            padding: 15px;
            text-align: center;
            color: #6c757d;
        }

        .notification-footer {
            text-align: center;
            padding: 10px;
            background: #f8f9fa;
            border-top: 1px solid #eee;
        }

        .notification-footer a {
            color: #6c757d;
            font-size: 13px;
        }
    </style>
</header>
