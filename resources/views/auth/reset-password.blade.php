<x-auth-shell
    title="Atur Ulang Kata Sandi"
    heading="Atur Ulang Kata Sandi"
    icon="password"
    containerWidth="max-w-5xl"
>
    <div class="space-y-6">
        <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
            @csrf

            <input type="hidden" name="token" value="{{ request()->route('token') }}">

            <div class="space-y-2">
                <x-input-label for="email" :value="__('Email')" class="ml-1" />
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-5 top-1/2 -translate-y-1/2 text-slate-400">alternate_email</span>
                    <x-text-input id="email" class="pl-14" type="email" name="email" :value="old('email', request('email'))" required autocomplete="username" placeholder="nama@email.com" />
                </div>
                <x-input-error :messages="$errors->get('email')" class="ml-1" />
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div class="space-y-2">
                    <x-input-label for="password" :value="__('Kata Sandi Baru')" class="ml-1" />
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-5 top-1/2 -translate-y-1/2 text-slate-400">lock</span>
                        <x-text-input id="password" class="pl-14" type="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter" />
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="ml-1" />
                </div>

                <div class="space-y-2">
                    <x-input-label for="password_confirmation" :value="__('Konfirmasi Kata Sandi')" class="ml-1" />
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-5 top-1/2 -translate-y-1/2 text-slate-400">shield_lock</span>
                        <x-text-input id="password_confirmation" class="pl-14" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi kata sandi" />
                    </div>
                </div>
            </div>

            <div class="rounded-[2rem] border border-blue-100 bg-gradient-to-r from-white via-blue-50 to-cyan-50 px-5 py-4 text-sm leading-relaxed text-slate-600">
                Gunakan kombinasi huruf, angka, dan simbol agar akun Anda lebih aman.
            </div>

            <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-blue-600 px-6 py-4 text-sm font-bold text-white shadow-lg shadow-blue-200 transition hover:bg-blue-700 active:scale-[0.98]">
                <span class="material-symbols-outlined text-[20px]">save</span>
                Simpan Kata Sandi
            </button>
        </form>

        <a href="{{ route('password.request') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-600 transition hover:text-blue-700">
            <span class="material-symbols-outlined text-[18px]">refresh</span>
            Minta tautan baru
        </a>
    </div>
</x-auth-shell>
