@extends('layouts.app')

@section('title', 'Kelola Tagihan Otomatis')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold">Kelola Tagihan Otomatis</h1>
                <p class="text-gray-600">Kelola dan monitoring tagihan yang dibuat secara otomatis</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.tagihan.index') }}" class="btn btn-ghost">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Kembali ke Tagihan
                </a>
                <a href="{{ route('admin.tagihan.create') }}" class="btn btn-primary">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Buat Tagihan Baru
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        @if($recurringBills->isEmpty())
            <div class="text-center py-8">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada tagihan otomatis</h3>
                <p class="mt-1 text-sm text-gray-500">Mulai dengan membuat tagihan dan mengaktifkan fitur otomatis.</p>
                <div class="mt-6">
                    <a href="{{ route('admin.tagihan.create') }}" class="btn btn-primary">
                        Buat Tagihan dengan Auto-Generate
                    </a>
                </div>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="table table-zebra">
                    <thead>
                        <tr>
                            <th>No. Tagihan</th>
                            <th>Perusahaan</th>
                            <th>Jumlah</th>
                            <th>Frekuensi</th>
                            <th>Tanggal Berikutnya</th>
                            <th>Total Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recurringBills as $bill)
                        <tr>
                            <td>
                                <div class="font-medium">{{ $bill->bill_number }}</div>
                                <div class="text-sm text-gray-500">
                                    Dibuat: {{ $bill->created_at->format('d/m/Y') }}
                                </div>
                            </td>
                            <td>
                                <div class="font-medium">{{ $bill->company->name ?? '-' }}</div>
                                <div class="text-sm text-gray-500">{{ $bill->company->code ?? '-' }}</div>
                            </td>
                            <td>
                                <div class="font-medium">Rp {{ number_format($bill->amount, 0, ',', '.') }}</div>
                            </td>
                            <td>
                                <div class="badge badge-primary">
                                    @if($bill->recurring_frequency === 'monthly')
                                        Bulanan (tgl {{ $bill->recurring_day_of_month }})
                                    @else
                                        Tahunan (tgl {{ $bill->recurring_day_of_month }})
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($bill->next_billing_date)
                                    <div class="font-medium">{{ $bill->next_billing_date->format('d/m/Y') }}</div>
                                    @if($bill->next_billing_date->isPast())
                                        <div class="badge badge-warning badge-sm">Terlambat</div>
                                    @elseif($bill->next_billing_date->isToday())
                                        <div class="badge badge-info badge-sm">Hari ini</div>
                                    @elseif($bill->next_billing_date->isTomorrow())
                                        <div class="badge badge-success badge-sm">Besok</div>
                                    @endif
                                @else
                                    <span class="text-gray-500">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="font-medium">{{ $bill->childBills->count() }} tagihan</div>
                                @if($bill->childBills->count() > 0)
                                    <div class="text-sm text-gray-500">
                                        Terakhir: {{ $bill->childBills->sortByDesc('created_at')->first()->created_at->format('d/m/Y') }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="dropdown dropdown-left">
                                    <label tabindex="0" class="btn btn-ghost btn-xs">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                        </svg>
                                    </label>
                                    <ul tabindex="0" class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-52">
                                        <li>
                                            <form action="{{ route('admin.tagihan.generate-next', $bill) }}" method="POST" class="w-full">
                                                @csrf
                                                <button type="submit" class="w-full text-left" onclick="return confirm('Generate tagihan untuk periode berikutnya?')">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                    </svg>
                                                    Generate Sekarang
                                                </button>
                                            </form>
                                        </li>
                                        <li>
                                            <button onclick="openEditModal('{{ $bill->id }}', '{{ $bill->recurring_frequency }}', '{{ $bill->recurring_day_of_month }}', '{{ $bill->next_billing_date ? $bill->next_billing_date->format('Y-m-d') : '' }}')" class="w-full text-left">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                Edit Pengaturan
                                            </button>
                                        </li>
                                        <li>
                                            <form action="{{ route('admin.tagihan.disable-recurring', $bill) }}" method="POST" class="w-full">
                                                @csrf
                                                <button type="submit" class="w-full text-left text-error" onclick="return confirm('Nonaktifkan tagihan otomatis ini?')">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    Nonaktifkan
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $recurringBills->links() }}
            </div>
        @endif
    </div>
</div>

{{-- Modal Edit Recurring Settings --}}
<dialog id="edit_modal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg">Edit Pengaturan Tagihan Otomatis</h3>
        <form id="editForm" method="POST" class="mt-4">
            @csrf
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text">Frekuensi</span>
                </label>
                <select name="recurring_frequency" id="edit_frequency" class="select select-bordered w-full">
                    <option value="monthly">Bulanan</option>
                    <option value="yearly">Tahunan</option>
                </select>
            </div>
            
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text">Tanggal dalam Bulan</span>
                </label>
                <input type="number" name="recurring_day_of_month" id="edit_day" min="1" max="31" class="input input-bordered w-full" required>
            </div>
            
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text">Mulai dari Tanggal</span>
                </label>
                <input type="date" name="start_date" id="edit_start_date" class="input input-bordered w-full" required>
            </div>
            
            <div class="modal-action">
                <button type="button" class="btn btn-ghost" onclick="edit_modal.close()">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<script>
function openEditModal(billId, frequency, dayOfMonth, nextDate) {
    document.getElementById('editForm').action = `/admin/tagihan/${billId}/setup-recurring`;
    document.getElementById('edit_frequency').value = frequency;
    document.getElementById('edit_day').value = dayOfMonth;
    document.getElementById('edit_start_date').value = nextDate || new Date().toISOString().split('T')[0];
    document.getElementById('edit_modal').showModal();
}
</script>
@endsection