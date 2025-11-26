@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <div class="card bg-base-100 shadow">
        <div class="card-body">
            <h2 class="card-title">Daftar Pembayaran / Invoice</h2>
            <p class="text-sm text-gray-500">Halaman dummy untuk admin — menampilkan invoice_number dan status.</p>

            <div class="mt-4">
                <form method="get" action="{{ route('payments.index') }}" class="mb-4">
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
                            @forelse($bills as $bill)
                            <tr class="hover">
                                        <td class="font-mono">{{ $bill->bill_number ?? ('#' . $bill->id) }}</td>
                                        <td>{{ optional($bill->company)->name ?? '-' }}</td>
                                        <td>{{ number_format($bill->amount ?? 0, 0, ',', '.') }}</td>
                                        <td>{{ number_format($bill->paid_amount ?? 0, 0, ',', '.') }}</td>
                                        <td>
                                            <span class="badge badge-ghost">{{ strtoupper($bill->status ?? 'unpaid') }}</span>
                                        </td>
                                        <td>{{ optional($bill->issued_at)?->format('Y-m-d') ?? $bill->created_at?->format('Y-m-d') ?? '-' }}</td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-outline">Lihat</a>
                                    <button class="btn btn-sm btn-info">Bayar</button>
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
                    {{ $bills->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
