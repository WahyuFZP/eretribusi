@extends('layouts.landing')

@section('page-title', 'Home')

@section('content')
<header class="bg-gradient-to-r from-primary to-indigo-600 text-white p-6">
    <nav class="container mx-auto flex items-center justify-between py-4">
        <a href="#" class="font-bold text-xl">{{ config('app.name', 'E-Retribusi') }}</a>
        <ul class="hidden md:flex gap-6">
            <li><a href="#home" class="hover:underline">Home</a></li>
            <li><a href="#about" class="hover:underline">About</a></li>
            <li><a href="#services" class="hover:underline">Services</a></li>
            <li><a href="#contact" class="hover:underline">Contact</a></li>
        </ul>

        <div class="flex items-center gap-2">
            @guest
                <a href="{{ route('login') }}" class="btn btn-ghost text-white">Login</a>
            @else
                <a href="{{ route('dashboard') }}" class="btn btn-outline text-white">Dashboard</a>
            @endguest

            {{-- Mobile Toggle --}}
            <button class="md:hidden btn btn-ghost text-white" onclick="document.querySelector('nav ul').classList.toggle('hidden')">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </nav>

</header>

@endsection
