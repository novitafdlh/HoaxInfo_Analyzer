<x-auth-shell
    title="Daftar"
    heading="Daftarkan akun anda"
    icon="person_add"
>
    <x-slot name="aside">
        <div class="space-y-4">
            <h1 class="text-5xl font-black tracking-tight text-slate-900">Mulai dari halaman daftar yang terasa satu tema dengan portal.</h1>
            <p class="max-w-xl text-base leading-relaxed text-slate-600">
                Halaman pendaftaran memakai bahasa visual yang sama dengan dashboard agar alur dari tamu menjadi pengguna terdaftar terasa lebih jelas dan profesional.
            </p>
        </div>

        <div class="grid gap-4">
            <div class="rounded-[2rem] border border-blue-100 bg-white/80 p-5">
                <p class="text-xs font-black uppercase tracking-[0.2em] text-blue-700">Profil Pengguna</p>
                <p class="mt-2 text-sm leading-relaxed text-slate-600">Akun baru dapat digunakan untuk mengelola validasi dan membuka daftar konten resmi.</p>
            </div>
            <div class="rounded-[2rem] border border-blue-100 bg-white/80 p-5">
                <p class="text-xs font-black uppercase tracking-[0.2em] text-blue-700">Warna Aksi</p>
                <p class="mt-2 text-sm leading-relaxed text-slate-600">Tombol utama memakai biru portal, sedangkan aksi berisiko tinggi akan memakai merah.</p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <form method="POST" action="{{ route('register') }}" class="space-y-6">
            @csrf

            <div class="grid gap-6 md:grid-cols-2">
                <div class="space-y-2 md:col-span-2">
                    <x-input-label for="name" :value="__('Nama Lengkap')" class="ml-1" />
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-5 top-1/2 -translate-y-1/2 text-slate-400">person</span>
                        <x-text-input id="name" class="pl-14" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Nama lengkap Anda" />
                    </div>
                    <x-input-error :messages="$errors->get('name')" class="ml-1" />
                </div>

                <div class="space-y-2 md:col-span-2">
                    <x-input-label for="email" :value="__('Email Resmi')" class="ml-1" />
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-5 top-1/2 -translate-y-1/2 text-slate-400">alternate_email</span>
                        <x-text-input id="email" class="pl-14" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="nama@sulteng.go.id" />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="ml-1" />
                </div>

                <div class="space-y-2">
                    <x-input-label for="password" :value="__('Kata Sandi')" class="ml-1" />
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

            <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-blue-600 px-6 py-4 text-sm font-bold text-white shadow-lg shadow-blue-200 transition hover:bg-blue-700 active:scale-[0.98]">
                <span class="material-symbols-outlined text-[20px]">arrow_forward</span>
                Daftar Sekarang
            </button>
        </form>

        <p class="text-center text-sm text-slate-600">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="font-bold text-blue-700 transition hover:text-blue-800">Masuk di sini</a>
        </p>
    </div>
</x-auth-shell>
