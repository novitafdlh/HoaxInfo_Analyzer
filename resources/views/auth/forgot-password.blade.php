<x-auth-shell
    title="Lupa Kata Sandi"
    heading="Lupa Kata Sandi?"
    description="Masukkan email akun Anda untuk menerima tautan pengaturan ulang kata sandi."
    icon="lock_reset"
    containerWidth="max-w-5xl"
>
    <div class="space-y-6">
        <x-auth-session-status :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
            @csrf

            <div class="space-y-2">
                <x-input-label for="email" :value="__('Alamat Email')" class="ml-1" />
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-5 top-1/2 -translate-y-1/2 text-slate-400">alternate_email</span>
                    <x-text-input id="email" class="pl-14" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="nama@email.com" />
                </div>
                <x-input-error :messages="$errors->get('email')" class="ml-1" />
            </div>

            <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-blue-600 px-6 py-4 text-sm font-bold text-white shadow-lg shadow-blue-200 transition hover:bg-blue-700 active:scale-[0.98]">
                <span class="material-symbols-outlined text-[20px]">mail</span>
                Kirim Tautan Reset
            </button>
        </form>

        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-600 transition hover:text-blue-700">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span>
            Kembali ke login
        </a>
    </div>
</x-auth-shell>
