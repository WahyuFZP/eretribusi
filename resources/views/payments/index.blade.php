<!-- filepath: c:\laragon\www\eretribusi\resources\views\payments\index.blade.php -->
<x-layouts.app :title="'Daftar Pembayaran'">
    <section class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-black">Payments</h1>
                <p class="text-gray-600 mt-1">Manage and track all payment transactions</p>
            </div>
            <div class="flex gap-2">
                <button class="btn btn-outline btn-sm">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Export
                </button>
                <button class="btn btn-primary btn-sm">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Payment
                </button>
            </div>
        </div>
    </section>

    <!-- Filters Section -->
    <div class="bg-white rounded-lg shadow-sm border p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="form-control">
                <input type="text" placeholder="Search payments..." class="input input-bordered input-sm text-black bg-white" />
            </div>
            <div class="form-control">
                <select class="select select-bordered select-sm text-black bg-white">
                    <option disabled selected>Status</option>
                    <option>Paid</option>
                    <option>Pending</option>
                    <option>Refunded</option>
                    <option>Cancelled</option>
                </select>
            </div>
            <div class="form-control">
                <select class="select select-bordered select-sm text-black bg-white">
                    <option disabled selected>Period</option>
                    <option>This Month</option>
                    <option>Last Month</option>
                    <option>This Year</option>
                </select>
            </div>
            <div class="form-control">
                <select class="select select-bordered select-sm text-black bg-white">
                    <option disabled selected>Company</option>
                    <option>All Companies</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Payments Table -->
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
                        <th class="text-black font-medium">Invoice</th>
                        <th class="text-black font-medium">Date</th>
                        <th class="text-black font-medium">Customer</th>
                        <th class="text-black font-medium">Amount</th>
                        <th class="text-black font-medium">Status</th>
                        <th class="text-black font-medium">Method</th>
                        <th class="text-black font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Payment Row 1 -->
                    <tr class="hover:bg-gray-50">
                        <td>
                            <label>
                                <input type="checkbox" class="checkbox checkbox-sm" />
                            </label>
                        </td>
                        <td class="text-black font-medium">INV-3066</td>
                        <td class="text-black">Jan 6, 2022</td>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="avatar">
                                    <div class="mask mask-squircle w-10 h-10">
                                        <img src="https://ui-avatars.com/api/?name=Olivia+Rhye&background=10b981&color=fff" alt="OR" />
                                    </div>
                                </div>
                                <div>
                                    <div class="font-medium text-black">Olivia Rhye</div>
                                    <div class="text-sm text-gray-500">olivia@untitledui.com</div>
                                </div>
                            </div>
                        </td>
                        <td class="text-black font-medium">Rp 2.500.000</td>
                        <td>
                            <div class="badge badge-success badge-sm text-white">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                Paid
                            </div>
                        </td>
                        <td class="text-black">Bank Transfer</td>
                        <td>
                            <div class="dropdown dropdown-end">
                                <label tabindex="0" class="btn btn-ghost btn-xs">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                    </svg>
                                </label>
                                <ul tabindex="0" class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-52">
                                    <li><a class="text-black">View Details</a></li>
                                    <li><a class="text-black">Download Receipt</a></li>
                                    <li><a class="text-red-600">Refund</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>

                    <!-- Payment Row 2 -->
                    <tr class="hover:bg-gray-50">
                        <td>
                            <label>
                                <input type="checkbox" class="checkbox checkbox-sm" />
                            </label>
                        </td>
                        <td class="text-black font-medium">INV-3065</td>
                        <td class="text-black">Jan 6, 2022</td>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="avatar">
                                    <div class="mask mask-squircle w-10 h-10">
                                        <img src="https://ui-avatars.com/api/?name=Phoenix+Baker&background=3b82f6&color=fff" alt="PB" />
                                    </div>
                                </div>
                                <div>
                                    <div class="font-medium text-black">Phoenix Baker</div>
                                    <div class="text-sm text-gray-500">phoenix@untitledui.com</div>
                                </div>
                            </div>
                        </td>
                        <td class="text-black font-medium">Rp 1.800.000</td>
                        <td>
                            <div class="badge badge-success badge-sm text-white">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                Paid
                            </div>
                        </td>
                        <td class="text-black">Credit Card</td>
                        <td>
                            <div class="dropdown dropdown-end">
                                <label tabindex="0" class="btn btn-ghost btn-xs">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                    </svg>
                                </label>
                                <ul tabindex="0" class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-52">
                                    <li><a class="text-black">View Details</a></li>
                                    <li><a class="text-black">Download Receipt</a></li>
                                    <li><a class="text-red-600">Refund</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>

                    <!-- Payment Row 3 - Pending -->
                    <tr class="hover:bg-gray-50">
                        <td>
                            <label>
                                <input type="checkbox" class="checkbox checkbox-sm" />
                            </label>
                        </td>
                        <td class="text-black font-medium">INV-3064</td>
                        <td class="text-black">Jan 5, 2022</td>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="avatar">
                                    <div class="mask mask-squircle w-10 h-10">
                                        <img src="https://ui-avatars.com/api/?name=Lana+Steiner&background=f59e0b&color=fff" alt="LS" />
                                    </div>
                                </div>
                                <div>
                                    <div class="font-medium text-black">Lana Steiner</div>
                                    <div class="text-sm text-gray-500">lana@untitledui.com</div>
                                </div>
                            </div>
                        </td>
                        <td class="text-black font-medium">Rp 3.200.000</td>
                        <td>
                            <div class="badge badge-warning badge-sm text-white">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                Pending
                            </div>
                        </td>
                        <td class="text-black">Bank Transfer</td>
                        <td>
                            <div class="dropdown dropdown-end">
                                <label tabindex="0" class="btn btn-ghost btn-xs">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                    </svg>
                                </label>
                                <ul tabindex="0" class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-52">
                                    <li><a class="text-black">View Details</a></li>
                                    <li><a class="text-black">Send Reminder</a></li>
                                    <li><a class="text-red-600">Cancel</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>

                    <!-- Payment Row 4 - Refunded -->
                    <tr class="hover:bg-gray-50">
                        <td>
                            <label>
                                <input type="checkbox" class="checkbox checkbox-sm" />
                            </label>
                        </td>
                        <td class="text-black font-medium">INV-3062</td>
                        <td class="text-black">Jan 5, 2022</td>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="avatar">
                                    <div class="mask mask-squircle w-10 h-10">
                                        <img src="https://ui-avatars.com/api/?name=Candice+Wu&background=8b5cf6&color=fff" alt="CW" />
                                    </div>
                                </div>
                                <div>
                                    <div class="font-medium text-black">Candice Wu</div>
                                    <div class="text-sm text-gray-500">candice@untitledui.com</div>
                                </div>
                            </div>
                        </td>
                        <td class="text-black font-medium">Rp 1.500.000</td>
                        <td>
                            <div class="badge badge-info badge-sm text-white">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                                </svg>
                                Refunded
                            </div>
                        </td>
                        <td class="text-black">Credit Card</td>
                        <td>
                            <div class="dropdown dropdown-end">
                                <label tabindex="0" class="btn btn-ghost btn-xs">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                    </svg>
                                </label>
                                <ul tabindex="0" class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-52">
                                    <li><a class="text-black">View Details</a></li>
                                    <li><a class="text-black">Download Receipt</a></li>
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
                Showing 1 to 10 of 47 results
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
</x-layouts.app>