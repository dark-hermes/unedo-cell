<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin' }}</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <!-- Add these scripts before Livewire -->
    <script>
        window.livewire_app_url = '{{ config('app.url') }}';
        window.livewire_token = '{{ csrf_token() }}';
        document.addEventListener('DOMContentLoaded', function() {
            Livewire.on('reinitialize', () => {
                Livewire.rescan();
            });
        });
    </script>

    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/leaflet/leaflet.css') }}">

    @vite(['resources/admin/css/app.css', 'resources/admin/css/app-dark.css', 'resources/js/app.js', 'resources/admin/js/app.js'])
    @livewireStyles
</head>

<body>
    @vite('resources/admin/static/js/initTheme.js')

    <div id="app">
        <livewire:admin.partials.sidebar />
        <div id="main" class="layout-navbar navbar-fixed">
            <livewire:admin.partials.topbar />
            <div id="main-content">
                {{ $slot }}
            </div>
            <livewire:admin.partials.footer />
        </div>
    </div>

    <!-- Add these right before Livewire scripts -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        axios.interceptors.request.use((config) => {
            config.headers['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;
            return config;
        });
    </script>
    <script src="{{ asset('vendor/leaflet/leaflet.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    


    @vite(['resources/admin/static/js/components/dark.js', 'resources/admin/static/js/components/toast.js', 'resources/admin/static/js/components/sweetalert.js', 'resources/admin/static/js/pages/dashboard.js'])
    @livewireScripts
    @stack('scripts')
</body>

</html>
