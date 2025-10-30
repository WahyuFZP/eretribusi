@extends('layouts.app')

@section('page-title', $company->name)

@section('header')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-base-content">{{ $company->name }}</h1>
            <p class="text-base-content/70 mt-1">{{ __('Detail perusahaan') }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('users.company.edit', $company) }}" class="btn btn-ghost">{{ __('Edit') }}</a>
            <a href="{{ route('users.company.index') }}" class="btn btn-ghost">{{ __('Kembali') }}</a>
        </div>
    </div>
@endsection

@section('content')
    <div class="bg-base-100 rounded-xl shadow-sm">
        <div class="p-4 md:p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <div class="text-sm text-base-content/60">{{ __('Badan Usaha') }}</div>
                    <div class="font-medium">{{ $company->badan_usaha ?? '—' }}</div>
                </div>
                <div>
                    <div class="text-sm text-base-content/60">{{ __('Jenis Usaha') }}</div>
                    <div class="font-medium">{{ $company->jenis_usaha ?? '—' }}</div>
                </div>
                <div>
                    <div class="text-sm text-base-content/60">{{ __('Email') }}</div>
                    <div class="font-medium">
                        @if($company->email)
                            <a href="mailto:{{ $company->email }}" class="link link-hover">{{ $company->email }}</a>
                        @else
                            <span class="text-base-content/40">—</span>
                        @endif
                    </div>
                </div>
                <div>
                    <div class="text-sm text-base-content/60">{{ __('Telepon') }}</div>
                    <div class="font-medium">{{ $company->phone ?? '—' }}</div>
                </div>
                <div class="md:col-span-2">
                    <div class="text-sm text-base-content/60">{{ __('Alamat') }}</div>
                    <div class="font-medium">{{ $company->address ?? '—' }}</div>
                </div>
            </div>

            <div class="mt-8 flex items-center justify-between">
                <div class="text-xs text-base-content/60">
                    <span>{{ __('Dibuat') }}: {{ optional($company->created_at)->format('d M Y, H:i') }}</span>
                    <span class="mx-2">•</span>
                    <span>{{ __('Diubah') }}: {{ optional($company->updated_at)->format('d M Y, H:i') }}</span>
                </div>
                <form action="{{ route('users.company.destroy', $company) }}" method="POST" onsubmit="return confirm('{{ __('Hapus perusahaan ini?') }}')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-ghost text-error">{{ __('Hapus') }}</button>
                </form>
            </div>
        </div>
    </div>
@endsection