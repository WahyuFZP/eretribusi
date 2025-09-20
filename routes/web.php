<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('companies', 'companies.index')
    ->middleware(['auth', 'verified'])
    ->name('companies.index');

Route::view('payments', 'payments.index')
    ->middleware(['auth', 'verified'])
    ->name('payments.index');

Route::view('invoices', 'invoices.index')
    ->middleware(['auth', 'verified'])
    ->name('invoices.index');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');
});

require __DIR__.'/auth.php';
