<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\User;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:super-admin|admin']);
    }

    public function index(Request $request)
    {
        $query = Company::with('user')->orderBy('name');
        if ($q = $request->query('q')) {
            $query->where('name', 'like', "%{$q}%")->orWhere('code', 'like', "%{$q}%");
        }
        $companies = $query->paginate(15)->withQueryString();
        return view('admin.company.index', compact('companies'));
    }

    public function create()
    {
        $users = User::orderBy('name')->limit(200)->get();
        return view('admin.company.create', compact('users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'badan_usaha' => 'nullable|string|max:100',
            'jenis_usaha' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:1000',
            'user_id' => 'nullable|exists:users,id',
            // do not require code here; Company model may auto-generate it
        ]);

        $company = Company::create(array_merge($data, ['user_id' => $data['user_id'] ?? null]));

        return redirect()->route('admin.company.show', $company)->with('success', 'Company created.');
    }

    public function show(Company $company)
    {
        $company->load(['user', 'bills']);
        return view('admin.company.show', compact('company'));
    }

    public function edit(Company $company)
    {
        $users = User::orderBy('name')->limit(200)->get();
        return view('admin.company.create', compact('company', 'users'));
    }

    public function update(Request $request, Company $company)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'badan_usaha' => 'nullable|string|max:100',
            'jenis_usaha' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:1000',
            'user_id' => 'nullable|exists:users,id',
        ]);

        $company->update($data);
        return redirect()->route('admin.company.show', $company)->with('success', 'Company updated.');
    }

    public function destroy(Company $company)
    {
        $company->delete();
        return redirect()->route('admin.company.index')->with('success', 'Company deleted.');
    }
}
