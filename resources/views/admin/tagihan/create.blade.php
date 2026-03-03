@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <div class="card bg-base-100 shadow">
        <div class="card-body">
            <div class="flex items-center justify-between">
                <h2 class="card-title">Buat Tagihan Baru</h2>
                @php
                    $backUrl = \Illuminate\Support\Facades\Route::has('admin.tagihan.index') ? route('admin.tagihan.index') : url()->previous();
                @endphp
                <a href="{{ $backUrl }}" class="btn btn-ghost">Kembali</a>
            </div>

            <form action="{{ \Illuminate\Support\Facades\Route::has('admin.tagihan.store') ? route('admin.tagihan.store') : '#' }}"
                  method="POST" enctype="multipart/form-data" class="mt-4 space-y-4">
                @csrf

                {{-- Company selector (if $companies provided) --}}
                @php $prefillCompany = request('company_id') ?? old('company_id'); @endphp
                @if(isset($companies) && $companies->count())
                    <div>
                        <label class="label"><span class="label-text">Perusahaan</span></label>
                        <select name="company_id" class="select select-bordered w-full">
                            <option value="">Pilih Perusahaan...</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}"
                                    {{ (string) $company->id === (string) $prefillCompany ? 'selected' : '' }}>
                                    {{ $company->name }} @if($company->code) ({{ $company->code }}) @endif
                                </option>
                            @endforeach
                        </select>
                        @error('company_id') <p class="text-sm text-error mt-1">{{ $message }}</p> @enderror
                    </div>
                @else
                    {{-- If companies list not provided, allow admin to input a company name or pass company_id --}}
                    <div>
                        <label class="label"><span class="label-text">Perusahaan (ID atau Nama)</span></label>
                        <input type="text" name="company_name" value="{{ old('company_name', $prefillCompany) }}" class="input input-bordered w-full" placeholder="Masukkan ID atau Nama Perusahaan">
                        @error('company_name') <p class="text-sm text-error mt-1">{{ $message }}</p> @enderror
                    </div>
                @endif

                {{-- Bill / Invoice number --}}
                <div>
                    <label class="label"><span class="label-text">Nomor Tagihan</span></label>
                    <input type="text" name="bill_number" value="{{ old('bill_number') }}" class="input input-bordered w-full" placeholder="Otomatis jika dikosongkan">
                    @error('bill_number') <p class="text-sm text-error mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Description --}}
                <div>
                    <label class="label"><span class="label-text">Keterangan</span></label>
                    <input type="text" name="description" value="{{ old('description') }}" class="input input-bordered w-full" placeholder="Mis. Retribusi bulan Januari">
                    @error('description') <p class="text-sm text-error mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Amount and Due Date in a row --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                    <div>
                        <label class="label"><span class="label-text">Jumlah (Rp)</span></label>
                        <input type="number" name="amount" value="{{ old('amount') }}" step="0.01" min="0" class="input input-bordered w-full" placeholder="0.00">
                        @error('amount') <p class="text-sm text-error mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="label"><span class="label-text">Jatuh Tempo</span></label>
                        <input type="date" name="due_date" value="{{ old('due_date') }}" class="input input-bordered w-full">
                        @error('due_date') <p class="text-sm text-error mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Billing period and status --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                    <div>
                        <label class="label"><span class="label-text">Periode Tagihan</span></label>
                        <input type="text" name="billing_period" value="{{ old('billing_period') }}" class="input input-bordered w-full" placeholder="Mis. 2025-11">
                        @error('billing_period') <p class="text-sm text-error mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="label"><span class="label-text">Status</span></label>
                        <select name="status" class="select select-bordered w-full">
                            <option value="unpaid" {{ old('status') === 'unpaid' ? 'selected' : '' }}>Belum Lunas</option>
                            <option value="partial" {{ old('status') === 'partial' ? 'selected' : '' }}>Sebagian</option>
                            <option value="paid" {{ old('status') === 'paid' ? 'selected' : '' }}>Lunas</option>
                        </select>
                        @error('status') <p class="text-sm text-error mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Attachment / Document --}}
                <div>
                    <label class="label"><span class="label-text">Lampiran (PDF/PNG/JPG)</span></label>
                    <input type="file" name="document" class="file-input file-input-bordered w-full" accept=".pdf,image/*">
                    @error('document') <p class="text-sm text-error mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Notes --}}
                <div>
                    <label class="label"><span class="label-text">Catatan</span></label>
                    <textarea name="notes" class="textarea textarea-bordered w-full" rows="4" placeholder="Catatan tambahan...">{{ old('notes') }}</textarea>
                    @error('notes') <p class="text-sm text-error mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Recurring Settings --}}
                <div class="card bg-base-200 p-4">
                    <h3 class="font-semibold text-lg mb-4">⏰ Pengaturan Tagihan Otomatis</h3>
                    
                    <div class="form-control mb-4">
                        <label class="label cursor-pointer justify-start gap-3">
                            <input type="checkbox" name="is_recurring" value="1" class="checkbox" id="recurring_toggle" {{ old('is_recurring') ? 'checked' : '' }}>
                            <span class="label-text">Aktifkan tagihan otomatis berulang</span>
                        </label>
                    </div>

                    <div id="recurring_options" class="grid grid-cols-1 md:grid-cols-2 gap-4" style="display: {{ old('is_recurring') ? 'grid' : 'none' }}">
                        <div>
                            <label class="label"><span class="label-text">Frekuensi</span></label>
                            <select name="recurring_frequency" class="select select-bordered w-full">
                                <option value="monthly" {{ old('recurring_frequency') === 'monthly' ? 'selected' : '' }}>Bulanan</option>
                                <option value="yearly" {{ old('recurring_frequency') === 'yearly' ? 'selected' : '' }}>Tahunan</option>
                            </select>
                            @error('recurring_frequency') <p class="text-sm text-error mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="label"><span class="label-text">Info Tanggal</span></label>
                            <div class="p-3 bg-base-100 rounded border text-sm">
                                Tanggal tagihan otomatis akan mengikuti tanggal jatuh tempo yang dipilih di atas.
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Optional hidden field for prefilled company_id if provided and you want to submit it --}}
                @if($prefillCompany && is_numeric($prefillCompany))
                    <input type="hidden" name="company_id" value="{{ $prefillCompany }}">
                @endif

                <div class="flex items-center gap-2 pt-2">
                    <button type="submit" class="btn btn-primary">Simpan Tagihan</button>
                    <a href="{{ $backUrl }}" class="btn btn-ghost">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const recurringToggle = document.getElementById('recurring_toggle');
    const recurringOptions = document.getElementById('recurring_options');
    
    recurringToggle.addEventListener('change', function() {
        recurringOptions.style.display = this.checked ? 'grid' : 'none';
    });
});
</script>
@endsection