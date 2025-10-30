@extends('layouts.app')

@section('page-title', __('Perusahaan'))

@section('header')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-base-content">{{ __('Perusahaan') }}</h1>
            <p class="text-base-content/70 mt-1">{{ __('Kelola data perusahaan Anda di sini.') }}</p>
        </div>
    <a href="{{ route('users.company.create') }}" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 mr-2"><path d="M12 4.5a.75.75 0 01.75.75V11h5.75a.75.75 0 010 1.5H12.75v5.75a.75.75 0 01-1.5 0V12.5H5.5a.75.75 0 010-1.5h5.75V5.25A.75.75 0 0112 4.5z"/></svg>
            <span class="hidden sm:inline">{{ __('Tambah Perusahaan') }}</span>
            <span class="sm:hidden">{{ __('Tambah') }}</span>
        </a>
    </div>
@endsection

@section('content')
    <div class="bg-base-100 rounded-xl shadow-sm">
        <div class="p-4 md:p-6">
            <div class="flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between mb-4">
                <div class="relative">
                    <form method="GET" action="{{ url()->current() }}">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="{{ __('Cari perusahaan...') }}" class="input input-bordered w-full sm:w-80 pr-10" />
                        <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 text-base-content/60 hover:text-base-content" aria-label="{{ __('Cari') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 103.75 10.5a7.5 7.5 0 0012.9 6.15z" /></svg>
                        </button>
                    </form>
                </div>
                <div class="flex items-center gap-2 text-sm text-base-content/60">
                    <span>{{ __('Total') }}:</span>
                    <span class="badge badge-outline">{{ isset($companies) ? $companies->total() : 0 }}</span>
                </div>
            </div>

            @if(isset($companies) && $companies->count())
                <div class="overflow-x-auto">
                    <table class="table table-zebra">
                        <thead class="bg-base-200">
                            <tr>
                                <th>{{ __('Nama') }}</th>
                                <th class="hidden md:table-cell">{{ __('Badan Usaha') }}</th>
                                <th class="hidden md:table-cell">{{ __('Jenis Usaha') }}</th>
                                <th class="hidden md:table-cell">{{ __('Telepon') }}</th>
                                <th class="hidden lg:table-cell">{{ __('Email') }}</th>
                                <th class="w-0"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($companies as $company)
                                <tr>
                                    <td>
                                        <div class="font-medium">{{ $company->name }}</div>
                                        <div class="text-xs text-base-content/60 md:hidden">
                                            {{ $company->phone ?? '—' }} • {{ $company->email ?? '—' }}
                                        </div>
                                    </td>
                                    <td class="hidden md:table-cell">
                                        @if($company->badan_usaha)
                                            <span class="badge badge-ghost">{{ $company->badan_usaha }}</span>
                                        @else
                                            <span class="text-base-content/40">—</span>
                                        @endif
                                    </td>
                                    <td class="hidden md:table-cell">
                                        @if($company->jenis_usaha)
                                            <span class="badge badge-outline">{{ $company->jenis_usaha }}</span>
                                        @else
                                            <span class="text-base-content/40">—</span>
                                        @endif
                                    </td>
                                    <td class="hidden md:table-cell">{{ $company->phone ?? '—' }}</td>
                                    <td class="hidden lg:table-cell">
                                        @if($company->email)
                                            <a href="mailto:{{ $company->email }}" class="link link-hover">{{ $company->email }}</a>
                                        @else
                                            <span class="text-base-content/40">—</span>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        <div class="join join-horizontal">
                                            <a href="{{ route('users.company.show', $company) }}" class="btn btn-ghost btn-sm join-item">{{ __('Lihat') }}</a>
                                            <a href="{{ route('users.company.edit', $company) }}" class="btn btn-ghost btn-sm join-item">{{ __('Edit') }}</a>
                                            <form action="{{ route('users.company.destroy', $company) }}" method="POST" onsubmit="return confirm('{{ __('Hapus perusahaan ini?') }}')" class="join-item">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-ghost btn-sm text-error">{{ __('Hapus') }}</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if(method_exists($companies, 'links'))
                    <div class="mt-4">{{ $companies->links() }}</div>
                @endif
            @else
                <div class="text-center py-16">
                    <div class="mx-auto w-16 h-16 rounded-full bg-base-200 flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-8 h-8 text-base-content/50"><path d="M3.75 4.875c0-1.036.84-1.875 1.875-1.875h12.75c1.036 0 1.875.84 1.875 1.875V19.5a.75.75 0 01-1.125.65l-4.5-2.571-4.5 2.571A.75.75 0 018.25 19.5V6a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5H10v9.403l3.75-2.143a.75.75 0 01.75 0L18.25 16.5V4.875a.375.375 0 00-.375-.375H5.625a.375.375 0 00-.375.375V18a.75.75 0 01-1.5 0V4.875z" /></svg>
                    </div>
                    <h3 class="text-lg font-medium">{{ __('Belum ada perusahaan') }}</h3>
                    <p class="text-base-content/60 mt-1">{{ __('Mulai dengan menambahkan data perusahaan pertama Anda.') }}</p>
                    <a href="{{ route('users.company.create') }}" class="btn btn-primary mt-6">{{ __('Tambah Perusahaan') }}</a>
                </div>
            @endif
        </div>
    </div>
@endsection