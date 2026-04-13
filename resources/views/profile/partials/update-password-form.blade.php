<section class="max-w-xl">
    {{-- Header dengan Spacing Modern & Ikon Keamanan --}}
    <header class="pb-6 border-b border-slate-100 mb-8 flex items-start gap-4">
        {{-- Ikon Gembok Kunci (Opsional) --}}
        <div class="p-2.5 bg-rose-50 rounded-xl text-rose-600 shadow-inner">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
            </svg>
        </div>
        <div>
            <h2 class="text-xl font-bold text-slate-950 tracking-tight">
                {{ __('Perbarui Kata Sandi') }}
            </h2>

            <p class="mt-2 text-sm text-slate-600 leading-relaxed">
                {{ __('Pastikan akun Anda menggunakan kata sandi yang panjang dan acak agar tetap aman.') }}
            </p>
        </div>
    </header>

    {{-- Form dengan Spacing Modern --}}
    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        {{-- Input Kata Sandi Saat Ini --}}
        <div>
            <x-input-label for="update_password_current_password" :value="__('Kata Sandi Saat Ini')" class="font-medium text-slate-700 ml-1" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1.5 block w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm py-2.5 transition duration-150" autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2 ml-1" />
        </div>

        {{-- Input Kata Sandi Baru --}}
        <div>
            <x-input-label for="update_password_password" :value="__('Kata Sandi Baru')" class="font-medium text-slate-700 ml-1" />
            <x-text-input id="update_password_password" name="password" type="password" class="mt-1.5 block w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm py-2.5 transition duration-150" autocomplete="new-password" placeholder="Minimal 8 karakter" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2 ml-1" />
        </div>

        {{-- Input Konfirmasi Kata Sandi --}}
        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Konfirmasi Kata Sandi Baru')" class="font-medium text-slate-700 ml-1" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1.5 block w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm py-2.5 transition duration-150" autocomplete="new-password" placeholder="Ulangi kata sandi baru" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2 ml-1" />
        </div>

        {{-- Bagian Tombol Simpan & Notifikasi Sukses --}}
        <div class="flex items-center gap-5 pt-4 border-t border-slate-100">
            <x-primary-button class="bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-900 rounded-xl px-6 py-3 font-bold text-sm tracking-wide">
                {{ __('Ubah Kata Sandi') }}
            </x-primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-x-1"
                    x-transition:enter-end="opacity-100 translate-x-0"
                    x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100 translate-x-0"
                    x-transition:leave-end="opacity-0 translate-x-1"
                    x-init="setTimeout(() => show = false, 2500)"
                    class="text-sm text-green-700 font-medium flex items-center gap-2"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ __('Berhasil disimpan.') }}
                </p>
            @endif
        </div>
    </form>
</section>