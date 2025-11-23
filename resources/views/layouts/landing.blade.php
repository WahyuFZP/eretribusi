<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('page-title', __('Dashboard')) - {{ config('app.name', 'e-Retribusi') }}</title>

    <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

         @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <header class="w-full">
       
    </header>

    <main class="min-h-screen">
        @yield('content')
    </main>

    <footer class="py-6 text-center text-sm text-gray-500">
        &copy; {{ date('Y') }} {{ config('app.name', 'e-Retribusi') }}
    </footer>

</body>
</html>