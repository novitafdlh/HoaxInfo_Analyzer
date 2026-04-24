<x-auth-shell
    title="Konfirmasi Kata Sandi"
    heading="Konfirmasi Kata Sandi"
    description="Langkah ini diperlukan sebelum Anda melanjutkan ke area yang lebih sensitif di dalam sistem."
    icon="lock_person"
    containerWidth="max-w-5xl"
>
    <div class="space-y-6">
        @if ($errors->any())
            <div class="rounded-[1.5rem] border border-rose-200 bg-rose-50 px-5 py-4 text-sm font-semibold text-rose-800">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
            @csrf

            <div class="space-y-2">
                <x-input-label for="password" :value="__('Kata Sandi')" class="ml-1" />
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-5 top-1/2 -translate-y-1/2 text-slate-400">key</span>
                    <x-text-input id="password" class="pl-14" type="password" name="password" required autocomplete="current-password" placeholder="Masukkan kata sandi Anda" />
                </div>
            </div>

            <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-blue-600 px-6 py-4 text-sm font-bold text-white shadow-lg shadow-blue-200 transition hover:bg-blue-700 active:scale-[0.98]">
                <span class="material-symbols-outlined text-[20px]">check_circle</span>
                Konfirmasi Sekarang
            </button>
        </form>

        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-600 transition hover:text-blue-700">
                <span class="material-symbols-outlined text-[18px]">help</span>
                Lupa kata sandi?
            </a>
        @endif
    </div>
</x-auth-shell>
