<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('page-title', __('Dashboard')) - {{ config('app.name', 'e-Retribusi') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Apply saved theme early to avoid flash -->
        <script>
            (function () {
                try {
                    var saved = localStorage.getItem('theme');
                    if (saved) document.documentElement.setAttribute('data-theme', saved);
                } catch (e) {}
            })();
        </script>
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <!-- Sidebar Layout dengan DaisyUI -->
        <x-sidebar>
            <!-- Page Header (use sections when view extends this layout) -->
            @hasSection('header')
                <div class="mb-6">
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        @yield('header')
                    </div>
                </div>
            @endif

            <!-- Page Content -->
            <div class="space-y-6">
                @yield('content')
            </div>
        </x-sidebar>    
    </body>
</html>