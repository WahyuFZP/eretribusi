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
    public function index()
    {
        // minimal index to power admin/tagihan view
        $bills = Bill::latest()->paginate(10);

        // Precompute active (unpaid/partial) bills sums per company to avoid calling relations in the view
        $companies = Company::orderBy('name')
            ->withSum([
                // alias with constraint: sum 'amount' for bills that are not paid
                'bills as active_bills_sum' => function ($q) {
                    $q->where('status', '<>', 'paid');
                }
            ], 'amount')
            ->get();

        return view('admin.tagihan.index', compact('bills', 'companies'));
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

            // handle document upload
            if ($request->hasFile('document')) {
                $path = $request->file('document')->store('bills', 'public');
                $attrs['document'] = $path;
            }

            $bill = Bill::create($attrs);

            return $bill;
        });

        return redirect()->route('admin.tagihan.index')->with('success', 'Tagihan berhasil dibuat (No: ' . $bill->bill_number . ')');
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

        // Ensure the listing view has the expected variables (pagination/list)
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

        // Prepare data for PDF
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
        $company_id = $request->input('company_id');

        // Build query
        $query = Bill::with(['company', 'payments']);

        // Filter by year
        if ($year) {
            $query->whereYear('created_at', $year);
        }

        // Filter by month if specified
        if ($month) {
            $query->whereMonth('created_at', $month);
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
            $query->whereMonth('created_at', $month);
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

        // Prepare data for PDF
        $data = [
            'bills' => $bills,
            'year' => $year,
            'month' => $month,
            'month_name' => $month ? \Carbon\Carbon::create()->month($month)->format('F') : null,
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

    

}
