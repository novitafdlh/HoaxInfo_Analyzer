@php
    $summaryCards = [
        ['label' => 'Total Submission', 'value' => number_format($managedUser->submissions_count), 'icon' => 'inbox'],
        ['label' => 'Terverifikasi', 'value' => number_format($managedUser->verified_submissions_count), 'icon' => 'verified'],
        ['label' => 'Menunggu Review', 'value' => number_format($managedUser->pending_submissions_count), 'icon' => 'pending_actions'],
    ];
@endphp

<x-admin-shell title="Detail User Admin">
    <x-slot name="pageHeader">
        <section class="space-y-4">
            <div class="flex flex-col gap-3 xl:flex-row xl:items-end xl:justify-between">
                <div>
                    <h1 class="text-4xl font-bold tracking-tight text-on-surface">Kelola Akun User</h1>
                    <p class="mt-2 text-lg text-on-surface-variant">Pantau profil, status verifikasi email, dan histori submission untuk akun pengguna ini.</p>
                </div>
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-700 shadow-sm transition hover:bg-slate-50">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                    Kembali ke daftar user
                </a>
            </div>
        </section>
    </x-slot>

    <div class="space-y-6">
        @if (session('success'))
            <div class="flex items-center gap-3 rounded-[1.5rem] border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-800 shadow-sm">
                <span class="material-symbols-outlined text-[22px] text-emerald-600">check_circle</span>
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-[1.5rem] border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-800 shadow-sm">
                <ul class="list-disc space-y-1 pl-5 font-medium">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="grid gap-4 md:grid-cols-3">
            @foreach ($summaryCards as $card)
                <div class="rounded-[2rem] border border-slate-200 bg-white p-5 shadow-[0px_20px_40px_rgba(25,28,30,0.06)]">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-black uppercase tracking-[0.18em] text-slate-500">{{ $card['label'] }}</p>
                        <span class="material-symbols-outlined text-slate-400">{{ $card['icon'] }}</span>
                    </div>
                    <p class="mt-4 text-3xl font-black tracking-tight text-slate-950">{{ $card['value'] }}</p>
                </div>
            @endforeach
        </section>

        <div class="grid gap-6 xl:grid-cols-[minmax(0,1.15fr)_minmax(0,0.85fr)]">
            <section class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-[0px_20px_40px_rgba(25,28,30,0.06)]">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-xs font-black uppercase tracking-[0.18em] text-slate-500">Profil User</p>
                        <h2 class="mt-1 text-2xl font-black tracking-tight text-slate-950">{{ $managedUser->name }}</h2>
                        <p class="mt-2 text-sm text-slate-500">{{ $managedUser->email }}</p>
                    </div>
                    <span class="inline-flex items-center rounded-full px-4 py-2 text-xs font-black {{ $managedUser->email_verified_at ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                        {{ $managedUser->email_verified_at ? 'Email terverifikasi' : 'Belum verifikasi email' }}
                    </span>
                </div>

                <form method="POST" action="{{ route('admin.users.update', $managedUser) }}" class="mt-8 space-y-5">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label for="name" class="text-sm font-bold text-slate-800">Nama user</label>
                        <input id="name" name="name" type="text" value="{{ old('name', $managedUser->name) }}" class="mt-2 block w-full rounded-[1.25rem] border-slate-200 bg-slate-50/60 py-3.5 shadow-sm transition duration-150 focus:border-blue-500 focus:bg-white focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="email" class="text-sm font-bold text-slate-800">Email user</label>
                        <input id="email" name="email" type="email" value="{{ old('email', $managedUser->email) }}" class="mt-2 block w-full rounded-[1.25rem] border-slate-200 bg-slate-50/60 py-3.5 shadow-sm transition duration-150 focus:border-blue-500 focus:bg-white focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="email_verification_status" class="text-sm font-bold text-slate-800">Status verifikasi email</label>
                        <select id="email_verification_status" name="email_verification_status" class="mt-2 block w-full rounded-[1.25rem] border-slate-200 bg-slate-50/60 py-3.5 shadow-sm transition duration-150 focus:border-blue-500 focus:bg-white focus:ring-blue-500">
                            <option value="verified" @selected(old('email_verification_status', $managedUser->email_verified_at ? 'verified' : 'unverified') === 'verified')>Terverifikasi</option>
                            <option value="unverified" @selected(old('email_verification_status', $managedUser->email_verified_at ? 'verified' : 'unverified') === 'unverified')>Belum terverifikasi</option>
                        </select>
                    </div>

                    <div class="rounded-[1.5rem] border border-blue-100 bg-blue-50/70 px-4 py-3 text-sm leading-relaxed text-blue-900">
                        Admin hanya dapat mengelola akun dengan role <span class="font-black">user</span> di halaman ini. Tidak ada fitur untuk membuat atau mengubah akun menjadi admin.
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-slate-900 px-6 py-3.5 text-sm font-black text-white shadow-lg shadow-slate-300/40 transition hover:bg-slate-800">
                            <span class="material-symbols-outlined text-[18px]">save</span>
                            Simpan perubahan
                        </button>
                    </div>
                </form>
            </section>

            <section class="space-y-6">
                <div class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-[0px_20px_40px_rgba(25,28,30,0.06)]">
                    <p class="text-xs font-black uppercase tracking-[0.18em] text-slate-500">Info Akun</p>
                    <div class="mt-5 space-y-4 text-sm">
                        <div>
                            <div class="font-bold text-slate-900">Bergabung sejak</div>
                            <div class="mt-1 text-slate-600">{{ $managedUser->created_at?->format('d M Y, H:i') ?: '-' }}</div>
                        </div>
                        <div>
                            <div class="font-bold text-slate-900">Update terakhir</div>
                            <div class="mt-1 text-slate-600">{{ $managedUser->updated_at?->format('d M Y, H:i') ?: '-' }}</div>
                        </div>
                        <div>
                            <div class="font-bold text-slate-900">Role akun</div>
                            <div class="mt-1 text-slate-600">user</div>
                        </div>
                    </div>
                </div>

                <div class="rounded-[2rem] border border-rose-200 bg-white p-6 shadow-[0px_20px_40px_rgba(25,28,30,0.06)]">
                    <p class="text-xs font-black uppercase tracking-[0.18em] text-rose-500">Zona Hati-Hati</p>
                    <h3 class="mt-2 text-xl font-black tracking-tight text-slate-950">Hapus akun user</h3>
                    <p class="mt-3 text-sm leading-relaxed text-slate-600">
                        Penghapusan akun akan melepaskan relasi submission milik user ini dari akunnya, menghapus notifikasi user, dan menutup sesi login yang masih aktif.
                    </p>

                    <form method="POST" action="{{ route('admin.users.destroy', $managedUser) }}" class="mt-5" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun user ini? Aksi ini tidak dapat dibatalkan.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-rose-600 px-5 py-3 text-sm font-black text-white shadow-lg shadow-rose-200/70 transition hover:bg-rose-700">
                            <span class="material-symbols-outlined text-[18px]">delete</span>
                            Hapus akun user
                        </button>
                    </form>
                </div>
            </section>
        </div>

        <section class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-[0px_20px_40px_rgba(25,28,30,0.06)]">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-xs font-black uppercase tracking-[0.18em] text-slate-500">Aktivitas Terkini</p>
                    <h2 class="mt-1 text-2xl font-black tracking-tight text-slate-950">Submission terbaru dari user</h2>
                </div>
            </div>

            <div class="mt-6 overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100">
                    <thead class="bg-slate-50/80">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-black uppercase tracking-[0.18em] text-slate-500">Gambar</th>
                            <th class="px-4 py-3 text-left text-xs font-black uppercase tracking-[0.18em] text-slate-500">Similarity</th>
                            <th class="px-4 py-3 text-left text-xs font-black uppercase tracking-[0.18em] text-slate-500">Status Final</th>
                            <th class="px-4 py-3 text-left text-xs font-black uppercase tracking-[0.18em] text-slate-500">Tanggal</th>
                            <th class="px-4 py-3 text-left text-xs font-black uppercase tracking-[0.18em] text-slate-500">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($recentSubmissions as $submission)
                            <tr class="hover:bg-slate-50/80">
                                <td class="px-4 py-4">
                                    <img src="{{ asset('storage/'.$submission->image_path) }}" alt="Submission" class="h-14 w-20 rounded-xl border border-slate-200 object-cover shadow-sm">
                                </td>
                                <td class="px-4 py-4 text-sm font-bold text-slate-900">
                                    {{ $submission->similarity_score !== null ? $submission->similarity_score.'%' : '-' }}
                                </td>
                                <td class="px-4 py-4 text-sm text-slate-700">
                                    {{ $submission->finalStatusLabel() }}
                                </td>
                                <td class="px-4 py-4 text-sm text-slate-600">
                                    {{ $submission->created_at?->format('d M Y, H:i') ?: '-' }}
                                </td>
                                <td class="px-4 py-4 text-sm">
                                    <a href="{{ route('admin.submissions.show', $submission) }}" class="inline-flex items-center gap-2 rounded-full border border-slate-200 px-4 py-2 text-xs font-black text-slate-700 transition hover:bg-slate-50">
                                        <span class="material-symbols-outlined text-[18px]">open_in_new</span>
                                        Buka submission
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-10 text-center text-sm text-slate-500">
                                    User ini belum memiliki submission.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-admin-shell>
