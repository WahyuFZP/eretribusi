{{-- <x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid auto-rows-min gap-4 md:grid-cols-4">
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
        </div>
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
        </div>
    </div>
</x-layouts.app>
 --}}

<x-layouts.app :title="__('Dashboard')">
    <section class="mb-4">
        <span class="tet-xxl font-semibold text-black">Overview</span>
    </section>
    <div class="grid gap-4 md:grid-cols-4 mb-6">
        <div class="stat bg-white rounded-xl shadow flex flex-col items-center">
            <div class="stat-figure text-green-600">
                <!-- Contoh icon SVG inline -->
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6m-6 0h6" />
                </svg>
            </div>
            <div class="stat-title text-gray-600">Total Invoice</div>
            <div class="stat-value text-black">120</div>
        </div>
        <div class="stat bg-white rounded-xl shadow flex flex-col items-center">
            <div class="stat-figure text-green-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 10c-4.41 0-8-1.79-8-4V6c0-2.21 3.59-4 8-4s8 1.79 8 4v8c0 2.21-3.59 4-8 4z" />
                </svg>
            </div>
            <div class="stat-title  text-gray-600">Total Pembayaran Bulan Ini</div>
            <div class="stat-value text-black">Rp 1.200.000</div>
        </div>
        <div class="stat bg-white rounded-xl shadow flex flex-col items-center">
            <div class="stat-figure text-green-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <div class="stat-title text-gray-600">Paid Invoice</div>
            <div class="stat-value text-black">Rp 800.000</div>
        </div>
        <div class="stat bg-white rounded-xl shadow flex flex-col items-center">
            <div class="stat-figure text-yellow-500">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3" />
                </svg>
            </div>
            <div class="stat-title  text-gray-600">Pending Invoice</div>
            <div class="stat-value text-yellow-600">Rp 400.000</div>
        </div>
    </div>
    <!-- ...konten dashboard lainnya... -->
</x-layouts.app>