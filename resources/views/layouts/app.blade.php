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

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <!-- Sidebar Layout dengan DaisyUI -->
        <x-sidebar>
            <!-- Page Header -->
            @isset($header)
                <div class="mb-6">
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        {{ $header }}
                    </div>
                </div>
            @endisset

            <!-- Page Content -->
            <div class="space-y-6">
                {{ $slot }}
            </div>
        </x-sidebar>
    </body>
</html>
