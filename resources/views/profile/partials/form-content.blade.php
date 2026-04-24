<section class="overflow-hidden">

        @if (session('status') === 'profile-updated')
            <div class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-4 py-2 text-sm font-semibold text-emerald-700">
                <span class="material-symbols-outlined text-[18px]">check_circle</span>
                <span>Profil berhasil diperbarui.</span>
            </div>
        @endif

        @if (session('status') === 'password-updated')
            <div class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-4 py-2 text-sm font-semibold text-emerald-700">
                <span class="material-symbols-outlined text-[18px]">lock</span>
                <span>Kata sandi berhasil diperbarui.</span>
            </div>
        @endif
</section>

<section class="mt-8 grid gap-8 xl:grid-cols-[1.45fr,0.95fr]">
    <div class="space-y-8">
        <div class="rounded-[2rem] bg-white p-6 shadow-[0px_20px_40px_rgba(25,28,30,0.06)] sm:p-8">
            <div class="mb-8 flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight text-slate-900">Informasi Profil</h2>
                    <p class="mt-2 max-w-2xl text-sm leading-relaxed text-slate-500">
                        Perbarui nama dan email yang digunakan untuk identitas akun.
                    </p>
                </div>
                <span class="material-symbols-outlined text-4xl text-slate-200">badge</span>
            </div>

            <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
                @csrf
                @method('patch')

                <div class="grid gap-6 md:grid-cols-2">
                    <div class="space-y-2">
                        <label class="ml-1 text-sm font-semibold text-slate-600" for="name">Nama Lengkap</label>
                        <input
                            id="name"
                            name="name"
                            type="text"
                            value="{{ old('name', $user->name) }}"
                            required
                            autofocus
                            autocomplete="name"
                            class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4 text-sm text-slate-900 transition focus:border-blue-400 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-100"
                        >
                        @error('name')
                            <p class="text-sm font-medium text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="ml-1 text-sm font-semibold text-slate-600" for="email">Alamat Email</label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email', $user->email) }}"
                            required
                            autocomplete="username"
                            class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4 text-sm text-slate-900 transition focus:border-blue-400 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-100"
                        >
                        @error('email')
                            <p class="text-sm font-medium text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="rounded-2xl border border-amber-200 bg-amber-50 px-5 py-4 text-sm text-amber-800">
                        <p class="font-semibold">Alamat email belum terverifikasi.</p>
                        <button
                            form="send-verification"
                            class="mt-3 inline-flex items-center gap-2 rounded-full bg-amber-600 px-4 py-2 text-xs font-bold uppercase tracking-[0.14em] text-white transition hover:bg-amber-700"
                        >
                            <span class="material-symbols-outlined text-[16px]">mark_email_unread</span>
                            <span>Kirim Ulang Verifikasi</span>
                        </button>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-3 font-medium text-emerald-700">
                                Link verifikasi baru telah dikirim ke email Anda.
                            </p>
                        @endif
                    </div>
                @endif

                <div class="flex justify-end">
                    <button class="inline-flex items-center gap-2 rounded-full bg-green-700 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-green-300/40 transition hover:bg-green-800" type="submit">
                        <span class="material-symbols-outlined text-[18px]">save</span>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </form>

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail)
                <form id="send-verification" method="post" action="{{ route('verification.send') }}" class="hidden">
                    @csrf
                </form>
            @endif
        </div>

        <div class="rounded-[2rem] bg-white p-6 shadow-[0px_20px_40px_rgba(25,28,30,0.06)] sm:p-8">
            <div class="mb-8 flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight text-slate-900">Perbarui Kata Sandi</h2>
                    <p class="mt-2 max-w-2xl text-sm leading-relaxed text-slate-500">
                        Gunakan kata sandi yang kuat dan unik untuk menjaga akses akun tetap terlindungi.
                    </p>
                </div>
                <span class="material-symbols-outlined text-4xl text-slate-200">lock</span>
            </div>

            <form method="post" action="{{ route('password.update') }}" class="space-y-6">
                @csrf
                @method('put')

                <div class="space-y-2">
                    <label class="ml-1 text-sm font-semibold text-slate-600" for="update_password_current_password">Kata Sandi Saat Ini</label>
                    <input
                        id="update_password_current_password"
                        name="current_password"
                        type="password"
                        autocomplete="current-password"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4 text-sm text-slate-900 transition focus:border-blue-400 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-100"
                    >
                    @error('current_password', 'updatePassword')
                        <p class="text-sm font-medium text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid gap-6 md:grid-cols-2">
                    <div class="space-y-2">
                        <label class="ml-1 text-sm font-semibold text-slate-600" for="update_password_password">Kata Sandi Baru</label>
                        <input
                            id="update_password_password"
                            name="password"
                            type="password"
                            autocomplete="new-password"
                            class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4 text-sm text-slate-900 transition focus:border-blue-400 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-100"
                        >
                        @error('password', 'updatePassword')
                            <p class="text-sm font-medium text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="ml-1 text-sm font-semibold text-slate-600" for="update_password_password_confirmation">Konfirmasi Kata Sandi</label>
                        <input
                            id="update_password_password_confirmation"
                            name="password_confirmation"
                            type="password"
                            autocomplete="new-password"
                            class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4 text-sm text-slate-900 transition focus:border-blue-400 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-100"
                        >
                    </div>
                </div>

                <div class="flex justify-end">
                    <button class="inline-flex items-center gap-2 rounded-full bg-slate-900 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-slate-300/40 transition hover:bg-slate-800" type="submit">
                        <span class="material-symbols-outlined text-[18px]">vpn_key</span>
                        <span>Perbarui Keamanan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="space-y-8">
        <div class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-[0px_20px_40px_rgba(25,28,30,0.06)] sm:p-8">
            <div class="flex flex-col items-center text-center">
                <div class="flex h-24 w-24 items-center justify-center rounded-full bg-gradient-to-br from-slate-800 to-slate-600 text-3xl font-black tracking-tight text-white shadow-xl shadow-slate-300/50">
                    {{ collect(preg_split('/\s+/', trim($user->name ?: 'Admin')))->filter()->take(2)->map(fn ($part) => strtoupper(substr($part, 0, 1)))->implode('') ?: 'AD' }}
                </div>
                <h2 class="mt-5 text-2xl font-bold text-slate-900">{{ $user->name }}</h2>
                <p class="mt-1 text-sm text-slate-500">{{ $user->email }}</p>
                <div class="mt-4 inline-flex items-center gap-2 rounded-full {{ $adminMode ? 'bg-blue-50 text-blue-700' : 'bg-slate-100 text-slate-700' }} px-4 py-2 text-xs font-bold uppercase tracking-[0.18em]">
                    <span class="material-symbols-outlined text-[16px]">{{ $adminMode ? 'admin_panel_settings' : 'person' }}</span>
                    <span>{{ $adminMode ? 'Administrator' : 'Pengguna' }}</span>
                </div>
            </div>

            <div class="mt-4 space-y-4">
                <div class="rounded-2xl bg-slate-50 px-5 py-4">
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Status Email</p>
                    <p class="mt-2 text-sm font-semibold text-slate-900">
                        {{ $user->hasVerifiedEmail() ? 'Terverifikasi' : 'Belum terverifikasi' }}
                    </p>
                </div>
                <div class="rounded-2xl bg-slate-50 px-5 py-4">
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Terakhir Diperbarui</p>
                    <p class="mt-2 text-sm font-semibold text-slate-900">{{ optional($user->updated_at)->translatedFormat('d F Y, H:i') ?? '-' }}</p>
                </div>
            </div>
        </div>

        <div class="rounded-[2rem] border border-rose-200 bg-rose-50/80 p-2 shadow-[0px_20px_40px_rgba(25,28,30,0.05)] sm:p-8">

            <form method="post" action="{{ route('profile.destroy') }}" class="space-y-2">
                @csrf
                @method('delete')
                <div class="space-y-2">
                    <label class="ml-1 text-sm font-semibold text-rose-900" for="delete_password">Konfirmasi Kata Sandi</label>
                    <input
                        id="delete_password"
                        name="password"
                        type="password"
                        autocomplete="current-password"
                        placeholder="Masukkan kata sandi untuk menghapus akun"
                        class="w-full rounded-2xl border border-rose-200 bg-white px-5 py-4 text-sm text-slate-900 transition focus:border-rose-400 focus:outline-none focus:ring-4 focus:ring-rose-100"
                    >
                    @error('password', 'userDeletion')
                        <p class="text-sm font-medium text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <button
                    type="submit"
                    class="inline-flex items-center gap-2 rounded-full bg-rose-600 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-rose-200 transition hover:bg-rose-700"
                    onclick="return confirm('Yakin ingin menghapus akun secara permanen?')"
                >
                    <span class="material-symbols-outlined text-[18px]">delete_forever</span>
                    <span>Hapus Akun Permanen</span>
                </button>
            </form>
        </div>
    </div>
</section>
