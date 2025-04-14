<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin' }}</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    @vite(['resources/admin/css/app.css', 'resources/admin/css/app-dark.css', 'resources/admin/js/app.js', 'resources/js/app.js'])
    @livewireStyles
</head>

<body>
    @vite('resources/admin/static/js/initTheme.js')

    <div id="app">
        <!-- Loading indicator -->
        {{-- <livewire:admin.partials.loader /> --}}


        <livewire:admin.partials.sidebar />
        <div id="main" class="layout-navbar navbar-fixed">
            <livewire:admin.partials.topbar />
            <div id="main-content">
                {{ $slot }}
            </div>
            <livewire:admin.partials.footer />
        </div>
    </div>
    @vite(['resources/admin/static/js/components/dark.js', 'resources/admin/static/js/components/toast.js'])
    @livewireScripts
</body>

</html>
