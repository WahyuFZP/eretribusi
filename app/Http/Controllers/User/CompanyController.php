<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;


class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $companies = $request->user()->companies()->search($request->input('q'))->latest()->paginate(10)->withQueryString();
        return view('users.company.index', compact('companies'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('users.company.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'badan_usaha' => 'nullable|string|max:100',
            'jenis_usaha' => 'nullable|string|max:100',
        ]);

        $company = $request->user()->companies()->create($data);

        return redirect()->route('users.company.show', $company)
            ->with('status', 'Perusahaan berhasil ditambahkan.');

    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Company $company)
    {
        abort_if($company->user_id !== $request->user()->id, 403);
        return view('users.company.show', compact('company'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Company $company)
    {
        abort_if($company->user_id !== $request->user()->id, 403);
        return view('users.company.edit', compact('company'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company)
    {
        abort_if($company->user_id !== $request->user()->id, 403);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'badan_usaha' => 'nullable|string|max:100',
            'jenis_usaha' => 'nullable|string|max:100',
        ]);

        $company->update($data);

        return redirect()->route('users.company.show', $company)
            ->with('status', 'Perusahaan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Company $company)
    {
        abort_if($company->user_id !== $request->user()->id, 403);
        $company->delete();
        return redirect()->route('users.company.index')
            ->with('status', 'Perusahaan berhasil dihapus.');
    }
}
