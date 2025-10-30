@extends('layouts.app')

@section('header')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-base-content">{{ __('Dashboard') }}</h1>
            <p class="text-base-content/70">{{ __('Welcome back to e-Retribusi system') }} {{ Auth::user()->name }}</p>
        </div>
        <div class="text-sm text-base-content/60">
            {{ now()->format('l, d F Y') }}
        </div>
    </div>
@endsection

@section('content')

    <!-- Dashboard Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Wajib Pajak -->
        <div class="stats shadow">
            <div class="stat">
                <div class="stat-figure text-primary">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"></path>
                    </svg>
                </div>
                <div class="stat-title">Total Wajib Pajak</div>
                <div class="stat-value text-primary">1,234</div>
                <div class="stat-desc">↗︎ 5% (30 days)</div>
            </div>
        </div>

        <!-- Pembayaran Hari Ini -->
        <div class="stats shadow">
            <div class="stat">
                <div class="stat-figure text-success">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <div class="stat-title">Pembayaran Hari Ini</div>
                <div class="stat-value text-success">Rp 5.2M</div>
                <div class="stat-desc">↗︎ 12% dari kemarin</div>
            </div>
        </div>

        <!-- Tagihan Pending -->
        <div class="stats shadow">
            <div class="stat">
                <div class="stat-figure text-warning">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div class="stat-title">Tagihan Pending</div>
                <div class="stat-value text-warning">89</div>
                <div class="stat-desc">Perlu ditindaklanjuti</div>
            </div>
        </div>

        <!-- Tunggakan -->
        <div class="stats shadow">
            <div class="stat">
                <div class="stat-figure text-error">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="stat-title">Total Tunggakan</div>
                <div class="stat-value text-error">Rp 2.1M</div>
                <div class="stat-desc">↘︎ 3% dari bulan lalu</div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Payments -->
        <div class="lg:col-span-2">
            <div class="card bg-base-100 shadow">
                <div class="card-body">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="card-title">{{ __('Recent Payments') }}</h3>
                        <a href="#" class="btn btn-ghost btn-sm">{{ __('View All') }}</a>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="table table-zebra">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Jenis Retribusi</th>
                                    <th>Jumlah</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="font-medium">PT. Maju Jaya</div>
                                        <div class="text-sm text-gray-500">ID: WP001234</div>
                                    </td>
                                    <td>Retribusi Pasar</td>
                                    <td>Rp 250,000</td>
                                    <td><span class="badge badge-success">Paid</span></td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="font-medium">CV. Berkah</div>
                                        <div class="text-sm text-gray-500">ID: WP001235</div>
                                    </td>
                                    <td>Retribusi Parkir</td>
                                    <td>Rp 150,000</td>
                                    <td><span class="badge badge-warning">Pending</span></td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="font-medium">UD. Sentosa</div>
                                        <div class="text-sm text-gray-500">ID: WP001236</div>
                                    </td>
                                    <td>Retribusi Kebersihan</td>
                                    <td>Rp 100,000</td>
                                    <td><span class="badge badge-success">Paid</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="space-y-6">
            <div class="card bg-base-100 shadow">
                <div class="card-body">
                    <h3 class="card-title">{{ __('Quick Actions') }}</h3>
                    <div class="space-y-2">
                        <button class="btn btn-primary btn-block">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            {{ __('Add New Payment') }}
                        </button>
                        <button class="btn btn-outline btn-block">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"></path>
                            </svg>
                            {{ __('Register Taxpayer') }}
                        </button>
                        <button class="btn btn-outline btn-block">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            {{ __('Generate Report') }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- System Status -->
            <div class="card bg-base-100 shadow">
                <div class="card-body">
                    <h3 class="card-title">{{ __('System Status') }}</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span>Database</span>
                            <span class="badge badge-success">Online</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span>Payment Gateway</span>
                            <span class="badge badge-success">Active</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span>Backup Status</span>
                            <span class="badge badge-info">Last: 2h ago</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
