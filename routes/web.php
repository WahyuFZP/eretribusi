<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\User\CompanyController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function (Request $request) {
    $user = $request->user();
    if (!$user) {
        return redirect()->route('login');
    }
    return $user->hasRole(['super-admin', 'admin'])
        ? view('admin.dashboard')
        : view('users.dashboard');    
})->middleware(['auth', 'verified'])->name('dashboard');



// Admin user management (super-admin and admin)
Route::resource('admin/users', UserController::class)
    ->middleware(['auth', 'role:super-admin|admin'])
    ->names('admin.users');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Users Company
Route::middleware(['auth', 'role:user'])->group(function () {
Route::resource('companies', CompanyController::class)
->names('users.company');
});

Route::resource('companies', CompanyController::class)
    ->middleware(['auth', 'role:user'])
    ->names('users.company');

require __DIR__.'/auth.php';
