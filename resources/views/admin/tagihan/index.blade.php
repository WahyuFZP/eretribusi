@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
	<div class="card bg-base-100 shadow">
		<div class="card-body">
			<div class="flex items-start justify-between gap-4">
				<div>
					<h2 class="card-title">Tagihan</h2>
					<p class="text-sm text-gray-500">Daftar invoice/tagihan untuk perusahaan. Gunakan filter untuk menemukan tagihan.</p>
				</div>

				<div class="flex items-center gap-2">
					@php $createUrlTop = \Illuminate\Support\Facades\Route::has('admin.tagihan.create') ? route('admin.tagihan.create') : '#'; @endphp
					<a href="{{ $createUrlTop }}" class="btn btn-primary">Buat Tagihan</a>
				</div>
			</div>

				<div class="mt-4">
				<form method="get" action="{{ request()->url() }}" class="grid grid-cols-1 md:grid-cols-4 gap-2 mb-4">
					<input type="search" name="q" value="{{ request('q') }}" placeholder="Cari invoice_number atau company..." class="input input-bordered w-full" />

					<select name="company_id" class="select select-bordered w-full">
						<option value="">Semua Perusahaan</option>
						@if(isset($companies))
							@foreach($companies as $company)
								<option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
							@endforeach
						@endif
					</select>

					<select name="status" class="select select-bordered w-full">
						<option value="">Semua Status</option>
						<option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Belum Lunas</option>
						<option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>Sebagian</option>
						<option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Lunas</option>
					</select>

					<div class="flex gap-2">
						<button type="submit" class="btn btn-outline">Filter</button>
						<a href="{{ request()->url() }}" class="btn">Reset</a>
					</div>
				</form>

				@if(isset($companies) && $companies->count())
				<div class="overflow-x-auto">
					<table class="table w-full">
						<thead>
							<tr>
								<th>Perusahaan</th>
								<th>Email</th>
								<th>Kode</th>
								<th class="text-right">Tagihan Aktif</th>
								<th>Aksi</th>
							</tr>
						</thead>
						<tbody>
							@foreach($companies as $company)
							<tr class="hover">
								<td>{{ $company->name }}</td>
								<td>{{ $company->email ?? '-' }}</td>
								<td class="font-mono">{{ $company->code ?? '-' }}</td>
								<td class="text-right">{{ number_format(optional($company->invoices())->where('status','<>','paid')->sum('amount') ?? 0, 0, ',', '.') }}</td>
								<td>
									<div class="flex gap-2">
										@php
											$createCompanyUrl = \Illuminate\Support\Facades\Route::has('admin.tagihan.create') ? route('admin.tagihan.create', ['company_id' => $company->id]) : '#';
											$companyShowUrl = \Illuminate\Support\Facades\Route::has('companies.show') ? route('companies.show', $company) : '#';
										@endphp
										<a href="{{ $createCompanyUrl }}" class="btn btn-sm">Buat Tagihan</a>
										<a href="{{ $companyShowUrl }}" class="btn btn-sm btn-ghost">Perusahaan</a>
									</div>
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
				@else
				<div class="overflow-x-auto">
					<table class="table w-full">
						<thead>
							<tr>
								<th>Invoice #</th>
								<th>Perusahaan</th>
								<th>Keterangan</th>
								<th class="text-right">Jumlah</th>
								<th class="text-right">Terbayar</th>
								<th>Status</th>
								<th>Jatuh Tempo</th>
								<th>Aksi</th>
							</tr>
						</thead>
						<tbody>
							@php
								// support both $bills (Bill model) and legacy $invoices
								$paginator = $bills ?? $invoices ?? null;
								$items = $paginator ?? collect();
							@endphp
							@forelse($items as $item)
							<tr class="hover">
								{{-- invoice_number or number or fallback to id --}}
								<td class="font-mono">{{ $item->invoice_number ?? $item->number ?? ('#' . $item->id) }}</td>
								<td>{{ optional($item->company)->name ?? ($item->company_name ?? '-') }}</td>
								<td class="w-1/3">{{ $item->description ?? $item->notes ?? '-' }}</td>
								<td class="text-right">Rp {{ number_format($item->amount ?? 0, 0, ',', '.') }}</td>
								<td class="text-right">Rp {{ number_format($item->paid_amount ?? 0, 0, ',', '.') }}</td>
								<td>
									@php $s = strtolower($item->status ?? 'unpaid'); @endphp
									@if($s === 'paid')
										<span class="badge badge-success">LUNAS</span>
									@elseif($s === 'partial')
										<span class="badge badge-warning">SEBAGIAN</span>
									@else
										<span class="badge badge-error">BELUM LUNAS</span>
									@endif
								</td>
								<td>{{ optional($item->due_date)?->format('Y-m-d') ?? '-' }}</td>
								<td>
									<div class="flex gap-2">
										@if(
											\Illuminate\Support\Facades\Route::has('admin.tagihan.show')
											|| \Illuminate\Support\Facades\Route::has('bills.show')
										)
											@php
												if(\Illuminate\Support\Facades\Route::has('admin.tagihan.show')){
													$showRoute = ['admin.tagihan.show', $item];
												} elseif(\Illuminate\Support\Facades\Route::has('bills.show')){
													$showRoute = ['bills.show', $item];
												} else {
													$showRoute = null;
												}
											@endphp
											@if($showRoute)
												@php
													$showUrl = \Illuminate\Support\Facades\Route::has($showRoute[0]) ? route($showRoute[0], $showRoute[1]) : '#';
												@endphp
												<a href="{{ $showUrl }}" class="btn btn-sm btn-ghost">Lihat</a>
											@else
												<a href="#" class="btn btn-sm btn-ghost">Lihat</a>
											@endif
										@else
											<a href="#" class="btn btn-sm btn-ghost">Lihat</a>
										@endif

										@if(\Illuminate\Support\Facades\Route::has('admin.tagihan.send') || \Illuminate\Support\Facades\Route::has('bills.send'))
											@php
												$sendName = \Illuminate\Support\Facades\Route::has('admin.tagihan.send') ? 'admin.tagihan.send' : 'bills.send';
												$sendUrl = \Illuminate\Support\Facades\Route::has($sendName) ? route($sendName, $item) : '#';
											@endphp
											<form action="{{ $sendUrl }}" method="post" onsubmit="return confirm('Kirim invoice ke perusahaan ini?');">
												@csrf
												<button type="submit" class="btn btn-sm btn-outline">Kirim</button>
											</form>
										@endif

										@if(($item->status ?? 'unpaid') !== 'paid' && (\Illuminate\Support\Facades\Route::has('admin.tagihan.markPaid') || \Illuminate\Support\Facades\Route::has('bills.markPaid')))
											@php
												$markName = \Illuminate\Support\Facades\Route::has('admin.tagihan.markPaid') ? 'admin.tagihan.markPaid' : 'bills.markPaid';
												$markUrl = \Illuminate\Support\Facades\Route::has($markName) ? route($markName, $item) : '#';
											@endphp
											<form action="{{ $markUrl }}" method="post" onsubmit="return confirm('Tandai invoice sebagai lunas?');">
												@csrf
												<button type="submit" class="btn btn-sm btn-success">Tandai Lunas</button>
											</form>
										@endif
									</div>
								</td>
							</tr>
							@empty
							<tr>
								<td colspan="8" class="text-center py-8 text-gray-500">Belum ada tagihan. Klik "Buat Tagihan" untuk menambahkan.</td>
							</tr>
							@endforelse
						</tbody>
					</table>
				</div>

				@if($paginator && method_exists($paginator, 'withQueryString'))
				<div class="mt-4">
					{{ $paginator->withQueryString()->links() }}
				</div>
				@endif
				@endif
			</div>
		</div>
	</div>
</div>
@endsection