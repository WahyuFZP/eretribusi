@extends('layouts.app')

@section('header')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-base-content">{{ __('Dashboard') }}</h1>
            <p class="text-base-content/70">{{ __('Welcome back to e-Retribusi system') }} {{ Auth::user()->name }}</p>
        </div>
        <div class="text-sm text-base-content/60">
            {{ now()->format('l, d F Y') }}
        </div>
    </div>
@endsection

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
@endsection