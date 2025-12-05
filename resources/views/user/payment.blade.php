@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <div class="card bg-base-100 shadow">
        <div class="card-body">
            <h3 class="card-title">Pembayaran Tagihan</h3>
            <p>Tagihan: <strong>{{ $bill->bill_number ?? ('#'.$bill->id) }}</strong></p>
            <p>Jumlah: <strong>Rp {{ number_format($payment->amount,0,',','.') }}</strong></p>

            <div class="mt-4">
                <button id="pay-button" class="btn btn-primary">Bayar Sekarang</button>
                <a href="{{ asset('documents/perda_kotamadiun.pdf') }}" target="_blank" class="btn btn-ghost">Lihat Tarif</a>
            </div>
        </div>
    </div>
</div>

@isset($snapToken)
    <!-- Midtrans Snap JS -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    <script>
        (function(){
            var btn = document.getElementById('pay-button');
            if (!btn) return;

            btn.addEventListener('click', function () {
                snap.pay('{{ $snapToken }}', {
                    onSuccess: function(result){
                        console.log('success', result);
                        window.location = '{{ route('payments.index') }}';
                    },
                    onPending: function(result){
                        console.log('pending', result);
                        window.location = '{{ route('payments.index') }}';
                    },
                    onError: function(result){
                        console.log('error', result);
                        alert('Terjadi kesalahan pada proses pembayaran.');
                    }
                });
            });
        })();
    </script>
@endisset

@endsection
