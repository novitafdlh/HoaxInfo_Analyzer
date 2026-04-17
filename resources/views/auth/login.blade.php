<x-guest-layout>
        {{-- Logo & Judul Utama --}}
        
        {{-- Bagian Header (Logo & Judul) --}}
        <div class="mb-1 text-center">
            <div class="inline-flex p-3 bg-blue-600 rounded-2xl shadow-lg shadow-blue-200 mb-2">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04a11.357 11.357 0 00-1.573 11.37c1.29 3.56 4.308 6.399 8.01 7.326a11.75 11.75 0 003.364 0c3.701-.927 6.72-3.766 8.01-7.326a11.357 11.357 0 00-1.573-11.37z"></path>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-slate-900">Sulteng Info Clarifier</h1>
            <p class="text-sm text-slate-500 mt-1">Silakan masuk untuk mengakses panel klarifikasi informasi publik</p>
        </div>

        {{-- Card Login --}}
        <div class="w-full sm:max-w-md px-8 py-10 bg-white shadow-xl shadow-slate-200/50 rounded-3xl border border-slate-100">
            <x-auth-session-status class="mb-6" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- Input Email --}}
                <div class="space-y-1">
                    <x-input-label for="email" :value="__('Email Resmi')" class="text-slate-700 font-medium" />
                    <x-text-input id="email" 
                        class="block mt-1 w-full border-slate-200 focus:border-blue-500 focus:ring-blue-500 rounded-xl shadow-sm transition-all" 
                        type="email" 
                        name="email" 
                        :value="old('email')" 
                        required 
                        autofocus 
                        placeholder="admin@instansi.go.id"
                        autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs" />
                </div>

                {{-- Input Password --}}
                <div class="mt-6 space-y-1">
                    <div class="flex items-center justify-between">
                        <x-input-label for="password" :value="__('Kata Sandi')" class="text-slate-700 font-medium" />
                    </div>
                    <x-text-input id="password" 
                        class="block mt-1 w-full border-slate-200 focus:border-blue-500 focus:ring-blue-500 rounded-xl shadow-sm transition-all"
                        type="password"
                        name="password"
                        placeholder="••••••••"
                        required 
                        autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs" />
                </div>

                {{-- Ingat Saya & Lupa Sandi --}}
                <div class="flex items-center justify-between mt-6">
                    <label for="remember_me" class="inline-flex items-center cursor-pointer">
                        <input id="remember_me" type="checkbox" class="rounded-md border-slate-300 text-blue-600 shadow-sm focus:ring-blue-500 transition-colors" name="remember">
                        <span class="ms-2 text-sm text-slate-600">{{ __('Ingat saya') }}</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="text-sm font-medium text-blue-600 hover:text-blue-700 transition-colors" href="{{ route('password.request') }}">
                            {{ __('Lupa sandi?') }}
                        </a>
                    @endif
                </div>

                {{-- Tombol Masuk --}}
                <div class="mt-8">
                    <x-primary-button class="w-full justify-center py-3 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white rounded-xl font-bold tracking-wide shadow-lg shadow-blue-100 transition-all transform hover:-translate-y-0.5">
                        {{ __('Login') }}
                    </x-primary-button>
                </div>
            </form>
            {{-- Link Registrasi --}}
            <div class="mt-4 text-center">
                <p class="text-sm text-slate-500">Belum punya akun? 
                    <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:text-blue-700 transition-colors">Daftar sekarang</a>
                </p>
        </div>
                {{-- Teks Hak Cipta (Sekarang di Tengah-Tengah di Luar Card) --}}
                <p class="mt-4 text-center text-xs text-slate-500 LEADING-RELAXED">
                    &copy; {{ date('Y') }} Dinas Kominfo Santik Provinsi Sulawesi Tengah.<br>
                </p>
    </div>
</x-guest-layout>
