@extends('layouts.app')

@section('title', 'Register - AYS Call Center')

@section('content')
<div class="w-full max-w-[460px]" style="animation: simpleFadeIn 0.5s ease-out both;">
    <div class="bg-white/95 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 overflow-hidden">
        {{-- Header --}}
        <div class="relative bg-gradient-to-br from-navy-600 via-navy-700 to-navy-800 px-8 pt-8 pb-10 text-center overflow-hidden">
            <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(rgba(255,255,255,0.2) 1px, transparent 1px); background-size: 20px 20px;"></div>
            <div class="absolute -top-12 -left-12 w-40 h-40 rounded-full bg-copper-400/10 blur-2xl"></div>
            <div class="relative">
                <div class="w-16 h-16 mx-auto bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center mb-4 ring-1 ring-white/20">
                    <img src="{{ asset('register.png') }}" alt="Register" class="w-10 h-10 object-contain">
                </div>
                <h2 class="text-2xl font-extrabold text-white tracking-tight">Create Account</h2>
                <p class="text-navy-200 text-sm mt-1.5 font-medium">Join the AYS Call Center team</p>
            </div>
        </div>

        {{-- Form --}}
        <div class="p-8">
            <form method="POST" action="{{ route('register') }}" class="space-y-4" id="registerForm">
                @csrf

                {{-- First + Last Name --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="first_name" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">First Name</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none transition-colors">
                                <svg class="w-5 h-5 text-gray-300 group-focus-within:text-navy-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <input id="first_name" type="text" name="first_name" value="{{ old('first_name') }}" required autocomplete="given-name" autofocus
                                class="w-full pl-11 pr-3 py-3 rounded-xl border-2 @error('first_name') border-red-300 bg-red-50/50 @else border-gray-100 bg-gray-50/50 @enderror focus:border-navy-500 focus:bg-white focus:ring-0 outline-none transition-all text-sm font-medium placeholder:text-gray-300"
                                placeholder="John">
                        </div>
                        @error('first_name')
                            <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="last_name" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Last Name</label>
                        <input id="last_name" type="text" name="last_name" value="{{ old('last_name') }}" required autocomplete="family-name"
                            class="w-full px-3.5 py-3 rounded-xl border-2 @error('last_name') border-red-300 bg-red-50/50 @else border-gray-100 bg-gray-50/50 @enderror focus:border-navy-500 focus:bg-white focus:ring-0 outline-none transition-all text-sm font-medium placeholder:text-gray-300"
                            placeholder="Doe">
                        @error('last_name')
                            <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Email Address</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none transition-colors">
                            <svg class="w-5 h-5 text-gray-300 group-focus-within:text-navy-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                        </div>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email"
                            class="w-full pl-11 pr-4 py-3 rounded-xl border-2 @error('email') border-red-300 bg-red-50/50 @else border-gray-100 bg-gray-50/50 @enderror focus:border-navy-500 focus:bg-white focus:ring-0 outline-none transition-all text-sm font-medium placeholder:text-gray-300"
                            placeholder="name@example.com">
                    </div>
                    @error('email')
                        <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1 font-medium"><svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</p>
                    @enderror
                </div>

                {{-- Phone --}}
                <div>
                    <label for="phone" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Phone Number</label>
                    <input type="hidden" name="phone" id="phone-hidden" value="{{ old('phone') }}">
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-0">
                            <div class="flex items-center gap-1.5 bg-gray-50 border-2 border-gray-100 border-r-0 rounded-l-xl px-3 h-full transition-colors group-focus-within:bg-white group-focus-within:border-navy-500">
                                <img src="https://flagcdn.com/w40/tz.png" alt="Tanzania" class="w-5 h-3.5 object-cover rounded-sm shadow-sm">
                                <span class="text-xs font-bold text-gray-600 select-none">+255</span>
                            </div>
                        </div>
                        <input id="phone-display" type="tel" inputmode="numeric" autocomplete="tel"
                            class="w-full pl-[92px] pr-4 py-3 rounded-xl border-2 @error('phone') border-red-300 bg-red-50/50 @else border-gray-100 bg-gray-50/50 @enderror focus:border-navy-500 focus:bg-white focus:ring-0 outline-none transition-all text-sm font-mono tracking-wide placeholder:text-gray-300"
                            placeholder="7XX XXX XXX" maxlength="9"
                            value="{{ old('phone') ? preg_replace('/^255/', '', old('phone')) : '' }}">
                    </div>
                    <p class="mt-1.5 text-[11px] text-gray-400 flex items-center gap-1 font-medium">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Enter 9 digits starting with 7 or 6
                    </p>
                    @error('phone')
                        <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1 font-medium">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Password</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none transition-colors">
                            <svg class="w-5 h-5 text-gray-300 group-focus-within:text-navy-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <input id="password" type="password" name="password" required autocomplete="new-password" minlength="8"
                            class="w-full pl-11 pr-12 py-3 rounded-xl border-2 @error('password') border-red-300 bg-red-50/50 @else border-gray-100 bg-gray-50/50 @enderror focus:border-navy-500 focus:bg-white focus:ring-0 outline-none transition-all text-sm font-medium placeholder:text-gray-300"
                            placeholder="Min. 8 characters">
                        <button type="button" onclick="togglePassword('password', this)" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-300 hover:text-navy-500 transition-colors" aria-label="Show password">
                            <svg class="w-5 h-5 eye-open" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg class="w-5 h-5 eye-closed hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 015.536-6.988M5.05 5.05a10.05 10.05 0 016.95-2.05c4.478 0 8.268 2.943 9.542 7a10.05 10.05 0 01-4.043 5.97M3 3l18 18"/></svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1 font-medium"><svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label for="password-confirm" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Confirm Password</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none transition-colors">
                            <svg class="w-5 h-5 text-gray-300 group-focus-within:text-navy-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <input id="password-confirm" type="password" name="password_confirmation" required autocomplete="new-password"
                            class="w-full pl-11 pr-12 py-3 rounded-xl border-2 border-gray-100 bg-gray-50/50 focus:border-navy-500 focus:bg-white focus:ring-0 outline-none transition-all text-sm font-medium placeholder:text-gray-300"
                            placeholder="Re-enter your password">
                        <button type="button" onclick="togglePassword('password-confirm', this)" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-300 hover:text-navy-500 transition-colors" aria-label="Show password">
                            <svg class="w-5 h-5 eye-open" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg class="w-5 h-5 eye-closed hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 015.536-6.988M5.05 5.05a10.05 10.05 0 016.95-2.05c4.478 0 8.268 2.943 9.542 7a10.05 10.05 0 01-4.043 5.97M3 3l18 18"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Submit --}}
                <button type="submit" class="w-full py-3.5 text-sm font-bold text-white bg-gradient-to-r from-navy-600 to-navy-700 hover:from-navy-700 hover:to-navy-800 rounded-xl shadow-lg shadow-navy-600/20 hover:shadow-xl hover:shadow-navy-600/30 transition-all flex items-center justify-center gap-2 mt-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                    Create Account
                </button>
            </form>

            {{-- Divider --}}
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-100"></div></div>
                <div class="relative flex justify-center text-xs"><span class="px-3 bg-white text-gray-400 font-medium">Already registered?</span></div>
            </div>

            {{-- Login link --}}
            <a href="{{ route('login') }}" data-ajax class="block w-full py-3 text-sm font-bold text-navy-700 bg-navy-50 hover:bg-navy-100 rounded-xl transition-all text-center border border-navy-100">
                Sign In
            </a>
        </div>
    </div>

    <p class="mt-6 text-center text-xs text-white/60 font-medium">&copy; {{ date('Y') }} AYS Call Center. All rights reserved.</p>
</div>

{{-- Phone + Password Scripts --}}
<script>
function togglePassword(inputId, btn) {
    const input = document.getElementById(inputId);
    const eyeOpen = btn.querySelector('.eye-open');
    const eyeClosed = btn.querySelector('.eye-closed');
    if (input.type === 'password') {
        input.type = 'text';
        eyeOpen.classList.add('hidden');
        eyeClosed.classList.remove('hidden');
    } else {
        input.type = 'password';
        eyeOpen.classList.remove('hidden');
        eyeClosed.classList.add('hidden');
    }
}

(function() {
    const phoneDisplay = document.getElementById('phone-display');
    const phoneHidden  = document.getElementById('phone-hidden');
    const form         = document.getElementById('registerForm');

    if (phoneDisplay && phoneHidden) {
        function syncPhone() {
            let raw = phoneDisplay.value.replace(/\D/g, '');
            if (raw.length > 0 && !/^[67]/.test(raw)) {
                raw = raw.substring(1);
            }
            if (raw.length > 9) raw = raw.substring(0, 9);
            phoneDisplay.value = raw;
            if (/^[67]/.test(raw) && raw.length === 9) {
                phoneHidden.value = '255' + raw;
                phoneDisplay.classList.remove('border-red-300', 'bg-red-50/50');
                phoneDisplay.classList.add('border-gray-100', 'bg-gray-50/50');
            } else {
                phoneHidden.value = '';
            }
        }

        phoneDisplay.addEventListener('input', syncPhone);
        phoneDisplay.addEventListener('paste', function(e) {
            setTimeout(syncPhone, 0);
        });
        phoneDisplay.addEventListener('blur', syncPhone);

        if (form) {
            form.addEventListener('submit', function(e) {
                syncPhone();
                if (!phoneHidden.value || phoneHidden.value.length !== 12) {
                    e.preventDefault();
                    phoneDisplay.focus();
                    phoneDisplay.classList.add('border-red-300', 'bg-red-50/50');
                    phoneDisplay.classList.remove('border-gray-100', 'bg-gray-50/50');
                }
            });
        }

        syncPhone();
    }
})();
</script>
@endsection
