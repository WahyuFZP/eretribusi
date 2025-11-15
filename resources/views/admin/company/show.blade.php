@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-2xl font-bold">Company: {{ $company->name }}</h1>
            <div class="flex items-center space-x-2">
                <a href="{{ route('admin.company.edit', $company) }}" class="btn btn-outline">Edit</a>
                <a href="{{ route('admin.company.index') }}" class="btn">Back</a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success mb-4">{{ session('success') }}</div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="card bg-base-100 p-4">
                <h3 class="font-semibold">Details</h3>
                <dl class="mt-2">
                    <div class="mb-2"><strong>Code:</strong> {{ $company->code ?? '-' }}</div>
                    <div class="mb-2"><strong>Name:</strong> {{ $company->name }}</div>
                    <div class="mb-2"><strong>Email:</strong> {{ $company->email ?? '-' }}</div>
                    <div class="mb-2"><strong>Phone:</strong> {{ $company->phone ?? '-' }}</div>
                    <div class="mb-2"><strong>Address:</strong> {{ $company->address ?? '-' }}</div>
                    <div class="mb-2"><strong>Owner (user):</strong> {{ optional($company->user)->name ?? '-' }}</div>
                    <div class="mb-2"><strong>Created at:</strong> {{ $company->created_at?->toDayDateTimeString() ?? '-' }}</div>
                </dl>
            </div>

            <div>
                <div class="card bg-base-100 p-4 mb-4">
                    <h3 class="font-semibold">Billing / Invoices</h3>
                    @if ($company->bills && $company->bills->count())
                        <table class="table w-full mt-2">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Bill Number</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($company->bills as $bill)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $bill->bill_number }}</td>
                                        <td>{{ number_format($bill->amount) }}</td>
                                        <td>{{ ucfirst($bill->status ?? 'unpaid') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-sm text-gray-500 mt-2">No bills found for this company.</div>
                    @endif
                </div>
                <div class="card bg-base-100 p-4">
                    <h3 class="font-semibold">Actions</h3>
                    <div class="mt-2">
                        <a href="{{ route('admin.tagihan.create') }}?company_id={{ $company->id }}" class="btn btn-primary">Create Bill</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
