@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-4">
        <div class="card bg-base-100 shadow">
            <div class="card-body">
                <h2 class="card-title">Bulk Export Invoices</h2>
                <p class="text-sm text-gray-500">Export semua invoice dalam format PDF berdasarkan filter periode</p>

                <form method="POST" action="{{ route('admin.bills.bulk-export') }}" class="mt-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
                        <!-- Year Filter -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Tahun</span>
                            </label>
                            <select name="year" class="select select-bordered">
                                @foreach($years as $year)
                                    <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Month Filter -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Bulan (Opsional)</span>
                            </label>
                            <select name="month" class="select select-bordered">
                                <option value="">Semua Bulan</option>
                                <option value="1">Januari</option>
                                <option value="2">Februari</option>
                                <option value="3">Maret</option>
                                <option value="4">April</option>
                                <option value="5">Mei</option>
                                <option value="6">Juni</option>
                                <option value="7">Juli</option>
                                <option value="8">Agustus</option>
                                <option value="9">September</option>
                                <option value="10">Oktober</option>
                                <option value="11">November</option>
                                <option value="12">Desember</option>
                            </select>
                        </div>

                        <!-- Company Filter -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Perusahaan (Opsional)</span>
                            </label>
                            <select name="company_id" class="select select-bordered">
                                <option value="">Semua Perusahaan</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Bulk Export Button -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Export ZIP</span>
                            </label>
                            <button type="submit" class="btn btn-primary" formaction="{{ route('admin.bills.bulk-export') }}">
                                <i class="fas fa-download"></i>
                                ZIP Files
                            </button>
                        </div>

                        <!-- Summary Report Button -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Laporan Summary</span>
                            </label>
                            <button type="submit" class="btn btn-secondary" formaction="{{ route('admin.bills.summary-report') }}">
                                <i class="fas fa-file-pdf"></i>
                                Summary PDF
                            </button>
                        </div>
                    </div>

                    <!-- Info Box -->
                    <div class="alert alert-info">
                        <div class="flex">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current shrink-0 w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <div>
                                <h3 class="font-bold">Catatan Export</h3>
                                <div class="text-xs">
                                    <ul class="list-disc list-inside mt-2">
                                        <li><strong>ZIP Files:</strong> Export akan menghasilkan file ZIP berisi semua PDF invoice terpisah</li>
                                        <li><strong>Summary PDF:</strong> Export akan menghasilkan 1 file PDF berisi tabel laporan semua invoice</li>
                                        <li>Jika tidak memilih bulan, akan export semua bulan dalam tahun tersebut</li>
                                        <li>Jika tidak memilih perusahaan, akan export semua perusahaan</li>
                                        <li>Summary PDF cocok untuk laporan bulanan/tahunan yang ringkas</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Quick Stats -->
                <div class="stats stats-vertical lg:stats-horizontal shadow mt-6">
                    <div class="stat">
                        <div class="stat-title">Total Invoice Tahun Ini</div>
                        <div class="stat-value text-sm">{{ \App\Models\Bill::whereYear('created_at', date('Y'))->count() }}</div>
                        <div class="stat-desc">{{ date('Y') }}</div>
                    </div>
                    
                    <div class="stat">
                        <div class="stat-title">Total Invoice Bulan Ini</div>
                        <div class="stat-value text-sm">{{ \App\Models\Bill::whereYear('created_at', date('Y'))->whereMonth('created_at', date('n'))->count() }}</div>
                        <div class="stat-desc">{{ \Carbon\Carbon::now()->format('F Y') }}</div>
                    </div>
                    
                    <div class="stat">
                        <div class="stat-title">Total Perusahaan</div>
                        <div class="stat-value text-sm">{{ \App\Models\Company::count() }}</div>
                        <div class="stat-desc">Aktif</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    // Auto select current month
    document.addEventListener('DOMContentLoaded', function() {
        const monthSelect = document.querySelector('select[name="month"]');
        const currentMonth = new Date().getMonth() + 1;
        
        // Uncomment this if you want to auto-select current month
        // monthSelect.value = currentMonth;
    });
</script>
@endsection