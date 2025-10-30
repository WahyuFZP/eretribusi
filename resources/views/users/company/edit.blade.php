@extends('layouts.app')

@section('page-title', __('Edit Perusahaan'))

@section('header')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-base-content">{{ __('Edit Perusahaan') }}</h1>
            <p class="text-base-content/70 mt-1">{{ __('Perbarui informasi perusahaan Anda.') }}</p>
        </div>
    <a href="{{ route('users.company.show', $company) }}" class="btn btn-ghost">{{ __('Kembali') }}</a>
    </div>
@endsection

@section('content')
    <div class="bg-base-100 rounded-xl shadow-sm">
        <div class="p-4 md:p-6">
            <form method="POST" action="{{ route('users.company.update', $company) }}" class="space-y-6">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label"><span class="label-text">{{ __('Nama Perusahaan') }}</span></label>
                        <input type="text" name="name" value="{{ old('name', $company->name) }}" class="input input-bordered w-full" required />
                        @error('name')<div class="text-error text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text">{{ __('Badan Usaha') }}</span></label>
                        <select name="badan_usaha" class="select select-bordered w-full">
                            @php($bu = old('badan_usaha', $company->badan_usaha))
                            <option value="" @selected($bu==='')>—</option>
                            <option value="PT" @selected($bu==='PT')>PT</option>
                            <option value="CV" @selected($bu==='CV')>CV</option>
                            <option value="UD" @selected($bu==='UD')>UD</option>
                            <option value="Firma" @selected($bu==='Firma')>Firma</option>
                            <option value="Koperasi" @selected($bu==='Koperasi')>Koperasi</option>
                            <option value="Yayasan" @selected($bu==='Yayasan')>Yayasan</option>
                            <option value="Perorangan" @selected($bu==='Perorangan')>Perorangan</option>
                        </select>
                        @error('badan_usaha')<div class="text-error text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text">{{ __('Jenis Usaha') }}</span></label>
                        <input type="text" name="jenis_usaha" value="{{ old('jenis_usaha', $company->jenis_usaha) }}" class="input input-bordered w-full" />
                        @error('jenis_usaha')<div class="text-error text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text">{{ __('Email') }}</span></label>
                        <input type="email" name="email" value="{{ old('email', $company->email) }}" class="input input-bordered w-full" />
                        @error('email')<div class="text-error text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text">{{ __('Telepon') }}</span></label>
                        <input type="text" name="phone" value="{{ old('phone', $company->phone) }}" class="input input-bordered w-full" />
                        @error('phone')<div class="text-error text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-control md:col-span-2">
                        <label class="label"><span class="label-text">{{ __('Alamat') }}</span></label>
                        <textarea name="address" class="textarea textarea-bordered w-full" rows="3">{{ old('address', $company->address) }}</textarea>
                        @error('address')<div class="text-error text-sm mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('users.company.show', $company) }}" class="btn btn-ghost">{{ __('Batal') }}</a>
                    <button type="submit" class="btn btn-primary">{{ __('Simpan Perubahan') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
