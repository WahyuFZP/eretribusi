<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Company;
use App\Http\Requests\StoreBillRequest;
use App\Http\Requests\UpdateBillRequest;

class BillController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // minimal index to power admin/tagihan view
        $bills = Bill::latest()->paginate(10);
        $companies = Company::orderBy('name')->get();

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
        //
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
