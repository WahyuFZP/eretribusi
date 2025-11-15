@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-2xl font-bold">{{ isset($company) ? 'Edit Company' : 'Create Company' }}</h1>
            <a href="{{ route('admin.company.index') }}" class="btn">Back</a>
        </div>

        @if ($errors->any())
            <div class="alert alert-error mb-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ isset($company) ? route('admin.company.update', $company) : route('admin.company.store') }}" method="POST">
            @csrf
            @if (isset($company))
                @method('PATCH')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="label">Name</label>
                    <input type="text" name="name" class="input input-bordered w-full" value="{{ old('name', $company->name ?? '') }}" required>
                </div>
                <div>
                    <label class="label">Email</label>
                    <input type="email" name="email" class="input input-bordered w-full" value="{{ old('email', $company->email ?? '') }}">
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">{{ __('Badan Usaha') }}</span>
                    </label>
                    <select name="badan_usaha" class="select select-bordered w-full">
                        <option value="-"@selected(old('badan_usaha', $company->badan_usaha ?? '') == '')>-</option>
                        <option value="PT"@selected(old('badan_usaha') == 'PT')>PT</option>
                        <option value="CV"@selected(old('badan_usaha') == 'CV')>CV</option>
                        <option value="UD"@selected(old('badan_usaha') == 'UD')>UD</option>
                        <option value="FIRMA"@selected(old('badan_usaha') == 'FIRMA')>FIRMA</option>
                        <option value="KOPERASI"@selected(old('badan_usaha') == 'KOPERASI')>KOPERASI</option>
                        <option value="YAYASAN"@selected(old('badan_usaha') == 'YAYASAN')>YAYASAN</option>
                        <option value="PERORANGAN"@selected(old('badan_usaha') == 'PERORANGAN')>PERORANGAN</option>
                    </select>
                </div>
                <div>
                    <label class="label">Phone</label>
                    <input type="text" name="phone" class="input input-bordered w-full" value="{{ old('phone', $company->phone ?? '') }}">
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">{{ __('Jenis Usaha') }}</span></label>
                    <input type="text" name="jenis_usaha" value="{{ old('jenis_usaha') }}" class="input input-bordered w-full">
                </div>
                <div>
                    <label class="label">Owner (user)</label>
                    <select name="user_id" class="select select-bordered w-full">
                        <option value="">-- none --</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ (old('user_id', $company->user_id ?? '') == $user->id) ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="label">Address</label>
                    <textarea name="address" class="textarea textarea-bordered w-full">{{ old('address', $company->address ?? '') }}</textarea>
                </div>
            </div>

            <div class="mt-4">
                <button class="btn btn-primary">{{ isset($company) ? 'Update' : 'Create' }}</button>
            </div>
        </form>
    </div>
@endsection
