@extends('layouts.app')

@section('content')
	<div class="container mx-auto p-6">
		<div class="flex items-center justify-between mb-4">
			<h1 class="text-2xl font-bold">Companies</h1>
			<a href="{{ route('admin.company.create') }}" class="btn btn-primary">Create Company</a>
		</div>

		@if (session('success'))
			<div class="alert alert-success mb-4">{{ session('success') }}</div>
		@endif

		<div class="overflow-x-auto">
			<table class="table table-zebra w-full">
				<thead>
					<tr class="bg-base-200 text-base-content/70">
						<th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide">No</th>
						<th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide">Name</th>
						<th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide">Code</th>
						<th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide">Owner</th>
						<th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide">Email</th>
						<th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide">Actions</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($companies as $company)
						<tr>
							<td>{{ $loop->iteration + ($companies->currentPage() - 1) * $companies->perPage() }}</td>
							<td>{{ $company->name }}</td>
							<td>{{ $company->code ?? '-' }}</td>
							<td>{{ optional($company->user)->name ?? '-' }}</td>
							<td>{{ $company->email ?? '-' }}</td>
							<td>
								<a href="{{ route('admin.company.show', $company) }}" class="btn btn-sm btn-outline mr-2">View</a>
								<a href="{{ route('admin.company.edit', $company) }}" class="btn btn-sm btn-outline mr-2">Edit</a>
								<form action="{{ route('admin.company.destroy', $company) }}" method="POST" class="inline-block"
									onsubmit="return confirm('Delete company?')">
									@csrf
									@method('DELETE')
									<button class="btn btn-sm btn-error btn-outline">Delete</button>
								</form>
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>

		<div class="mt-4 flex items-center justify-between">
			<div class="text-sm text-gray-600">
				Showing {{ $companies->firstItem() ?? 0 }} to {{ $companies->lastItem() ?? 0 }} of {{ $companies->total() }} companies
			</div>
			<div>
				{{ $companies->links() }}
			</div>
		</div>
	</div>
@endsection

