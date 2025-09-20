<!-- filepath: c:\laragon\www\eretribusi\resources\views\invoices\index.blade.php -->
<x-layouts.app :title="'Daftar Tagihan'">
    <section class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-black">Tagihan</h1>
                <p class="text-gray-600 mt-1">Kelola dan kirim tagihan retribusi kepada perusahaan</p>
            </div>
            <div class="flex gap-2">
                <button class="btn btn-outline btn-sm">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Export
                </button>
                <a href="#" class="btn btn-primary btn-sm">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Buat Tagihan
                </a>
            </div>
        </div>
    </section>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="card bg-white shadow-sm border">
            <div class="card-body p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Total Tagihan</p>
                        <p class="text-2xl font-bold text-black">45</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-white shadow-sm border">
            <div class="card-body p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Sudah Dibayar</p>
                        <p class="text-2xl font-bold text-black">32</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-white shadow-sm border">
            <div class="card-body p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-yellow-100 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Pending</p>
                        <p class="text-2xl font-bold text-black">8</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-white shadow-sm border">
            <div class="card-body p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-red-100 rounded-lg">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Jatuh Tempo</p>
                        <p class="text-2xl font-bold text-black">5</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white rounded-lg shadow-sm border p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div class="form-control">
                <input type="text" placeholder="Cari tagihan..."
                    class="input input-bordered input-sm text-black bg-white" />
            </div>
            <div class="form-control">
                <select class="select select-bordered select-sm text-black bg-white">
                    <option disabled selected>Status</option>
                    <option>Draft</option>
                    <option>Sent</option>
                    <option>Paid</option>
                    <option>Overdue</option>
                    <option>Cancelled</option>
                </select>
            </div>
            <div class="form-control">
                <select class="select select-bordered select-sm text-black bg-white">
                    <option disabled selected>Perusahaan</option>
                    <option>Semua Perusahaan</option>
                    <option>PT Maju Jaya</option>
                    <option>CV Sukses Selalu</option>
                </select>
            </div>
            <div class="form-control">
                <select class="select select-bordered select-sm text-black bg-white">
                    <option disabled selected>Periode</option>
                    <option>Bulan Ini</option>
                    <option>Bulan Lalu</option>
                    <option>3 Bulan Terakhir</option>
                </select>
            </div>
            <div class="form-control">
                <button class="btn btn-outline btn-sm w-full">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.414A1 1 0 013 6.707V4z" />
                    </svg>
                    Filter
                </button>
            </div>
        </div>
    </div>

    <!-- Invoices Table -->
    <div class="bg-white rounded-lg shadow-sm border">
        <div class="overflow-x-auto">
            <table class="table w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="text-black font-medium">
                            <label>
                                <input type="checkbox" class="checkbox checkbox-sm" />
                            </label>
                        </th>
                        <th class="text-black font-medium">No. Invoice</th>
                        <th class="text-black font-medium">Perusahaan</th>
                        <th class="text-black font-medium">Periode</th>
                        <th class="text-black font-medium">Jumlah</th>
                        <th class="text-black font-medium">Jatuh Tempo</th>
                        <th class="text-black font-medium">Status</th>
                        <th class="text-black font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Invoice Row 1 -->
                    <tr class="hover:bg-gray-50">
                        <td>
                            <label>
                                <input type="checkbox" class="checkbox checkbox-sm" />
                            </label>
                        </td>
                        <td class="text-black font-medium">INV-2024-001</td>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="avatar">
                                    <div class="mask mask-squircle w-10 h-10">
                                        <img src="https://ui-avatars.com/api/?name=PT+Maju+Jaya&background=10b981&color=fff"
                                            alt="PT Maju Jaya" />
                                    </div>
                                </div>
                                <div>
                                    <div class="font-medium text-black">PT Maju Jaya</div>
                                    <div class="text-sm text-gray-500">majujaya@email.com</div>
                                </div>
                            </div>
                        </td>
                        <td class="text-black">Jan 2024</td>
                        <td class="text-black font-medium">Rp 2.500.000</td>
                        <td class="text-black">25 Jan 2024</td>
                        <td>
                            <div class="badge badge-success badge-sm text-white">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                Paid
                            </div>
                        </td>
                        <td>
                            <div class="dropdown dropdown-end">
                                <label tabindex="0"
                                    class="btn btn-ghost btn-xs hover:bg-gray-100 border border-gray-200">
                                    <svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                    </svg>
                                </label>
                               <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-box z-[1] w-52 p-2 shadow">
            <li>
                <a class="text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    Lihat Detail
                </a>
            </li>
            <li>
                <a class="text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Download PDF
                </a>
            </li>
        </ul>
                            </div>
                        </td>
                    </tr>

                    <!-- Invoice Row 2 - Pending -->
                    <tr class="hover:bg-gray-50">
                        <td>
                            <label>
                                <input type="checkbox" class="checkbox checkbox-sm" />
                            </label>
                        </td>
                        <td class="text-black font-medium">INV-2024-002</td>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="avatar">
                                    <div class="mask mask-squircle w-10 h-10">
                                        <img src="https://ui-avatars.com/api/?name=CV+Sukses+Selalu&background=3b82f6&color=fff"
                                            alt="CV Sukses Selalu" />
                                    </div>
                                </div>
                                <div>
                                    <div class="font-medium text-black">CV Sukses Selalu</div>
                                    <div class="text-sm text-gray-500">suksesselalu@email.com</div>
                                </div>
                            </div>
                        </td>
                        <td class="text-black">Jan 2024</td>
                        <td class="text-black font-medium">Rp 1.800.000</td>
                        <td class="text-black">28 Jan 2024</td>
                        <td>
                            <div class="badge badge-warning badge-sm text-white">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                Pending
                            </div>
                        </td>
                        <td>
                            <div class="dropdown dropdown-end">
                                <label tabindex="0"
                                    class="btn btn-ghost btn-xs hover:bg-gray-100 border border-gray-200">
                                    <svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                    </svg>
                                </label>
                                <ul tabindex="0"
                                    class="dropdown-content menu bg-base-100 rounded-box z-[1] w-52 p-2 shadow">
                                    <li>
                                        <a class="text-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Lihat Detail
                                        </a>
                                    </li>
                                    <li>
                                        <a class="text-sm text-blue-600">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                            </svg>
                                            Kirim Tagihan
                                        </a>
                                    </li>
                                    <li>
                                        <a class="text-sm text-green-600">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                            Tandai Lunas
                                        </a>
                                    </li>
                                    <div class="divider my-1"></div>
                                    <li>
                                        <a class="text-sm text-red-600">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Batalkan
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>

                    <!-- Invoice Row 3 - Overdue -->
                    <tr class="hover:bg-gray-50">
                        <td>
                            <label>
                                <input type="checkbox" class="checkbox checkbox-sm" />
                            </label>
                        </td>
                        <td class="text-black font-medium">INV-2024-003</td>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="avatar">
                                    <div class="mask mask-squircle w-10 h-10">
                                        <img src="https://ui-avatars.com/api/?name=UD+Makmur+Sentosa&background=f59e0b&color=fff"
                                            alt="UD Makmur Sentosa" />
                                    </div>
                                </div>
                                <div>
                                    <div class="font-medium text-black">UD Makmur Sentosa</div>
                                    <div class="text-sm text-gray-500">makmursentosa@email.com</div>
                                </div>
                            </div>
                        </td>
                        <td class="text-black">Dec 2023</td>
                        <td class="text-black font-medium">Rp 3.200.000</td>
                        <td class="text-black">15 Jan 2024</td>
                        <td>
                            <div class="badge badge-error badge-sm text-white">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                                Overdue
                            </div>
                        </td>
                        <td>
                            <div class="dropdown dropdown-end">
                                <label tabindex="0"
                                    class="btn btn-ghost btn-xs hover:bg-gray-100 border border-gray-200">
                                    <svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                    </svg>
                                </label>
                                <ul tabindex="0"
                                    class="dropdown-content menu bg-base-100 rounded-box z-[1] w-52 p-2 shadow">
                                    <li>
                                        <a class="text-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Lihat Detail
                                        </a>
                                    </li>
                                    <li>
                                        <a class="text-sm text-orange-600">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Kirim Reminder
                                        </a>
                                    </li>
                                    <li>
                                        <a class="text-sm text-green-600">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                            Tandai Lunas
                                        </a>
                                    </li>
                                    <div class="divider my-1"></div>
                                    <li>
                                        <a class="text-sm text-red-600">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Batalkan
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="flex justify-between items-center p-4 border-t bg-gray-50">
            <div class="text-sm text-gray-600">
                Menampilkan 1 sampai 10 dari 45 tagihan
            </div>
            <div class="join">
                <button class="join-item btn btn-sm">Previous</button>
                <button class="join-item btn btn-sm btn-active">1</button>
                <button class="join-item btn btn-sm">2</button>
                <button class="join-item btn btn-sm">3</button>
                <button class="join-item btn btn-sm">Next</button>
            </div>
        </div>
    </div>

    <!-- Bulk Actions -->
    <div class="fixed bottom-4 left-1/2 transform -translate-x-1/2 bg-white shadow-lg rounded-lg border p-4 hidden"
        id="bulk-actions">
        <div class="flex items-center gap-4">
            <span class="text-sm text-gray-600">2 tagihan dipilih</span>
            <div class="flex gap-2">
                <button class="btn btn-sm btn-outline">Kirim Massal</button>
                <button class="btn btn-sm btn-outline">Export</button>
                <button class="btn btn-sm btn-error btn-outline">Hapus</button>
            </div>
        </div>
    </div>
</x-layouts.app>
