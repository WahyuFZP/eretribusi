<?php

use App\Http\Controllers\Admin\CompanyController as AdminCompanyController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\MidtransWebhookController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\CompanyController;
use App\Models\Bill;
use App\Models\Company;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;



// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return view('guest');
});

Route::get('/dashboard', function (Request $request) {
    $user = $request->user();
    if (! $user) {
        return redirect()->route('login');
    }

    // Admins need a list of companies for the dashboard table
    if ($user->hasRole(['super-admin', 'admin'])) {
        $companies = Company::latest()->take(10)->get();
        return view('admin.dashboard', compact('companies'));
    }

    return view('users.dashboard');
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

// Admin Company management (super-admin and admin)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:super-admin|admin'])->group(function () {
    Route::get('companies', [AdminCompanyController::class, 'index'])->name('company.index');
    Route::get('companies/create', [AdminCompanyController::class, 'create'])->name('company.create');
    Route::post('companies', [AdminCompanyController::class, 'store'])->name('company.store');
    Route::get('companies/{company}', [AdminCompanyController::class, 'show'])->name('company.show');
    Route::get('companies/{company}/edit', [AdminCompanyController::class, 'edit'])->name('company.edit');
    Route::patch('companies/{company}', [AdminCompanyController::class, 'update'])->name('company.update');
    Route::delete('companies/{company}', [AdminCompanyController::class, 'destroy'])->name('company.destroy');
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

    $user = $request->user();

    // Build base query with eager-loaded company
    $query = Bill::with('company')->latest();

    // If the authenticated user is a regular 'user', scope bills to their companies only
    if ($user && ! $user->hasRole(['super-admin', 'admin'])) {
        $query = Bill::whereHas('company', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->with('company')->latest();
    }

    // Optional search by invoice/bill number
    if ($search = $request->query('q')) {
        $query->where(function ($q) use ($search) {
                $q->where('bill_number', 'like', "%{$search}%")
              ->orWhereHas('company', function ($qc) use ($search) {
                  $qc->where('name', 'like', "%{$search}%");
              });
        });
    }

    $bills = $query->paginate(10);
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

// Route to initiate payment for a bill (requires authentication)
Route::get('bills/{bill}/pay', [BillController::class, 'pay'])
    ->middleware(['auth'])
    ->name('bills.pay');

// Route to export bill as PDF
Route::get('bills/{bill}/export-pdf', [BillController::class, 'exportPdf'])
    ->middleware(['auth'])
    ->name('bills.export-pdf');

// Admin bulk export routes
Route::get('admin/bills/bulk-export', [BillController::class, 'showBulkExportForm'])
    ->middleware(['auth', 'role:super-admin|admin'])
    ->name('admin.bills.bulk-export-form');

Route::post('admin/bills/bulk-export', [BillController::class, 'bulkExportPdf'])
    ->middleware(['auth', 'role:super-admin|admin'])
    ->name('admin.bills.bulk-export');

// Admin summary report export
Route::post('admin/bills/summary-report', [BillController::class, 'exportSummaryPdf'])
    ->middleware(['auth', 'role:super-admin|admin'])
    ->name('admin.bills.summary-report');

// Midtrans payment notification webhook via web.php (ensure CSRF exemption)
// Route::post('/midtrans/webhook', [MidtransWebhookController::class, 'notification'])
//     ->name('midtrans.notification')
//     ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

// Optional: simple ping to verify reachability from ngrok
// Route::get('/midtrans/notification/ping', function () {
//     return response()->json(['ok' => true]);
// });


Route::post('/midtrans/webhook', [MidtransWebhookController::class, 'notification']);


require __DIR__.'/auth.php';




