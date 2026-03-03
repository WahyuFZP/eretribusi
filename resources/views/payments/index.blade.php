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
                                                @php $billStatus = strtolower($bill->status ?? 'unpaid'); @endphp
                                                <div class="text-xs">Tagihan:
                                                    @if($billStatus === 'paid')
                                                        <span class="badge badge-success">LUNAS</span>
                                                    @elseif($billStatus === 'partial')
                                                        <span class="badge badge-warning">SEBAGIAN</span>
                                                    @elseif($billStatus === 'cancelled')
                                                        <span class="badge">BATAL</span>
                                                    @else
                                                        <span class="badge badge-error">BELUM LUNAS</span>
                                                    @endif
                                                </div>

                                                {{-- @if (isset($bill->latestPayment) && $bill->latestPayment)
                                                    @php $p = $bill->latestPayment; $ps = strtolower($p->status ?? ''); @endphp
                                                    <div class="text-xs mt-1">Pembayaran:
                                                        @if (in_array($ps, ['paid','settlement','success','capture','captured']))
                                                            <span class="badge badge-success">{{ strtoupper($p->status) }}</span>
                                                        @elseif(in_array($ps, ['pending','challenge','authorize']))
                                                            <span class="badge badge-warning">{{ strtoupper($p->status) }}</span>
                                                        @elseif(in_array($ps, ['cancel','canceled','deny','expired']))
                                                            <span class="badge badge-error">{{ strtoupper($p->status) }}</span>
                                                        @else
                                                            <span class="badge badge-secondary">{{ strtoupper($p->status ?? 'UNKNOWN') }}</span>
                                                        @endif
                                                    </div>
                                                @else
                                                    <div class="text-xs mt-1">Pembayaran: <span class="badge">—</span></div>
                                                @endif --}}
                                            </div>
                                        </td>
                                        <td>{{ optional($bill->issued_at)?->format('Y-m-d') ?? ($bill->created_at?->format('Y-m-d') ?? '-') }}
                                        </td>
                                        <td>
                                            <div class="flex items-center gap-2">
                                                <a href="{{ route('bills.export-pdf', $bill) }}" class="btn btn-xs btn-secondary">
                                                    PDF
                                                </a>
                                                <span class="text-gray-400">•</span>
                                                @php $latest = $bill->latestPayment ?? null; $isPaid = ($bill->status === 'paid') || ($latest && strtolower($latest->status) === 'paid'); @endphp
                                                @if ($isPaid)
                                                    <span class="badge badge-success">Lunas</span>
                                                @else
                                                    @if (isset($payment) && $payment->bill_id == $bill->id)
                                                        <button id="pay-button" class="btn btn-xs btn-info">Bayar</button>
                                                    @else
                                                        <a href="{{ route('bills.pay', $bill) }}" class="btn btn-xs btn-info">Bayar</a>
                                                    @endif
                                                @endif
                                            </div>
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
