<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin')</title>
    @vite(['resources/admin/css/app.css', 'resources/admin/css/app-dark.css', 'resources/admin/js/app.js'])
</head>

<body>
    @vite('resources/admin/static/js/initTheme.js')
    <div id="app">
        @include('components.admin.sidebar')
        <div id="main" class="layout-navbar navbar-fixed">
            @include('components.admin.topbar')
            <div id="main-content">
                @include('components.admin.page-heading')
                @yield('content')
            </div>
            @include('components.admin.footer')
        </div>
    </div>
    @vite('resources/admin/static/js/components/dark.js')
</body>

</html>
