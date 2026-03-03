<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Company;
use App\Http\Requests\StoreBillRequest;
use App\Http\Requests\UpdateBillRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;
use Dompdf\Dompdf;
use Dompdf\Options;
use ZipArchive;

class BillController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q'));
        $companyId = $request->query('company_id');

        // Base companies options for the filter dropdown (unfiltered)
        $companyOptions = Company::orderBy('name')->get();

        // If no filter, show companies summary table. If filtered, show bills table.
        $companies = collect();
        $bills = null;

        if (!$q && !$companyId) {
            $companies = Company::orderBy('name')
                ->withSum([
                    'bills as active_bills_sum' => function ($qsum) {
                        // Exclude bills that are already settled (paid) or still pending if desired
                        $qsum->whereNotIn('status', ['paid', 'pending']);
                    }
                ], 'amount')
                ->get();
        } else {
            $query = Bill::with('company')->latest();

            if ($companyId) {
                $query->where('company_id', $companyId);
            }

            if ($q) {
                $query->where(function ($qb) use ($q) {
                    $qb->where('bill_number', 'like', "%{$q}%")
                        ->orWhereHas('company', function ($qc) use ($q) {
                            $qc->where('name', 'like', "%{$q}%")
                               ->orWhere('email', 'like', "%{$q}%")
                               ->orWhere('code', 'like', "%{$q}%");
                        });
                });
            }

            $bills = $query->paginate(10);
        }

        return view('admin.tagihan.index', [
            'bills' => $bills,
            'companies' => $companies,
            'companyOptions' => $companyOptions,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Provide data for the Create Tagihan form.
        $companies = Company::orderBy('name')->get();
        $companyId = request('company_id');

        return view('admin.tagihan.create', compact('companies', 'companyId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBillRequest $request)
    {
        $data = $request->validated();

        $bill = DB::transaction(function () use ($data, $request) {
            // Resolve or create company
            $company = null;
            if (!empty($data['company_id'])) {
                $company = Company::find($data['company_id']);
            } elseif (!empty($data['company_name'])) {
                $company = Company::firstOrCreate(
                    ['name' => $data['company_name']],
                    ['code' => null]
                );
            }

            // Generate bill number if not provided
            $billNumber = $data['bill_number'] ?? ('BILL-' . strtoupper(Str::random(6)) . '-' . date('YmdHis'));

            $attrs = [
                'bill_number' => $billNumber,
                'company_id' => $company->id ?? null,
                'description' => $data['description'] ?? null,
                'amount' => $data['amount'] ?? 0,
                'paid_amount' => $data['paid_amount'] ?? 0,
                'due_date' => $data['due_date'] ?? null,
                'billing_period' => $data['billing_period'] ?? null,
                'status' => $data['status'] ?? 'unpaid',
                'notes' => $data['notes'] ?? null,
                'created_by' => $request->user()->id ?? null,
            ];

            // Handle recurring bill setup
            if (!empty($data['is_recurring']) && $data['is_recurring']) {
                $attrs['is_recurring'] = true;
                $attrs['recurring_frequency'] = $data['recurring_frequency'] ?? 'monthly';
                
                // Set recurring day of month from due_date if provided
                if (!empty($data['due_date'])) {
                    $dueDate = \Carbon\Carbon::parse($data['due_date']);
                    $attrs['recurring_day_of_month'] = $dueDate->day;
                    
                    // Calculate next billing date based on frequency
                    $nextBillingDate = match ($attrs['recurring_frequency']) {
                        'monthly' => $dueDate->copy()->addMonth(),
                        'yearly' => $dueDate->copy()->addYear(),
                        default => null,
                    };
                    
                    if ($nextBillingDate) {
                        $attrs['next_billing_date'] = $nextBillingDate->toDateString();
                    }
                }
                
                $attrs['issued_at'] = now();
            }

            // handle document upload
            if ($request->hasFile('document')) {
                $path = $request->file('document')->store('bills', 'public');
                $attrs['document'] = $path;
            }

            $bill = Bill::create($attrs);

            return $bill;
        });

        $message = 'Tagihan berhasil dibuat (No: ' . $bill->bill_number . ')';
        if ($bill->is_recurring) {
            $message .= '. Tagihan otomatis akan dibuat setiap ' . 
                       ($bill->recurring_frequency === 'monthly' ? 'bulan' : 'tahun') . 
                       ' pada tanggal ' . $bill->recurring_day_of_month;
        }

        return redirect()->route('admin.tagihan.index')->with('success', $message);
    }

    /**
     * Display the specified resource.
     */
    public function show(Bill $bill)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bill $bill)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBillRequest $request, Bill $bill)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bill $bill)
    {
        //
    }

    public function pay(Request $request, Bill $bill)
    {
        // prefer the request user to avoid issues when called in different contexts
        $user = $request->user();
 
        // ensure only authenticated users can create a payment
        if (! $user) {
            return redirect()->route('login');
        }

        // simple ownership check: allow admins or the owner of the company attached to the bill
        if (method_exists($user, 'hasRole')) {
            $isAdmin = $user->hasRole(['super-admin', 'admin']);
        } else {
            $isAdmin = false;
        }

        $isOwner = $bill->company && $bill->company->user_id === $user->id;

        if (! $isAdmin && ! $isOwner) {
            abort(403);
        }

        // create a Payment record first for tracking
        $payment = Payment::create([
            'bill_id' => $bill->id,
            'user_id' => $user->id ?? null,
            'order_id' => 'TEMP',
            'amount' => (int) ($bill->amount ?? 0),
            'status' => 'pending',
        ]);

        // set a meaningful, unique order id including payment id
        $payment->order_id = 'PAY-' . $payment->id . '-' . time();
        $payment->save();

        $params = [
            'transaction_details' => [
                'order_id' => $payment->order_id,
                'gross_amount' => (int) $payment->amount,
            ],
            'customer_details' => [
                'first_name' => $user->name ?? null,
                'email' => $user->email ?? null,
            ],
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            $payment->snap_token = $snapToken;
            $payment->save();
        } catch (\Throwable $e) {
            Log::error('Midtrans Snap error: ' . $e->getMessage());
            return back()->with('error', 'Gagal membuat transaksi pembayaran. Silakan coba lagi.');
        }

       
        // Scope Bill Admin Melihat semua Tagihan dan User hanya Tagihan miliknya
        $query = Bill::with('company')->latest();
        if($user && ! $isAdmin){
            $query = Bill::whereHas('company', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->with('company')->Latest();
        }
        $bills = $query->paginate(10);


        $viewData = [
            'payment' => $payment,
            'bill' => $bill,
            'bills' => $bills,
        ];

        if (isset($snapToken)) {
            $viewData['snapToken'] = $snapToken;
        }

        return view('payments.index', $viewData);
    }

    /**
     * Export bill to PDF
     */
    public function exportPdf(Request $request, Bill $bill)
    {
        // Simple authorization check
        $user = $request->user();
        if (!$user) {
            return redirect()->route('login');
        }

        $isAdmin = method_exists($user, 'hasRole') ? $user->hasRole(['super-admin', 'admin']) : false;
        $isOwner = $bill->company && $bill->company->user_id === $user->id;

        if (!$isAdmin && !$isOwner) {
            abort(403);
        }

        $data = [
            'bill' => $bill,
            'company' => $bill->company,
            'payments' => $bill->payments ?? collect(),
            'generated_at' => now()->format('d/m/Y H:i:s'),
        ];

        // Create HTML content
        $html = view('pdf.invoice', $data)->render();

        // Configure dompdf
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);

        // Create PDF
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Download PDF
        $filename = 'invoice-' . $bill->bill_number . '-' . now()->format('Ymd') . '.pdf';
        
        return response()->streamDownload(function() use ($dompdf) {
            echo $dompdf->output();
        }, $filename, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    /**
     * Bulk export invoices for admin
     */
    public function bulkExportPdf(Request $request)
    {
        // Only admin can access
        $user = $request->user();
        if (!$user || !method_exists($user, 'hasRole') || !$user->hasRole(['super-admin', 'admin'])) {
            abort(403, 'Unauthorized access');
        }

        // Get filter parameters
        $year = $request->input('year', now()->year);
        $month = $request->input('month');
        // Normalize month to integer 1-12 if provided as name (supports EN/ID)
        if ($month) {
            $map = [
                'january' => 1, 'jan' => 1, 'januari' => 1,
                'february' => 2, 'feb' => 2, 'februari' => 2,
                'march' => 3, 'mar' => 3, 'maret' => 3,
                'april' => 4,
                'may' => 5, 'mei' => 5,
                'june' => 6, 'jun' => 6, 'juni' => 6,
                'july' => 7, 'jul' => 7, 'juli' => 7,
                'august' => 8, 'aug' => 8, 'agustus' => 8,
                'september' => 9, 'sep' => 9,
                'october' => 10, 'oct' => 10, 'oktober' => 10,
                'november' => 11, 'nov' => 11,
                'december' => 12, 'dec' => 12, 'desember' => 12,
            ];
            if (!is_numeric($month)) {
                $key = strtolower(trim($month));
                if (isset($map[$key])) {
                    $month = $map[$key];
                }
            } else {
                $month = (int) $month;
            }
            if (!is_int($month) || $month < 1 || $month > 12) {
                $month = null;
            }
        }
        // Normalize month to integer 1-12 if provided as name (supports EN/ID)
        if ($month) {
            $map = [
                'january' => 1, 'jan' => 1, 'januari' => 1,
                'february' => 2, 'feb' => 2, 'februari' => 2,
                'march' => 3, 'mar' => 3, 'maret' => 3,
                'april' => 4,
                'may' => 5, 'mei' => 5,
                'june' => 6, 'jun' => 6, 'juni' => 6,
                'july' => 7, 'jul' => 7, 'juli' => 7,
                'august' => 8, 'aug' => 8, 'agustus' => 8,
                'september' => 9, 'sep' => 9,
                'october' => 10, 'oct' => 10, 'oktober' => 10,
                'november' => 11, 'nov' => 11,
                'december' => 12, 'dec' => 12, 'desember' => 12,
            ];
            if (!is_numeric($month)) {
                $key = strtolower(trim($month));
                if (isset($map[$key])) {
                    $month = $map[$key];
                }
            } else {
                $month = (int) $month;
            }
            if (!is_int($month) || $month < 1 || $month > 12) {
                $month = null;
            }
        }
        
        $company_id = $request->input('company_id');

        // Build query
        $query = Bill::with(['company', 'payments']);

        // Filter by year (align with payment year when month filter is used)
        if ($year && !$month) {
            // No month filter: use bill creation year
            $query->whereYear('created_at', $year);
        }

        // Filter strictly by payment month (and year)
        if ($month) {
            $query->whereHas('payments', function($p) use ($month, $year) {
                $p->whereMonth('paid_at', $month);
                if ($year) {
                    $p->whereYear('paid_at', $year);
                }
            });
        }

        // Filter by company if specified
        if ($company_id) {
            $query->where('company_id', $company_id);
        }

        $bills = $query->orderBy('created_at', 'desc')->get();

        if ($bills->isEmpty()) {
            return back()->with('error', 'Tidak ada data tagihan untuk periode yang dipilih.');
        }

        // Create temp directory for PDFs
        $tempDir = storage_path('app/temp/bulk-invoices-' . uniqid());
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $pdfPaths = [];

        // Configure dompdf once
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);

        foreach ($bills as $bill) {
            try {
                // Prepare data for PDF
                $data = [
                    'bill' => $bill,
                    'company' => $bill->company,
                    'payments' => $bill->payments ?? collect(),
                    'generated_at' => now()->format('d/m/Y H:i:s'),
                ];

                // Create HTML content
                $html = view('pdf.invoice', $data)->render();

                // Create PDF
                $dompdf = new Dompdf($options);
                $dompdf->loadHtml($html);
                $dompdf->setPaper('A4', 'portrait');
                $dompdf->render();

                // Save PDF to temp file
                $filename = 'invoice-' . $bill->bill_number . '.pdf';
                $filePath = $tempDir . '/' . $filename;
                file_put_contents($filePath, $dompdf->output());
                $pdfPaths[] = $filePath;

            } catch (\Exception $e) {
                Log::error('Error generating PDF for bill ' . $bill->id . ': ' . $e->getMessage());
                continue; // Skip this bill and continue with others
            }
        }

        if (empty($pdfPaths)) {
            return back()->with('error', 'Gagal generate PDF untuk periode yang dipilih.');
        }

        // Create ZIP file
        $zipFileName = 'bulk-invoices-' . $year . ($month ? '-' . str_pad($month, 2, '0', STR_PAD_LEFT) : '') . '-' . now()->format('Ymd-His') . '.zip';
        $zipPath = storage_path('app/temp/' . $zipFileName);

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE) !== TRUE) {
            return back()->with('error', 'Gagal membuat file ZIP.');
        }

        foreach ($pdfPaths as $pdfPath) {
            $zip->addFile($pdfPath, basename($pdfPath));
        }
        $zip->close();

        // Clean up temp PDFs
        foreach ($pdfPaths as $pdfPath) {
            unlink($pdfPath);
        }
        rmdir($tempDir);

        // Download ZIP and clean up
        return response()->download($zipPath, $zipFileName)->deleteFileAfterSend();
    }

    /**
     * Show bulk export form for admin
     */
    public function showBulkExportForm(Request $request)
    {
        // Only admin can access
        $user = $request->user();
        if (!$user || !method_exists($user, 'hasRole') || !$user->hasRole(['super-admin', 'admin'])) {
            abort(403, 'Unauthorized access');
        }

        $companies = Company::orderBy('name')->get();
        $years = range(date('Y'), date('Y') - 5); // Last 5 years
        
        return view('admin.bulk-export', compact('companies', 'years'));
    }

    /**
     * Export summary report PDF (all invoices in one PDF)
     */
    public function exportSummaryPdf(Request $request)
    {
        // Only admin can access
        $user = $request->user();
        if (!$user || !method_exists($user, 'hasRole') || !$user->hasRole(['super-admin', 'admin'])) {
            abort(403, 'Unauthorized access');
        }

        // Get filter parameters
        $year = $request->input('year', now()->year);
        $month = $request->input('month');
        $company_id = $request->input('company_id');

        // Build query
        $query = Bill::with(['company', 'payments']);

        // Filter by year
        if ($year) {
            $query->whereYear('created_at', $year);
        }

        // Filter by month if specified
        if ($month) {
    $map = [
        'january' => 1, 'jan' => 1, 'januari' => 1,
        'february' => 2, 'feb' => 2, 'februari' => 2,
        'march' => 3, 'mar' => 3, 'maret' => 3,
        'april' => 4,
        'may' => 5, 'mei' => 5,
        'june' => 6, 'jun' => 6, 'juni' => 6,
        'july' => 7, 'jul' => 7, 'juli' => 7,
        'august' => 8, 'aug' => 8, 'agustus' => 8,
        'september' => 9, 'sep' => 9,
        'october' => 10, 'oct' => 10, 'oktober' => 10,
        'november' => 11, 'nov' => 11,
        'december' => 12, 'dec' => 12, 'desember' => 12,
    ];

    if (!is_numeric($month)) {
        $key = strtolower(trim($month));
        $month = $map[$key] ?? null;
    } else {
        $month = (int) $month;
    }

    if ($month < 1 || $month > 12) {
        $month = null;
    }
}
if ($month) {
    $query->whereHas('payments', function ($p) use ($month) {
        $p->whereMonth('paid_at', $month);
    });
}

        // Filter by company if specified
        if ($company_id) {
            $query->where('company_id', $company_id);
        }

        $bills = $query->orderBy('created_at', 'desc')->get();

        if ($bills->isEmpty()) {
            return back()->with('error', 'Tidak ada data tagihan untuk periode yang dipilih.');
        }

        // Calculate summary data
        $totalAmount = $bills->sum('amount');
        $totalPaid = $bills->sum('paid_amount');
        $totalOutstanding = $totalAmount - $totalPaid;
        $totalPaidCount = $bills->where('status', 'paid')->count();
        $totalUnpaidCount = $bills->where('status', 'unpaid')->count();

        // Prepare month name safely
        $monthName = null;
        if ($month) {
            $locale = app()->getLocale();
            $monthName = \Carbon\Carbon::create()->locale($locale)->month($month)->isoFormat('MMMM');
        }

        // Prepare data for PDF
        $data = [
            'bills' => $bills,
            'year' => $year,
            'month' => $month,
            'month_name' => $monthName,
            'company' => $company_id ? \App\Models\Company::find($company_id) : null,
            'summary' => [
                'total_amount' => $totalAmount,
                'total_paid' => $totalPaid,
                'total_outstanding' => $totalOutstanding,
                'paid_count' => $totalPaidCount,
                'unpaid_count' => $totalUnpaidCount,
                'total_count' => $bills->count(),
            ],
            'generated_at' => now()->format('d/m/Y H:i:s'),
        ];

        // Create HTML content
        $html = view('pdf.summary-report', $data)->render();

        // Configure dompdf
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);

        // Create PDF
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape'); // Landscape untuk tabel yang lebih lebar
        $dompdf->render();

        // Generate filename
        $filename = 'laporan-invoice-' . $year;
        if ($month) {
            $filename .= '-' . str_pad($month, 2, '0', STR_PAD_LEFT);
        }
        if ($company_id) {
            $filename .= '-' . Str::slug($data['company']->name ?? 'company');
        }
        $filename .= '-' . now()->format('Ymd') . '.pdf';

        // Download PDF
        return response()->streamDownload(function() use ($dompdf) {
            echo $dompdf->output();
        }, $filename, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    /**
     * Setup recurring billing for an existing bill
     */
    public function setupRecurring(Request $request, Bill $bill)
    {
        // Authorization check
        $user = $request->user();
        $isAdmin = method_exists($user, 'hasRole') ? $user->hasRole(['super-admin', 'admin']) : false;
        
        if (!$isAdmin) {
            abort(403, 'Only admin can setup recurring billing');
        }

        $request->validate([
            'recurring_frequency' => 'required|in:monthly,yearly',
            'recurring_day_of_month' => 'required|integer|min:1|max:31',
            'start_date' => 'required|date|after_or_equal:today',
        ]);

        $startDate = \Carbon\Carbon::parse($request->start_date);
        
        // Calculate next billing date
        $nextBillingDate = match ($request->recurring_frequency) {
            'monthly' => $startDate->copy()->addMonth()->day($request->recurring_day_of_month),
            'yearly' => $startDate->copy()->addYear()->day($request->recurring_day_of_month),
            default => null,
        };

        $bill->update([
            'is_recurring' => true,
            'recurring_frequency' => $request->recurring_frequency,
            'recurring_day_of_month' => $request->recurring_day_of_month,
            'next_billing_date' => $nextBillingDate,
        ]);

        $message = 'Tagihan otomatis berhasil diaktifkan. ' .
                  'Tagihan selanjutnya akan dibuat otomatis setiap ' .
                  ($request->recurring_frequency === 'monthly' ? 'bulan' : 'tahun') .
                  ' pada tanggal ' . $request->recurring_day_of_month;

        return back()->with('success', $message);
    }

    /**
     * Disable recurring billing
     */
    public function disableRecurring(Request $request, Bill $bill)
    {
        $user = $request->user();
        $isAdmin = method_exists($user, 'hasRole') ? $user->hasRole(['super-admin', 'admin']) : false;
        
        if (!$isAdmin) {
            abort(403, 'Only admin can disable recurring billing');
        }

        $bill->update([
            'is_recurring' => false,
            'recurring_frequency' => null,
            'recurring_day_of_month' => null,
            'next_billing_date' => null,
        ]);

        return back()->with('success', 'Tagihan otomatis berhasil dinonaktifkan');
    }

    /**
     * Manually generate next recurring bill
     */
    public function generateNext(Request $request, Bill $bill)
    {
        $user = $request->user();
        $isAdmin = method_exists($user, 'hasRole') ? $user->hasRole(['super-admin', 'admin']) : false;
        
        if (!$isAdmin) {
            abort(403, 'Only admin can generate bills manually');
        }

        if (!$bill->is_recurring) {
            return back()->with('error', 'Tagihan ini bukan tagihan otomatis');
        }

        try {
            $newBill = $bill->generateNextBill();
            
            if ($newBill) {
                return back()->with('success', 'Tagihan baru berhasil dibuat: ' . $newBill->bill_number);
            } else {
                return back()->with('warning', 'Tagihan untuk periode ini sudah ada');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuat tagihan: ' . $e->getMessage());
        }
    }

    /**
     * Show recurring bills management page
     */
    public function recurringIndex(Request $request)
    {
        $user = $request->user();
        $isAdmin = method_exists($user, 'hasRole') ? $user->hasRole(['super-admin', 'admin']) : false;
        
        if (!$isAdmin) {
            abort(403);
        }

        $recurringBills = Bill::where('is_recurring', true)
            ->with(['company', 'childBills'])
            ->orderBy('next_billing_date')
            ->paginate(15);

        return view('admin.tagihan.recurring', compact('recurringBills'));
    }

    

}
