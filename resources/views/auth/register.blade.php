<x-guest-layout>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/dlh.png') }}" alt="DLH" class="h-8 w-8 object-contain">
            <div>
                <div class="text-base font-semibold text-slate-800">E‑Retribusi</div>
                <div class="text-xs text-slate-500">Buat akun untuk melanjutkan</div>
            </div>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input id="name" class="mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password with visibility toggle -->
            <div>
                <x-input-label for="password" :value="__('Password')" />
                <div class="relative mt-1">
                    <x-text-input id="password" class="w-full pr-10" type="password" name="password" required autocomplete="new-password" />
                    <button type="button" id="togglePassword" aria-label="Toggle password visibility" class="absolute inset-y-0 right-0 flex items-center px-3 text-slate-500 hover:text-slate-700">
                        <svg id="eyeIcon1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5"><path d="M12 5c-7.633 0-10.5 7-10.5 7s2.867 7 10.5 7 10.5-7 10.5-7-2.867-7-10.5-7zm0 11a4 4 0 110-8 4 4 0 010 8z"/></svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password with visibility toggle -->
            <div>
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                <div class="relative mt-1">
                    <x-text-input id="password_confirmation" class="w-full pr-10" type="password" name="password_confirmation" required autocomplete="new-password" />
                    <button type="button" id="toggleConfirm" aria-label="Toggle confirm password visibility" class="absolute inset-y-0 right-0 flex items-center px-3 text-slate-500 hover:text-slate-700">
                        <svg id="eyeIcon2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5"><path d="M12 5c-7.633 0-10.5 7-10.5 7s2.867 7 10.5 7 10.5-7 10.5-7-2.867-7-10.5-7zm0 11a4 4 0 110-8 4 4 0 010 8z"/></svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-between">
                <a class="text-sm font-medium text-indigo-600 hover:text-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 rounded whitespace-nowrap" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-primary-button class="ml-4">
                    {{ __('Register') }}
                </x-primary-button>
            </div>
        </form>
    </div>

    <!-- Password toggles script -->
    <script>
        (function() {
            const pairs = [
                { inputId: 'password', btnId: 'togglePassword', iconId: 'eyeIcon1' },
                { inputId: 'password_confirmation', btnId: 'toggleConfirm', iconId: 'eyeIcon2' }
            ];
            pairs.forEach(({ inputId, btnId, iconId }) => {
                const input = document.getElementById(inputId);
                const btn = document.getElementById(btnId);
                const icon = document.getElementById(iconId);
                if (!input || !btn || !icon) return;
                btn.addEventListener('click', function() {
                    const isPwd = input.getAttribute('type') === 'password';
                    input.setAttribute('type', isPwd ? 'text' : 'password');
                    icon.innerHTML = isPwd
                        ? '<path d="M2.25 12s2.867 7 9.75 7c2.794 0 5.254-.927 7.104-2.168l-2.4-2.4A7.5 7.5 0 116.568 7.146L4.97 5.548C3.36 7.146 2.25 9.4 2.25 12z"/>'
                        : '<path d="M12 5c-7.633 0-10.5 7-10.5 7s2.867 7 10.5 7 10.5-7 10.5-7-2.867-7-10.5-7zm0 11a4 4 0 110-8 4 4 0 010 8z"/>';
                });
            });
        })();
    </script>
</x-guest-layout>
