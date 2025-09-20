{{-- <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ route('dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
                <x-app-logo />
            </a>

            <flux:navlist variant="outline">
                <flux:navlist.group :heading="__('Platform')" class="grid">
                    <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('Dashboard') }}</flux:navlist.item>
                </flux:navlist.group>
            </flux:navlist>

            <flux:spacer />

            <!-- Desktop User Menu -->
            <flux:dropdown class="hidden lg:block" position="bottom" align="start">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    icon:trailing="chevrons-up-down"
                />

                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full" data-test="logout-button">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full" data-test="logout-button">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @fluxScripts
    </body>
</html> --}}

<!-- filepath: c:\laragon\www\eretribusi\resources\views\components\layouts\app\sidebar.blade.php -->
<!-- filepath: c:\laragon\www\eretribusi\resources\views\components\layouts\app\sidebar.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head')
</head>
<body class="min-h-screen bg-white">
    <div class="drawer lg:drawer-open">
        <input id="sidebar-drawer" type="checkbox" class="drawer-toggle" />
        <div class="drawer-content flex flex-col">
            <!-- Main content -->
            <div class="p-4">
                {{ $slot }}
            </div>
        </div>
        <div class="drawer-side">
            <label for="sidebar-drawer" class="drawer-overlay"></label>
            <aside class="menu p-4 w-64 min-h-full bg-white border-r border-gray-200 text-gray-800">
                <div class="mb-4">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 mb-6">
                        <img src="{{ asset('icons/logo.png') }}" alt="Logo DLH" class="w-10 h-15 inline-block" />
                        <span class="font-bold text-lg">DLH</span>
                    </a>
                </div>

<ul>
    <li>
        <a href="{{ route('dashboard') }}"
           class="flex items-center px-3 py-2 rounded-lg transition
           {{ request()->routeIs('dashboard') 
                ? 'bg-green-600 text-white font-semibold' 
                : 'text-gray-800 hover:bg-green-50 hover:text-green-700' }}">
            <img src="{{ asset('icons/layout-dashboard.svg') }}" alt="Dashboard" class="w-6 h-6 inline-block mr-2" />
            Dashboard
        </a>
    </li>
    <li><a href="{{ route('companies.index') }}"
        class="flex items-center px-3 py-2 rounded-lg transition
        {{ request()->routeIs('companies.*') 
             ? 'bg-green-600 text-white font-semibold' 
             : 'text-gray-800 hover:bg-green-50 hover:text-green-700' }}">
        <span >
            <img src="{{ asset('icons/building-2.svg') }}" alt="Perusahaan" class="w-6 h-6 inline-block mr-2" />
            Perusahaan
        </span>
    </a>
</li>
    <li>
        <a href="{{ route('invoices.index') }}"
        class="flex items-center px-3 py-2 rounded-lg transition
        {{ request()->routeIs('invoices.*') 
             ? 'bg-green-600 text-white font-semibold' 
             : 'text-gray-800 hover:bg-green-50 hover:text-green-700' }}">
        <span>
            <img src="{{ asset('icons/receipt.svg') }}" alt="Tagihan" class="w-6 h-6 inline-block mr-2" />
            Tagihan
        </span>
        </a>
    </li>
    <li>
        <a href="{{ route('payments.index') }}"
        class="flex items-center px-3 py-2 rounded-lg transition
        {{ request()->routeIs('payments.*') 
             ? 'bg-green-600 text-white font-semibold' 
             : 'text-gray-800 hover:bg-green-50 hover:text-green-700' }}">
        <span>
            <img src="{{ asset('icons/pay.svg') }}" alt="Pembayaran" class="w-6 h-6 inline-block mr-2" />
            Pembayaran
        </span>
        </a>
    </li>
    <li>
        <span class="flex items-center px-3 py-2 text-gray-500 rounded-lg">
            <img src="{{ asset('icons/notification.svg') }}" alt="Notifikasi" class="w-6 h-6 inline-block mr-2" />
            Notifikasi
        </span>
    </li>
    <li class="mt-8">
        <span class="flex items-center px-3 py-2 text-gray-500 rounded-lg">Pengaturan</span>
    </li>
    <li>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center w-full text-left px-3 py-2 rounded-lg hover:bg-green-50 hover:text-green-700 transition">
                <img src="{{ asset('icons/logout.svg') }}" alt="Log Out" class="w-6 h-6 inline-block mr-2" />
                Log Out
            </button>
        </form>
    </li>
</ul>
            </aside>
        </div>
    </div>
</body>
</html> 
