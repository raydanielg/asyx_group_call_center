@extends('layouts.app')

@section('title', 'New Password - AYS Call Center')

@section('content')
<div class="w-full max-w-[440px]" style="animation: simpleFadeIn 0.5s ease-out both;">
    <div class="bg-white/95 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 overflow-hidden">
        {{-- Header --}}
        <div class="relative bg-gradient-to-br from-navy-600 via-navy-700 to-navy-800 px-8 pt-8 pb-10 text-center overflow-hidden">
            <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(rgba(255,255,255,0.2) 1px, transparent 1px); background-size: 20px 20px;"></div>
            <div class="absolute -top-12 -left-12 w-40 h-40 rounded-full bg-copper-400/10 blur-2xl"></div>
            <div class="relative">
                <div class="w-16 h-16 mx-auto bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center mb-4 ring-1 ring-white/20">
                    <img src="{{ asset('forgotpass.png') }}" alt="Reset Password" class="w-10 h-10 object-contain">
                </div>
                <h2 class="text-2xl font-extrabold text-white tracking-tight">New Password</h2>
                <p class="text-navy-200 text-sm mt-1.5 font-medium">Create a new secure password</p>
            </div>
        </div>

        {{-- Form --}}
        <div class="p-8">
            <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Email Address</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors">
                            <svg class="w-5 h-5 text-gray-300 group-focus-within:text-navy-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                        </div>
                        <input id="email" type="email" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus
                            class="w-full pl-12 pr-4 py-3 rounded-xl border-2 @error('email') border-red-300 bg-red-50/50 @else border-gray-100 bg-gray-50/50 @enderror focus:border-navy-500 focus:bg-white focus:ring-0 outline-none transition-all text-sm font-medium placeholder:text-gray-300"
                            placeholder="you@example.com">
                    </div>
                    @error('email')
                        <p class="mt-2 text-xs text-red-600 flex items-center gap-1 font-medium"><svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">New Password</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors">
                            <svg class="w-5 h-5 text-gray-300 group-focus-within:text-navy-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <input id="password" type="password" name="password" required autocomplete="new-password"
                            class="w-full pl-12 pr-12 py-3 rounded-xl border-2 @error('password') border-red-300 bg-red-50/50 @else border-gray-100 bg-gray-50/50 @enderror focus:border-navy-500 focus:bg-white focus:ring-0 outline-none transition-all text-sm font-medium placeholder:text-gray-300"
                            placeholder="Create a strong password">
                        <button type="button" onclick="togglePassword('password', this)" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-300 hover:text-navy-500 transition-colors" aria-label="Show password">
                            <svg class="w-5 h-5 eye-open" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg class="w-5 h-5 eye-closed hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 015.536-6.988M5.05 5.05a10.05 10.05 0 016.95-2.05c4.478 0 8.268 2.943 9.542 7a10.05 10.05 0 01-4.043 5.97M3 3l18 18"/></svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-2 text-xs text-red-600 flex items-center gap-1 font-medium"><svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label for="password-confirm" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Confirm Password</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors">
                            <svg class="w-5 h-5 text-gray-300 group-focus-within:text-navy-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <input id="password-confirm" type="password" name="password_confirmation" required autocomplete="new-password"
                            class="w-full pl-12 pr-12 py-3 rounded-xl border-2 border-gray-100 bg-gray-50/50 focus:border-navy-500 focus:bg-white focus:ring-0 outline-none transition-all text-sm font-medium placeholder:text-gray-300"
                            placeholder="Confirm your password">
                        <button type="button" onclick="togglePassword('password-confirm', this)" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-300 hover:text-navy-500 transition-colors" aria-label="Show password">
                            <svg class="w-5 h-5 eye-open" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg class="w-5 h-5 eye-closed hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 015.536-6.988M5.05 5.05a10.05 10.05 0 016.95-2.05c4.478 0 8.268 2.943 9.542 7a10.05 10.05 0 01-4.043 5.97M3 3l18 18"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Submit --}}
                <button type="submit" class="w-full py-3.5 text-sm font-bold text-white bg-gradient-to-r from-navy-600 to-navy-700 hover:from-navy-700 hover:to-navy-800 rounded-xl shadow-lg shadow-navy-600/20 hover:shadow-xl hover:shadow-navy-600/30 transition-all flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Reset Password
                </button>
            </form>
        </div>
    </div>

    <p class="mt-6 text-center text-xs text-white/60 font-medium">&copy; {{ date('Y') }} AYS Call Center. All rights reserved.</p>
</div>

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
</script>
@endsection
