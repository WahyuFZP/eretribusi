<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\CompanyController;
use App\Models\Company;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Bill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;


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

// Admin payments - simple listing view for admins
Route::get('payments', function (Request $request) {
    // create some dummy bills on first visit to help admin UI testing
    if (Bill::count() === 0) {
        $company = Company::first();
        if (! $company) {
            $company = Company::create([
                'user_id' => $request->user()->id ?? null,
                'name' => 'Sampah Lokal',
                'email' => 'info@sampah.local',
                'code' => 'SMPH-PG01',
            ]);
        }

        for ($i = 1; $i <= 5; $i++) {
            $bill = new Bill([
                'company_id' => $company->id,
                'bill_number' => 'BILL-'.str_pad($i,3,'0',STR_PAD_LEFT),
                'issued_at' => now(),
                'due_date' => now()->addDays(14)->toDateString(),
                'amount' => 100000 * $i,
                'paid_amount' => 0,
                'status' => 'unpaid',
            ]);
            $bill->save();
        }
    }

    $bills = Bill::latest()->paginate(10);
    return view('payments.index', compact('bills'));
})->middleware(['auth', 'role:super-admin|admin|user'])->name('payments.index');

// Admin Tagihan
Route::get('admin/tagihan', [BillController::class, 'index'])
    ->middleware(['auth', 'role:super-admin|admin'])
    ->name('admin.tagihan.index');
Route::get('admin/tagihan/create', [BillController::class, 'create'])
    ->middleware(['auth', 'role:super-admin|admin'])
    ->name('admin.tagihan.create');
Route::post('admin/tagihan', [BillController::class, 'store'])
    ->middleware(['auth', 'role:super-admin|admin'])
    ->name('admin.tagihan.store');
require __DIR__.'/auth.php';
