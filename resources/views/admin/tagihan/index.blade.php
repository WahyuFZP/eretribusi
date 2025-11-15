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
					@if(Route::has('admin.tagihan.create'))
						<a href="{{ route('admin.tagihan.create') }}" class="btn btn-primary">Buat Tagihan</a>
					@else
						<button class="btn btn-primary" disabled>Buat Tagihan</button>
					@endif
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
										<td class="text-right">{{ number_format($company->active_bills_sum ?? 0, 0, ',', '.') }}</td>
										<td>
											<div class="flex gap-2">	
												@if(Route::has('admin.tagihan.create'))
													<a href="{{ route('admin.tagihan.create', ['company_id' => $company->id]) }}" class="btn btn-sm">Buat Tagihan</a>
												@else
													<button class="btn btn-sm" disabled>Buat Tagihan</button>
												@endif

												@if(Route::has('companies.show'))
													<a href="{{ route('companies.show', $company) }}" class="btn btn-sm btn-ghost">Perusahaan</a>
												@else
													<a href="#" class="btn btn-sm btn-ghost">Perusahaan</a>
												@endif
											</div>
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				@else
					{{-- Show bills/invoices listing --}}
					{{-- <div class="overflow-x-auto">
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
									$paginator = $bills ?? $invoices ?? null;
									$items = $paginator ?? collect();
								@endphp

								@forelse($items as $item)
									<tr class="hover">
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
												@if(Route::has('admin.tagihan.show'))
													<a href="{{ route('admin.tagihan.show', $item) }}" class="btn btn-sm btn-ghost">Lihat</a>
												@elseif(Route::has('bills.show'))
													<a href="{{ route('bills.show', $item) }}" class="btn btn-sm btn-ghost">Lihat</a>
												@else
													<a href="#" class="btn btn-sm btn-ghost">Lihat</a>
												@endif

												@if(Route::has('admin.tagihan.send'))
													<form action="{{ route('admin.tagihan.send', $item) }}" method="post" onsubmit="return confirm('Kirim invoice ke perusahaan ini?');">
														@csrf
														<button type="submit" class="btn btn-sm btn-outline">Kirim</button>
													</form>
												@elseif(Route::has('bills.send'))
													<form action="{{ route('bills.send', $item) }}" method="post" onsubmit="return confirm('Kirim invoice ke perusahaan ini?');">
														@csrf
														<button type="submit" class="btn btn-sm btn-outline">Kirim</button>
													</form>
												@endif

												@if(($item->status ?? 'unpaid') !== 'paid')
													@if(Route::has('admin.tagihan.markPaid'))
														<form action="{{ route('admin.tagihan.markPaid', $item) }}" method="post" onsubmit="return confirm('Tandai invoice sebagai lunas?');">
															@csrf
															<button type="submit" class="btn btn-sm btn-success">Tandai Lunas</button>
														</form>
													@elseif(Route::has('bills.markPaid'))
														<form action="{{ route('bills.markPaid', $item) }}" method="post" onsubmit="return confirm('Tandai invoice sebagai lunas?');">
															@csrf
															<button type="submit" class="btn btn-sm btn-success">Tandai Lunas</button>
														</form>
													@endif
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
					</div> --}}

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