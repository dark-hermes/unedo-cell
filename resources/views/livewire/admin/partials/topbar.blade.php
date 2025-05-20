<header>
    <nav class="navbar navbar-expand navbar-light navbar-top">
        <div class="container-fluid">
            <a href="#" class="burger-btn d-block">
                <i class="bi bi-justify fs-3"></i>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-lg-0">
                    <li class="nav-item dropdown me-1">
                        <a class="nav-link active dropdown-toggle text-gray-600" href="#"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-envelope bi-sub fs-4"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-lg-end" aria-labelledby="dropdownMenuButton">
                            <li>
                                <h6 class="dropdown-header">
                                    Mail
                                </h6>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">No new mail</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown me-3" wire:ignore>
                        <a class="nav-link active dropdown-toggle text-gray-600" href="#"
                            wire:click="toggleNotifications" data-bs-toggle="dropdown" data-bs-display="static"
                            aria-expanded="false">
                            <i class="bi bi-bell bi-sub fs-4"></i>
                            <span class="badge badge-notification bg-danger">
                                {{ $unreadCount }}
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-center dropdown-menu-sm-end notification-dropdown"
                            aria-labelledby="dropdownMenuButton">
                            <li class="dropdown-header">
                                <h6>Notifications</h6>
                            </li>
                            @foreach ($notifications as $notification)
                                <li class="dropdown-item notification-item">
                                    <a class="d-flex align-items-center"
                                        href="{{ $notification->data['action_url'] ?? $notification->data['action'] }}">
                                        <div class="notification-text ms-4">
                                            <p class="notification-title font-bold">
                                                {{ $notification->data['title'] }}
                                            </p>
                                            <p class="notification-subtitle font-thin text-sm">
                                                {{ $notification->data['message'] }}
                                            </p>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                </ul>
                <div class="dropdown">
                    <a href="#" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="user-menu d-flex">
                            <div class="user-name text-end me-3">
                                <h6 class="mb-0 text-gray-600">
                                    {{ auth()->user()->name }}
                                </h6>
                                <p class="mb-0 text-sm text-gray-600">
                                    {{ auth()->user()->email }}
                                </p>
                            </div>
                            <div class="user-img d-flex align-items-center">
                                <div class="avatar avatar-md">
                                    <img src="{{ auth()->user()->image_url }}" alt={{ auth()->user()->name }} />
                                </div>
                            </div>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton"
                        style="min-width: 11rem">
                        <li>
                            <h6 class="dropdown-header">
                                Halo, {{ auth()->user()->name }}
                            </h6>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#"><i class="icon-mid bi bi-person me-2"></i>
                                My Profile</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#"><i class="icon-mid bi bi-gear me-2"></i>
                                Settings</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#"><i class="icon-mid bi bi-wallet me-2"></i>
                                Wallet</a>
                        </li>
                        <li>
                            <hr class="dropdown-divider" />
                        </li>
                        <li>
                            {{-- <a class="dropdown-item" href="#"><i
                                    class="icon-mid bi bi-box-arrow-left me-2"></i>
                                Logout</a> --}}
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="icon-mid bi bi-box-arrow-left me-2"></i>
                                    Logout
                                </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    @push('scripts')
        <script>
            document.addEventListener('livewire:initialized', () => {
                const notificationDropdown = document.querySelector('.notification-dropdown');

                notificationDropdown.addEventListener('shown.bs.dropdown', () => {
                    @this.markAllAsRead();
                });
            });
        </script>
    @endpush
</header>
