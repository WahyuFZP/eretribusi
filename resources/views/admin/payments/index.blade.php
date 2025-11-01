@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <div class="card bg-base-100 shadow">
        <div class="card-body">
            <h2 class="card-title">Daftar Pembayaran / Invoice</h2>
            <p class="text-sm text-gray-500">Halaman dummy untuk admin — menampilkan invoice_number dan status.</p>

            <div class="mt-4">
                <form method="get" action="{{ route('admin.payments.index') }}" class="mb-4">
                    <div class="flex gap-2">
                        <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari invoice_number..." class="input input-bordered w-full" />
                        <button type="submit" class="btn btn-primary">Cari</button>
                    </div>
                </form>

                <div class="overflow-x-auto">
                    <table class="table w-full">
                        <thead>
                            <tr>
                                <th>Invoice #</th>
                                <th>Company</th>
                                <th>Amount</th>
                                <th>Late Fee</th>
                                <th>Status</th>
                                <th>Issued</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($invoices as $invoice)
                            <tr class="hover">
                                <td class="font-mono">{{ $invoice->invoice_number }}</td>
                                <td>{{ optional($invoice->company)->name ?? '-' }}</td>
                                <td>{{ number_format($invoice->amount, 2, ',', '.') }}</td>
                                <td>{{ number_format($invoice->late_fee ?? 0, 2, ',', '.') }}</td>
                                <td>
                                    <span class="badge badge-ghost">{{ strtoupper($invoice->status) }}</span>
                                </td>
                                <td>{{ $invoice->issued_at?->format('Y-m-d') ?? $invoice->invoice_date ?? '-' }}</td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-outline">Lihat</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-8">
                                    <div class="text-gray-500">Belum ada invoice. Anda dapat membuat invoice melalui tinker atau seeder.</div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $invoices->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
