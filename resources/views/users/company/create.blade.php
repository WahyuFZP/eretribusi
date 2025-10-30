@extends('layouts.app')

@section('page-title', __('Tambah Perusahaan'))

@section('header')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-base-content">{{ __('Tambah Perusahaan') }}</h1>
            <p class="text-base-content/70 mt-1">{{ __('Lengkapi informasi perusahaan Anda.') }}</p>
        </div>
    <a href="{{ route('users.company.index') }}" class="btn btn-ghost">{{ __('Batal') }}</a>
    </div>
@endsection

@section('content')
    <div class="bg-base-100 rounded-xl shadow-sm">
        <div class="p-4 md:p-6">
            <form method="POST" action="{{ route('users.company.store') }}" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label"><span class="label-text">{{ __('Nama Perusahaan') }}</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" class="input input-bordered w-full" required />
                        @error('name')<div class="text-error text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text">{{ __('Badan Usaha') }}</span></label>
                        <select name="badan_usaha" class="select select-bordered w-full">
                            <option value="" @selected(old('badan_usaha')==='')>—</option>
                            <option value="PT" @selected(old('badan_usaha')==='PT')>PT</option>
                            <option value="CV" @selected(old('badan_usaha')==='CV')>CV</option>
                            <option value="UD" @selected(old('badan_usaha')==='UD')>UD</option>
                            <option value="Firma" @selected(old('badan_usaha')==='Firma')>Firma</option>
                            <option value="Koperasi" @selected(old('badan_usaha')==='Koperasi')>Koperasi</option>
                            <option value="Yayasan" @selected(old('badan_usaha')==='Yayasan')>Yayasan</option>
                            <option value="Perorangan" @selected(old('badan_usaha')==='Perorangan')>Perorangan</option>
                        </select>
                        @error('badan_usaha')<div class="text-error text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text">{{ __('Jenis Usaha') }}</span></label>
                        <input type="text" name="jenis_usaha" value="{{ old('jenis_usaha') }}" class="input input-bordered w-full" />
                        @error('jenis_usaha')<div class="text-error text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text">{{ __('Email') }}</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" class="input input-bordered w-full" />
                        @error('email')<div class="text-error text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text">{{ __('Telepon') }}</span></label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="input input-bordered w-full" />
                        @error('phone')<div class="text-error text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-control md:col-span-2">
                        <label class="label"><span class="label-text">{{ __('Alamat') }}</span></label>
                        <textarea name="address" class="textarea textarea-bordered w-full" rows="3">{{ old('address') }}</textarea>
                        @error('address')<div class="text-error text-sm mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('users.company.index') }}" class="btn btn-ghost">{{ __('Batal') }}</a>
                    <button type="submit" class="btn btn-primary">{{ __('Simpan') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
