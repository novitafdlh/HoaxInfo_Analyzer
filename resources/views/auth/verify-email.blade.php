<x-auth-shell
    title="Verifikasi Email"
    heading="Verifikasi Email"
    description="Periksa kotak masuk email Anda lalu klik tautan verifikasi yang telah kami kirim."
    icon="mark_email_read"
    containerWidth="max-w-5xl"
>
    <div class="space-y-6">
        @if (session('status') == 'verification-link-sent')
            <div class="rounded-[1.5rem] border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-800">
                Link verifikasi baru sudah dikirim ke email Anda.
            </div>
        @endif

        <div class="rounded-[2rem] border border-slate-200 bg-slate-50/70 px-5 py-4 text-sm leading-relaxed text-slate-600">
            Setelah email terverifikasi, Anda bisa melanjutkan penggunaan seluruh fitur akun dengan lebih aman.
        </div>

        @if (\App\Models\User::usesFormalEmailVerification())
            <div class="rounded-[1.5rem] border border-amber-200 bg-amber-50 px-5 py-4 text-sm leading-relaxed text-amber-800">
                Mode testing aktif. Verifikasi email di halaman ini hanya formalitas, jadi akun testing tetap bisa dipakai walau email tidak benar-benar menerima tautan verifikasi.
            </div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-blue-600 px-6 py-4 text-sm font-bold text-white shadow-lg shadow-blue-200 transition hover:bg-blue-700 active:scale-[0.98]">
                <span class="material-symbols-outlined text-[20px]">send</span>
                Kirim Ulang Verifikasi
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}" data-logout-confirm data-confirm-title="Keluar dari akun {{ auth()->user()?->name ?: 'Anda' }}?" data-confirm-message="Anda belum menyelesaikan verifikasi email untuk {{ auth()->user()?->name ?: 'akun ini' }}. Apakah Anda yakin ingin keluar?">
            @csrf

            <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-rose-600 px-6 py-4 text-sm font-bold text-white shadow-lg shadow-rose-200 transition hover:bg-rose-700 active:scale-[0.98]">
                <span class="material-symbols-outlined text-[20px]">logout</span>
                Logout
            </button>
        </form>

        @if (\App\Models\User::usesFormalEmailVerification())
            <a
                href="{{ auth()->user()?->role === 'admin' ? route('admin.dashboard') : route('user.dashboard') }}"
                class="inline-flex w-full items-center justify-center gap-2 rounded-full border border-slate-200 bg-white px-6 py-4 text-sm font-bold text-slate-700 shadow-sm transition hover:bg-slate-50 active:scale-[0.98]"
            >
                <span class="material-symbols-outlined text-[20px]">arrow_forward</span>
                Lanjutkan Dalam Mode Testing
            </a>
        @endif
    </div>
</x-auth-shell>
