@php
    $summaryCards = [
        ['label' => 'Total Submission', 'value' => number_format($managedUser->submissions_count), 'icon' => 'inbox', 'tone' => 'border-blue-100 bg-blue-50/70 text-blue-700 shadow-blue-100/60'],
        ['label' => 'Terverifikasi', 'value' => number_format($managedUser->verified_submissions_count), 'icon' => 'verified', 'tone' => 'border-emerald-100 bg-emerald-50/70 text-emerald-700 shadow-emerald-100/60'],
        ['label' => 'Menunggu Review', 'value' => number_format($managedUser->pending_submissions_count), 'icon' => 'pending_actions', 'tone' => 'border-amber-100 bg-amber-50/70 text-amber-700 shadow-amber-100/60'],
    ];
@endphp

<x-admin-shell title="Detail User Admin">
    <x-slot name="pageHeader">
        <section class="space-y-4">
            <div class="flex flex-col gap-3 xl:flex-row xl:items-start xl:justify-between">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight text-on-surface">Kelola Akun User</h1>
                    <p class="mt-2 text-base leading-relaxed text-on-surface-variant">Pantau profil, status verifikasi email, dan histori submission untuk akun pengguna ini.</p>
                </div>
                <a href="{{ route('admin.users.index') }}" class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-blue-600 text-white shadow-lg shadow-blue-100 transition hover:bg-blue-700" aria-label="Kembali ke daftar user" title="Kembali ke daftar user">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span>
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
                <div class="rounded-[2rem] border border-slate-200 bg-white p-5 shadow-[0px_20px_40px_rgba(25,28,30,0.06)] transition hover:-translate-y-1 hover:shadow-[0px_24px_44px_rgba(37,99,235,0.10)]">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-bold text-slate-600">{{ $card['label'] }}</p>
                        <span class="material-symbols-outlined rounded-full border p-2 shadow-sm {{ $card['tone'] }}">{{ $card['icon'] }}</span>
                    </div>
                    <p class="mt-4 text-2xl font-black tracking-tight text-slate-950">{{ $card['value'] }}</p>
                </div>
            @endforeach
        </section>

        <div class="grid gap-6 xl:grid-cols-[minmax(0,1.15fr)_minmax(0,0.85fr)]">
            <section class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-[0px_20px_40px_rgba(25,28,30,0.06)]">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="mb-3 text-sm font-bold text-slate-600">Profil User</p>
                        <div class="flex items-end gap-4">
                            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-blue-50 text-lg font-black text-blue-700">
                                {{ strtoupper(substr($managedUser->name ?: $managedUser->email, 0, 1)) }}
                            </div>
                            <div>
                                <h2 class="text-xl font-black tracking-tight text-slate-950">{{ $managedUser->name }}</h2>
                                <p class="mt-1 text-sm text-slate-500">{{ $managedUser->email }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 sm:justify-end">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full {{ $managedUser->email_verified_at ? 'bg-blue-50 text-blue-700' : 'bg-amber-50 text-amber-700' }}">
                            <span class="material-symbols-outlined text-[20px]">
                                {{ $managedUser->email_verified_at ? 'verified' : 'mark_email_unread' }}
                            </span>
                        </div>
                        <div>
                            <span class="inline-flex rounded-full border px-4 py-2 text-xs font-black {{ $managedUser->email_verified_at ? 'border-blue-100 bg-blue-50 text-blue-700' : 'border-amber-100 bg-amber-50 text-amber-700' }}">
                                {{ $managedUser->email_verified_at ? 'Email terverifikasi' : 'Belum verifikasi email' }}
                            </span>
                            @if ($managedUser->email_verified_at)
                                <p class="mt-2 text-xs font-medium text-slate-500">{{ $managedUser->email_verified_at->format('d M Y, H:i') }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.users.update', $managedUser) }}" class="mt-8 space-y-5">
                    @csrf
                    @method('PATCH')

                    <div class="grid gap-5 lg:grid-cols-2">
                        <div>
                            <label for="name" class="text-sm font-bold text-slate-800">Nama user</label>
                            <div class="relative mt-2">
                                <span class="material-symbols-outlined pointer-events-none absolute left-5 top-1/2 -translate-y-1/2 text-[20px] text-slate-400">person</span>
                                <input id="name" name="name" type="text" value="{{ old('name', $managedUser->name) }}" class="block w-full rounded-[1.25rem] border-slate-200 bg-slate-50/60 py-3.5 pl-14 shadow-sm transition duration-150 focus:border-blue-500 focus:bg-white focus:ring-blue-500">
                            </div>
                        </div>

                        <div>
                            <label for="email" class="text-sm font-bold text-slate-800">Email user</label>
                            <div class="relative mt-2">
                                <span class="material-symbols-outlined pointer-events-none absolute left-5 top-1/2 -translate-y-1/2 text-[20px] text-slate-400">mail</span>
                                <input id="email" name="email" type="email" value="{{ old('email', $managedUser->email) }}" class="block w-full rounded-[1.25rem] border-slate-200 bg-slate-50/60 py-3.5 pl-14 shadow-sm transition duration-150 focus:border-blue-500 focus:bg-white focus:ring-blue-500">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="email_verification_status" class="text-sm font-bold text-slate-800">Status verifikasi email</label>
                        <div class="relative mt-2">
                            <span class="material-symbols-outlined pointer-events-none absolute left-5 top-1/2 -translate-y-1/2 text-[20px] text-slate-400">mark_email_read</span>
                            <select id="email_verification_status" name="email_verification_status" class="block w-full rounded-[1.25rem] border-slate-200 bg-slate-50/60 py-3.5 pl-14 shadow-sm transition duration-150 focus:border-blue-500 focus:bg-white focus:ring-blue-500">
                                <option value="verified" @selected(old('email_verification_status', $managedUser->email_verified_at ? 'verified' : 'unverified') === 'verified')>Terverifikasi</option>
                                <option value="unverified" @selected(old('email_verification_status', $managedUser->email_verified_at ? 'verified' : 'unverified') === 'unverified')>Belum terverifikasi</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex gap-3 rounded-[1.5rem] border border-blue-100 bg-blue-50/70 px-4 py-3 text-sm leading-relaxed text-blue-900">
                        <span class="material-symbols-outlined text-[20px] text-blue-700">info</span>
                        <p>
                        Admin hanya dapat mengelola akun dengan role <span class="font-black">user</span> di halaman ini. Tidak ada fitur untuk membuat atau mengubah akun menjadi admin.
                        </p>
                    </div>

                    <div class="flex flex-wrap gap-3 border-t border-slate-100 pt-5">
                        <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-blue-600 px-6 py-3.5 text-sm font-black text-white shadow-lg shadow-blue-100 transition hover:bg-blue-700 sm:w-auto">
                            <span class="material-symbols-outlined text-[18px]">save</span>
                            Simpan perubahan
                        </button>
                    </div>
                </form>
            </section>

            <section class="space-y-6">
                <div class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-[0px_20px_40px_rgba(25,28,30,0.06)]">
                    <p class="text-sm font-bold text-slate-600">Info Akun</p>
                    <div class="mt-5 space-y-3 text-sm">
                        <div class="flex items-start gap-3 rounded-[1.25rem] bg-slate-50/70 p-4">
                            <span class="material-symbols-outlined text-[20px] text-blue-700">event</span>
                            <div>
                                <div class="font-bold text-slate-900">Bergabung sejak</div>
                                <div class="mt-1 text-slate-600">{{ $managedUser->created_at?->format('d M Y, H:i') ?: '-' }}</div>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 rounded-[1.25rem] bg-slate-50/70 p-4">
                            <span class="material-symbols-outlined text-[20px] text-blue-700">update</span>
                            <div>
                                <div class="font-bold text-slate-900">Update terakhir</div>
                                <div class="mt-1 text-slate-600">{{ $managedUser->updated_at?->format('d M Y, H:i') ?: '-' }}</div>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 rounded-[1.25rem] bg-slate-50/70 p-4">
                            <span class="material-symbols-outlined text-[20px] text-blue-700">badge</span>
                            <div>
                                <div class="font-bold text-slate-900">Role akun</div>
                                <div class="mt-1 text-slate-600">user</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-[2rem] border border-rose-200 bg-white p-6 shadow-[0px_20px_40px_rgba(25,28,30,0.06)]">
                    <p class="text-sm font-bold text-rose-500">Zona Hati-Hati</p>
                    <h3 class="mt-2 text-xl font-black tracking-tight text-slate-950">Hapus akun user</h3>
                    <p class="mt-3 text-sm leading-relaxed text-slate-600">
                        Penghapusan akun akan melepaskan relasi submission milik user ini dari akunnya, menghapus notifikasi user, dan menutup sesi login yang masih aktif.
                    </p>

                    <form method="POST" action="{{ route('admin.users.destroy', $managedUser) }}" class="mt-5" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun user ini? Aksi ini tidak dapat dibatalkan.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-rose-600 px-5 py-3 text-sm font-black text-white shadow-lg shadow-rose-950/20 transition hover:bg-rose-700 sm:w-auto">
                            <span class="material-symbols-outlined text-[18px]">delete</span>
                            Hapus akun user
                        </button>
                    </form>
                </div>
            </section>
        </div>

        <section class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-[0px_20px_40px_rgba(25,28,30,0.06)]">
            <div class="flex items-center justify-between gap-4 border-b border-slate-100 px-6 py-4">
                <div>
                    <h2 class="mt-1 font-black tracking-tight text-slate-950">Aktivitas terkini</h2>
                </div>
            </div>

            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-xl shadow-slate-100/70 transition-all duration-300 hover:shadow-indigo-50">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100">
                    <thead class="bg-white">
                        <tr>
                            <th class="px-6 py-4 text-center text-xs font-bold tracking-widest text-slate-500">Gambar</th>
                            <th class="px-6 py-4 text-center text-xs font-bold tracking-widest text-slate-500">Similarity</th>
                            <th class="px-6 py-4 text-center text-xs font-bold tracking-widest text-slate-500">Status Final</th>
                            <th class="px-6 py-4 text-center text-xs font-bold tracking-widest text-slate-500">Tanggal</th>
                            <th class="px-6 py-4 text-center text-xs font-bold tracking-widest text-slate-500">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white text-center">
                        @forelse ($recentSubmissions as $submission)
                            <tr class="transition duration-150 hover:bg-slate-50">
                                <td class="px-6 py-4">
                                    @if ($submission->image_url)
                                        <img src="{{ $submission->image_url }}" alt="Submission" class="mx-auto h-16 w-24 rounded-xl border border-slate-200 object-cover shadow-sm">
                                    @else
                                        <div class="mx-auto flex h-16 w-24 items-center justify-center rounded-xl border border-slate-200 bg-slate-50 text-slate-400 shadow-sm">
                                            <span class="material-symbols-outlined text-[22px]">image_not_supported</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm font-semibold text-slate-800">
                                    {{ $submission->similarity_score !== null ? $submission->similarity_score.'%' : '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-700">
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-3 py-1.5 text-xs font-bold text-blue-700 shadow-sm shadow-blue-100/50">
                                        <span class="material-symbols-outlined text-[15px]">schedule</span>
                                        {{ $submission->finalStatusLabel() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600">
                                    {{ $submission->created_at?->format('d M Y, H:i') ?: '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <a href="{{ route('admin.submissions.show', $submission) }}" class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-indigo-600 text-white shadow-lg shadow-indigo-100 transition hover:bg-indigo-700 active:scale-[0.98]" aria-label="Buka submission" title="Buka submission">
                                        <span class="material-symbols-outlined text-[18px]">open_in_new</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <p class="text-lg font-bold tracking-tight text-slate-900">Aktivitas masih kosong</p>
                                    <p class="mx-auto mt-1.5 max-w-sm text-sm text-slate-500">User ini belum memiliki submission.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                </div>
            </div>
        </section>
    </div>
</x-admin-shell>
