<x-auth-shell
    title="Masuk"
    heading="Masuk"
    icon="login"
>
    <x-slot name="aside">
        <div class="space-y-4">
            <h1 class="text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl">Akses cepat ke dashboard dengan tema yang sama.</h1>
            <p class="max-w-xl text-base leading-relaxed text-slate-600">
                Halaman masuk kini mengikuti identitas visual portal utama agar perpindahan dari halaman tamu ke dashboard terasa lebih mulus dan konsisten.
            </p>
        </div>

        <div class="grid gap-4">
            <div class="rounded-[2rem] border border-blue-100 bg-white/80 p-5">
                <p class="text-xs font-black uppercase tracking-[0.2em] text-blue-700">Aksi Utama</p>
                <p class="mt-2 text-sm leading-relaxed text-slate-600">Tombol primer menggunakan biru portal sebagai titik fokus utama.</p>
            </div>
            <div class="rounded-[2rem] border border-blue-100 bg-white/80 p-5">
                <p class="text-xs font-black uppercase tracking-[0.2em] text-blue-700">Akses Aman</p>
                <p class="mt-2 text-sm leading-relaxed text-slate-600">Masuk dengan akun resmi untuk membuka fitur lengkap dan pengelolaan hasil validasi.</p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <x-auth-session-status :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <div class="space-y-2">
                <x-input-label for="email" :value="__('Email Resmi')" class="ml-1" />
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-5 top-1/2 -translate-y-1/2 text-slate-400">mail</span>
                    <x-text-input id="email" class="pl-14" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="admin@sultengprov.go.id" />
                </div>
                <x-input-error :messages="$errors->get('email')" class="ml-1" />
            </div>

            <div class="space-y-2">
                <x-input-label for="password" :value="__('Kata Sandi')" class="ml-1" />
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-5 top-1/2 -translate-y-1/2 text-slate-400">lock</span>
                    <x-text-input id="password" class="pl-14 pr-14" type="password" name="password" required autocomplete="current-password" placeholder="Masukkan kata sandi Anda" />
                    <button
                        type="button"
                        class="absolute right-4 top-1/2 inline-flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-full text-slate-400 transition hover:bg-slate-100 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-200"
                        data-password-toggle="password"
                        aria-label="Lihat kata sandi"
                        aria-pressed="false"
                    >
                        <span class="material-symbols-outlined text-[20px]" aria-hidden="true">visibility</span>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="ml-1" />
            </div>

            <div class="flex items-center justify-between">
                <label class="inline-flex items-center gap-3 text-sm text-slate-600">
                    <input type="checkbox" name="remember" class="rounded border-slate-300 text-blue-600 focus:ring-blue-300">
                    <span>Ingat saya di perangkat ini</span>
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm font-bold text-blue-700 transition hover:text-blue-800">
                        Lupa kata sandi?
                    </a>
                @endif
            </div>

            <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-blue-600 px-6 py-4 text-sm font-bold text-white shadow-lg shadow-blue-200 transition hover:bg-blue-700 active:scale-[0.98]">
                Masuk
            </button>
             <p class="text-sm text-slate-600 text-center">
                Belum punya akun?
                <a href="{{ route('register') }}" class="font-bold text-blue-700 transition hover:text-blue-800">Daftar sekarang</a>
            </p>
        </form>
           
    </div>

    <script>
        document.querySelectorAll('[data-password-toggle]').forEach((button) => {
            button.addEventListener('click', () => {
                const input = document.getElementById(button.dataset.passwordToggle);
                const icon = button.querySelector('.material-symbols-outlined');
                const isHidden = input.type === 'password';

                input.type = isHidden ? 'text' : 'password';
                button.setAttribute('aria-label', isHidden ? 'Sembunyikan kata sandi' : 'Lihat kata sandi');
                button.setAttribute('aria-pressed', String(isHidden));
                icon.textContent = isHidden ? 'visibility_off' : 'visibility';
            });
        });
    </script>
</x-auth-shell>
