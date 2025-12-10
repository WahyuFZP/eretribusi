@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-4">
        <div class="card bg-base-100 shadow">
            <div class="card-body">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h2 class="card-title">Daftar Pembayaran / Invoice</h2>
                        <p class="text-sm text-gray-500">Halaman dummy untuk admin — menampilkan invoice_number dan status.</p>
                    </div>
                    
                    @if(auth()->user() && method_exists(auth()->user(), 'hasRole') && auth()->user()->hasRole(['super-admin', 'admin']))
                        <div class="flex gap-2">
                            <a href="{{ route('admin.bills.bulk-export-form') }}" class="btn btn-secondary">
                                <i class="fas fa-download"></i> 
                                Bulk Export
                            </a>
                        </div>
                    @endif
                </div>

                <div class="mt-4">
                    <form method="get" action="{{ route('payments.index') }}" class="mb-4">
                        <div class="flex gap-2">
                            <input type="search" name="q" value="{{ request('q') }}"
                                placeholder="Cari invoice_number..." class="input input-bordered w-full" />
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
                                        <td class="font-mono">{{ $bill->bill_number ?? '#' . $bill->id }}</td>
                                        <td>{{ optional($bill->company)->name ?? '-' }}</td>
                                        <td>{{ number_format($bill->amount ?? 0, 0, ',', '.') }}</td>
                                        <td>{{ number_format($bill->paid_amount ?? 0, 0, ',', '.') }}</td>
                                        <td>
                                            <div class="flex flex-col gap-1">
                                                @if (isset($bill->latestPayment) && $bill->latestPayment)
                                                    @php $p = $bill->latestPayment; @endphp
                                                    <div class="text-xs mt-1">Pembayaran:
                                                        @if ($p->status === 'paid')
                                                            <span
                                                                class="badge badge-success">{{ strtoupper($p->status) }}</span>
                                                        @elseif(in_array($p->status, ['pending', 'challenge']))
                                                            <span
                                                                class="badge badge-warning">{{ strtoupper($p->status) }}</span>
                                                        @else
                                                            <span
                                                                class="badge badge-secondary">{{ strtoupper($p->status) }}</span>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td>{{ optional($bill->issued_at)?->format('Y-m-d') ?? ($bill->created_at?->format('Y-m-d') ?? '-') }}
                                        </td>
                                        <td>
                                            <a href="{{ route('bills.export-pdf', $bill) }}" class="btn btn-sm btn-secondary">
                                                <i class="fas fa-file-pdf"></i> PDF
                                            </a>
                                            @php $latest = $bill->latestPayment ?? null; @endphp
                                            @if ($bill->status === 'paid' || ($latest && $latest->status === 'paid'))
                                                <span class="badge badge-success">Lunas</span>
                                            @else
                                                @if (isset($payment) && $payment->bill_id == $bill->id)
                                                    <button id="pay-button" class="btn btn-sm btn-info">Bayar</button>
                                                @else
                                                    <a href="{{ route('bills.pay', $bill) }}"
                                                        class="btn btn-sm btn-info">Bayar</a>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-8">
                                            <div class="text-gray-500">Belum ada invoice. Anda dapat membuat invoice melalui
                                                tinker atau seeder.</div>
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

    @isset($snapToken)
        <!-- Midtrans Snap JS -->
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
        </script>
        <script>
            (function() {
                function openSnap() {
                    snap.pay('{{ $snapToken }}', {
                        onSuccess: function(result) {
                            console.log('success', result);
                            window.location = '{{ route('payments.index') }}';
                        },
                        onPending: function(result) {
                            console.log('pending', result);
                            window.location = '{{ route('payments.index') }}';
                        },
                        onError: function(result) {
                            console.log('error', result);
                            alert('Terjadi kesalahan pada proses pembayaran.');
                        }
                    });
                }

                // Auto-open when token is present (return from bills.pay)
                if (typeof snap !== 'undefined') {
                    openSnap();
                } else {
                    // if script loads slightly later
                    window.addEventListener('load', function() {
                        if (typeof snap !== 'undefined') openSnap();
                    });
                }

                // Also wire the manual button as fallback
                var btn = document.getElementById('pay-button');
                if (btn) {
                    btn.addEventListener('click', function() {
                        openSnap();
                    });
                }
            })();
        </script>
    @endisset
@endsection
