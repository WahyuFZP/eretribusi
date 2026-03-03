<x-guest-layout>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/dlh.png') }}" alt="DLH" class="h-8 w-8 object-contain">
            <div>
                <div class="text-base font-semibold text-slate-800">E‑Retribusi</div>
                <div class="text-xs text-slate-500">Silakan masuk untuk melanjutkan</div>
            </div>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-2" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password with visibility toggle -->
            <div>
                <x-input-label for="password" :value="__('Password')" />
                <div class="relative mt-1">
                    <x-text-input id="password" class="w-full pr-10" type="password" name="password" required autocomplete="current-password" />
                    <button type="button" id="togglePassword" aria-label="Toggle password visibility" class="absolute inset-y-0 right-0 flex items-center px-3 text-slate-500 hover:text-slate-700">
                        <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5"><path d="M12 5c-7.633 0-10.5 7-10.5 7s2.867 7 10.5 7 10.5-7 10.5-7-2.867-7-10.5-7zm0 11a4 4 0 110-8 4 4 0 010 8z"/></svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember + Forgot -->
            <div class="flex items-center justify-between">
                <label for="remember_me" class="inline-flex items-center gap-2">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                    <span class="text-sm text-gray-700">{{ __('Remember me') }}</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-sm font-medium text-indigo-600 hover:text-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 rounded whitespace-nowrap" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif
            </div>

            <!-- Submit -->
            <div class="pt-1">
                <x-primary-button class="w-full justify-center" id="loginButton">
                    {{ __('Log in') }}
                </x-primary-button>
            </div>
        </form>
    </div>

    <!-- Password toggle script -->
    <script>
        (function() {
            const input = document.getElementById('password');
            const btn = document.getElementById('togglePassword');
            const icon = document.getElementById('eyeIcon');
            if (!input || !btn || !icon) return;
            btn.addEventListener('click', function() {
                const isPwd = input.getAttribute('type') === 'password';
                input.setAttribute('type', isPwd ? 'text' : 'password');
                icon.innerHTML = isPwd
                    ? '<path d="M2.25 12s2.867 7 9.75 7c2.794 0 5.254-.927 7.104-2.168l-2.4-2.4A7.5 7.5 0 116.568 7.146L4.97 5.548C3.36 7.146 2.25 9.4 2.25 12z"/>'
                    : '<path d="M12 5c-7.633 0-10.5 7-10.5 7s2.867 7 10.5 7 10.5-7 10.5-7-2.867-7-10.5-7zm0 11a4 4 0 110-8 4 4 0 010 8z"/>';
            });
        })();
    </script>

    <!-- Simple validation + notice script (no layout changes) -->
    <script>
        (function() {
            const form = document.querySelector('form[action*="login"]');
            const email = document.getElementById('email');
            const password = document.getElementById('password');
            if (!form || !email || !password) return;

            function showNotice(message) {
                let toast = document.getElementById('login-toast');
                if (!toast) {
                    toast = document.createElement('div');
                    toast.id = 'login-toast';
                    toast.className = 'fixed top-3 left-1/2 -translate-x-1/2 z-50 px-4 py-2 rounded-md bg-amber-100 text-amber-800 shadow border border-amber-300 text-sm';
                    document.body.appendChild(toast);
                }
                toast.textContent = message;
                toast.style.display = 'block';
                clearTimeout(toast._hideTimer);
                toast._hideTimer = setTimeout(() => {
                    toast.style.display = 'none';
                }, 2500);
            }

            form.addEventListener('submit', function(e) {
                const emailVal = (email.value || '').trim();
                const pwdVal = (password.value || '').trim();
                if (!emailVal || !pwdVal) {
                    e.preventDefault();
                    showNotice('Fill the fields first');
                    if (!emailVal) email.focus(); else if (!pwdVal) password.focus();
                }
            });
        })();
    </script>
</x-guest-layout>
