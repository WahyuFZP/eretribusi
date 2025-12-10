<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Invoice {{ $year }}{{ $month ? ' - ' . $month_name : '' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 15px;
            font-size: 11px;
            line-height: 1.3;
        }
        
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #0b4b3f;
            padding-bottom: 15px;
        }
        
        .header h1 {
            color: #0b4b3f;
            margin: 0;
            font-size: 20px;
        }
        
        .header p {
            margin: 3px 0;
            color: #666;
            font-size: 10px;
        }
        
        .period-info {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        
        .period-info .left,
        .period-info .right {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        
        .period-info .right {
            text-align: right;
        }
        
        .summary-stats {
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
            display: table;
            width: 100%;
        }
        
        .stat-item {
            display: table-cell;
            text-align: center;
            padding: 0 10px;
            border-right: 1px solid #ddd;
        }
        
        .stat-item:last-child {
            border-right: none;
        }
        
        .stat-value {
            font-size: 14px;
            font-weight: bold;
            color: #0b4b3f;
            margin-bottom: 3px;
        }
        
        .stat-label {
            font-size: 9px;
            color: #666;
            text-transform: uppercase;
        }
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 9px;
        }
        
        .data-table th {
            background-color: #0b4b3f;
            color: white;
            padding: 8px 5px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #0b4b3f;
        }
        
        .data-table td {
            padding: 6px 5px;
            border: 1px solid #ddd;
            vertical-align: top;
        }
        
        .data-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .data-table tr:hover {
            background-color: #e8f4f8;
        }
        
        .status {
            padding: 2px 6px;
            border-radius: 3px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 8px;
            white-space: nowrap;
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
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .amount {
            font-family: monospace;
            text-align: right;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 9px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        
        .company-name {
            font-weight: bold;
            color: #0b4b3f;
        }
        
        .bill-number {
            font-family: monospace;
            color: #333;
        }
        
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN INVOICE RETRIBUSI</h1>
        <p>Dinas Lingkungan Hidup Kota Madiun</p>
        <p>Jl. Salak III No.7a, Taman, Kec. Taman, Kota Madiun | Telp: 0351 - 468876</p>
    </div>

    <div class="period-info">
        <div class="left">
            <h3 style="margin: 0; color: #0b4b3f;">Periode Laporan</h3>
            <p><strong>Tahun:</strong> {{ $year }}</p>
            @if($month)
                <p><strong>Bulan:</strong> {{ $month_name }}</p>
            @else
                <p><strong>Bulan:</strong> Semua Bulan</p>
            @endif
            @if($company)
                <p><strong>Perusahaan:</strong> {{ $company->name }}</p>
            @else
                <p><strong>Perusahaan:</strong> Semua Perusahaan</p>
            @endif
        </div>
        <div class="right">
            <h3 style="margin: 0; color: #0b4b3f;">Info Laporan</h3>
            <p><strong>Total Data:</strong> {{ $summary['total_count'] }} Invoice</p>
            <p><strong>Dibuat:</strong> {{ $generated_at }}</p>
        </div>
    </div>

    <div class="summary-stats">
        <div class="stat-item">
            <div class="stat-value">{{ number_format($summary['total_count']) }}</div>
            <div class="stat-label">Total Invoice</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">{{ number_format($summary['paid_count']) }}</div>
            <div class="stat-label">Sudah Lunas</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">{{ number_format($summary['unpaid_count']) }}</div>
            <div class="stat-label">Belum Lunas</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">Rp {{ number_format($summary['total_amount'], 0, ',', '.') }}</div>
            <div class="stat-label">Total Tagihan</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">Rp {{ number_format($summary['total_paid'], 0, ',', '.') }}</div>
            <div class="stat-label">Total Terbayar</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">Rp {{ number_format($summary['total_outstanding'], 0, ',', '.') }}</div>
            <div class="stat-label">Sisa Tagihan</div>
        </div>
    </div>

    <h3 style="color: #0b4b3f; border-bottom: 1px solid #ddd; padding-bottom: 5px;">Detail Invoice</h3>
    
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 8%;">No</th>
                <th style="width: 15%;">Invoice #</th>
                <th style="width: 20%;">Perusahaan</th>
                <th style="width: 12%;">Jumlah</th>
                <th style="width: 12%;">Terbayar</th>
                <th style="width: 12%;">Sisa</th>
                <th style="width: 8%;">Status</th>
                <th style="width: 13%;">Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bills as $index => $bill)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <span class="bill-number">{{ $bill->bill_number ?? 'BILL-' . $bill->id }}</span>
                    </td>
                    <td>
                        <span class="company-name">{{ optional($bill->company)->name ?? 'N/A' }}</span>
                        @if($bill->company && $bill->company->code)
                            <br><small style="color: #666;">{{ $bill->company->code }}</small>
                        @endif
                    </td>
                    <td class="amount">Rp {{ number_format($bill->amount ?? 0, 0, ',', '.') }}</td>
                    <td class="amount">Rp {{ number_format($bill->paid_amount ?? 0, 0, ',', '.') }}</td>
                    <td class="amount">Rp {{ number_format(($bill->amount ?? 0) - ($bill->paid_amount ?? 0), 0, ',', '.') }}</td>
                    <td class="text-center">
                        <span class="status {{ $bill->status === 'paid' ? 'paid' : ($bill->status === 'partial' ? 'partial' : 'unpaid') }}">
                            {{ $bill->status === 'paid' ? 'LUNAS' : ($bill->status === 'partial' ? 'SEBAGIAN' : 'BELUM LUNAS') }}
                        </span>
                    </td>
                    <td class="text-center">
                        {{ $bill->issued_at ? \Carbon\Carbon::parse($bill->issued_at)->format('d/m/Y') : ($bill->created_at ? $bill->created_at->format('d/m/Y') : 'N/A') }}
                        @if($bill->due_date)
                            <br><small style="color: #666;">Jatuh Tempo: {{ \Carbon\Carbon::parse($bill->due_date)->format('d/m/Y') }}</small>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p><strong>Laporan Invoice Retribusi - Dinas Lingkungan Hidup Kota Madiun</strong></p>
        <p>Dokumen ini dibuat secara otomatis oleh Sistem E-Retribusi pada {{ $generated_at }}</p>
        @if($summary['total_count'] > 0)
            <p>Rekapitulasi: {{ $summary['paid_count'] }} dari {{ $summary['total_count'] }} invoice sudah lunas ({{ round(($summary['paid_count'] / $summary['total_count']) * 100, 1) }}%)</p>
        @endif
    </div>
</body>
</html>