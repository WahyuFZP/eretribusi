<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\CompanyController;
use App\Models\Company;
use App\Models\Invoice;
use App\Models\Payment;
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
Route::get('admin/payments', function (Request $request) {
    // create some dummy invoices on first visit to help admin UI testing
    if (Invoice::count() === 0) {
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
            $inv = new Invoice([
                'company_id' => $company->id,
                'invoice_date' => now()->toDateString(),
                'due_date' => now()->addDays(14)->toDateString(),
                'amount' => 100000 * $i,
                'late_fee' => 0,
                'issued_at' => now(),
            ]);
            $inv->save();
        }
        
        // create sample payments for first two invoices (mark as paid)
        $firstTwo = Invoice::latest()->take(2)->get();
        foreach ($firstTwo as $invPaid) {
            $payment = Payment::create([
                'invoice_id' => $invPaid->id,
                'company_id' => $invPaid->company_id,
                'amount' => $invPaid->amount,
                'method' => 'manual',
                'gateway' => 'manual',
                'order_id' => null,
                'transaction_id' => 'DUMMY-'.now()->timestamp.'-'.($invPaid->id),
                'reference' => null,
                'status' => 'settled',
                'gateway_response' => null,
                'paid_at' => now(),
                'created_by' => $request->user()->id ?? null,
            ]);

            // update invoice paid_amount and status
            $invPaid->paid_amount = $invPaid->amount;
            $invPaid->status = 'paid';
            $invPaid->paid_at = now();
            $invPaid->save();
        }
    }

    $invoices = Invoice::latest()->paginate(10);
    return view('admin.payments.index', compact('invoices'));
})->middleware(['auth', 'role:super-admin|admin'])->name('admin.payments.index');

// Admin Tagihan
Route::get('admin/tagihan', [BillController::class, 'index'])
    ->middleware(['auth', 'role:super-admin|admin'])
    ->name('admin.tagihan.index');
Route::get('admin/tagihan/create', [BillController::class, 'create'])
    ->middleware(['auth', 'role:super-admin|admin'])
    ->name('admin.tagihan.create');
require __DIR__.'/auth.php';
