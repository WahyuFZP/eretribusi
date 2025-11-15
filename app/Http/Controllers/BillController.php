<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Company;
use App\Http\Requests\StoreBillRequest;
use App\Http\Requests\UpdateBillRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
}
