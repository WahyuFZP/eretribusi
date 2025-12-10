<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $bill->bill_number ?? 'N/A' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
            line-height: 1.4;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #0b4b3f;
            padding-bottom: 20px;
        }
        
        .header h1 {
            color: #0b4b3f;
            margin: 0;
            font-size: 24px;
        }
        
        .header p {
            margin: 5px 0;
            color: #666;
        }
        
        .invoice-info {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        
        .invoice-info .left,
        .invoice-info .right {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        
        .invoice-info .right {
            text-align: right;
        }
        
        .section {
            margin-bottom: 25px;
        }
        
        .section h3 {
            color: #0b4b3f;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            margin-bottom: 15px;
            font-size: 14px;
        }
        
        .detail-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .detail-table th,
        .detail-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        
        .detail-table th {
            background-color: #f8f9fa;
            color: #0b4b3f;
            font-weight: bold;
        }
        
        .amount-section {
            text-align: right;
            margin-top: 20px;
        }
        
        .amount-section .total {
            font-size: 16px;
            font-weight: bold;
            color: #0b4b3f;
            border-top: 2px solid #0b4b3f;
            padding-top: 10px;
            margin-top: 10px;
        }
        
        .status {
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
        }
        
        .status.paid {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status.unpaid {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .status.partial {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
        
        .payment-history {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>INVOICE RETRIBUSI</h1>
        <p>Dinas Lingkungan Hidup Kota Madiun</p>
        <p>Jl. Salak III No.7a, Taman, Kec. Taman, Kota Madiun</p>
        <p>Telp: 0351 - 468876</p>
    </div>

    <div class="invoice-info">
        <div class="left">
            <h3>Detail Tagihan</h3>
            <p><strong>No. Tagihan:</strong> {{ $bill->bill_number ?? 'N/A' }}</p>
            <p><strong>Tanggal Tagihan:</strong> {{ $bill->issued_at ? \Carbon\Carbon::parse($bill->issued_at)->format('d/m/Y') : 'N/A' }}</p>
            <p><strong>Jatuh Tempo:</strong> {{ $bill->due_date ? \Carbon\Carbon::parse($bill->due_date)->format('d/m/Y') : 'N/A' }}</p>
            <p><strong>Periode:</strong> {{ $bill->billing_period ?? 'N/A' }}</p>
        </div>
        <div class="right">
            <h3>Informasi Perusahaan</h3>
            @if($company)
                <p><strong>Nama:</strong> {{ $company->name }}</p>
                <p><strong>Kode:</strong> {{ $company->code ?? 'N/A' }}</p>
                <p><strong>Email:</strong> {{ $company->email ?? 'N/A' }}</p>
            @else
                <p>Informasi perusahaan tidak tersedia</p>
            @endif
        </div>
    </div>

    <div class="section">
        <h3>Detail Pembayaran</h3>
        <table class="detail-table">
            <thead>
                <tr>
                    <th>Deskripsi</th>
                    <th style="width: 100px; text-align: right;">Jumlah</th>
                    <th style="width: 80px; text-align: center;">Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $bill->description ?? 'Retribusi' }}</td>
                    <td style="text-align: right;">Rp {{ number_format($bill->amount ?? 0, 0, ',', '.') }}</td>
                    <td style="text-align: center;">
                        <span class="status {{ $bill->status === 'paid' ? 'paid' : ($bill->status === 'partial' ? 'partial' : 'unpaid') }}">
                            {{ $bill->status === 'paid' ? 'LUNAS' : ($bill->status === 'partial' ? 'SEBAGIAN' : 'BELUM LUNAS') }}
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    @if($payments && $payments->count() > 0)
    <div class="section payment-history">
        <h3>Riwayat Pembayaran</h3>
        <table class="detail-table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Order ID</th>
                    <th style="text-align: right;">Jumlah</th>
                    <th style="text-align: center;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments as $payment)
                <tr>
                    <td>{{ $payment->created_at ? $payment->created_at->format('d/m/Y H:i') : 'N/A' }}</td>
                    <td>{{ $payment->order_id ?? 'N/A' }}</td>
                    <td style="text-align: right;">Rp {{ number_format($payment->amount ?? 0, 0, ',', '.') }}</td>
                    <td style="text-align: center;">
                        <span class="status {{ $payment->status === 'paid' ? 'paid' : 'unpaid' }}">
                            {{ strtoupper($payment->status ?? 'pending') }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <div class="amount-section">
        <p><strong>Total Tagihan:</strong> Rp {{ number_format($bill->amount ?? 0, 0, ',', '.') }}</p>
        <p><strong>Sudah Dibayar:</strong> Rp {{ number_format($bill->paid_amount ?? 0, 0, ',', '.') }}</p>
        <div class="total">
            <strong>Sisa Tagihan: Rp {{ number_format(($bill->amount ?? 0) - ($bill->paid_amount ?? 0), 0, ',', '.') }}</strong>
        </div>
    </div>

    @if($bill->notes)
    <div class="section">
        <h3>Catatan</h3>
        <p>{{ $bill->notes }}</p>
    </div>
    @endif

    <div class="footer">
        <p>Dokumen ini dibuat secara otomatis pada {{ $generated_at }}</p>
        <p>Sistem E-Retribusi - Dinas Lingkungan Hidup Kota Madiun</p>
    </div>
</body>
</html>