@php
    $statusCards = [
        ['label' => 'Total User', 'value' => number_format($userSummary['total']), 'icon' => 'groups'],
        ['label' => 'Email Terverifikasi', 'value' => number_format($userSummary['verified']), 'icon' => 'verified'],
        ['label' => 'Belum Verifikasi', 'value' => number_format($userSummary['unverified']), 'icon' => 'mark_email_unread'],
        ['label' => 'Pernah Submit', 'value' => number_format($userSummary['with_submissions']), 'icon' => 'fact_check'],
    ];
@endphp

<x-admin-shell title="Manajemen User Admin">
    <x-slot name="pageHeader">
        <section class="space-y-4">
            <div class="flex flex-col gap-3 xl:flex-row xl:items-end xl:justify-between">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight text-on-surface">Manajemen User</h1>
                    <p class="mt-2 text-base leading-relaxed text-on-surface-variant">Pantau akun pengguna, verifikasi email, dan aktivitas submission tanpa membuka jalur pembuatan admin baru.</p>
                </div>
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

        <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            @foreach ($statusCards as $card)
                <div class="rounded-[2rem] border border-slate-200 bg-white p-5 shadow-[0px_20px_40px_rgba(25,28,30,0.06)] transition hover:-translate-y-1 hover:shadow-[0px_24px_44px_rgba(37,99,235,0.10)]">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-black uppercase tracking-[0.18em] text-slate-500">{{ $card['label'] }}</p>
                        <span class="material-symbols-outlined rounded-full bg-blue-50 p-2 text-blue-700">{{ $card['icon'] }}</span>
                    </div>
                    <p class="mt-4 text-2xl font-black tracking-tight text-slate-950">{{ $card['value'] }}</p>
                </div>
            @endforeach
        </section>

        <section class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-[0px_20px_40px_rgba(25,28,30,0.06)]">
            <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col gap-3 xl:flex-row xl:items-center">
                <div class="relative flex-1">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                        <span class="material-symbols-outlined text-[20px]">search</span>
                    </span>
                    <input
                        type="search"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Cari nama atau email user"
                        class="w-full rounded-[1.25rem] border border-slate-200 bg-slate-50/60 py-3 pl-12 pr-4 text-sm text-slate-700 shadow-sm transition placeholder:text-slate-400 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100"
                    >
                </div>

                <select name="verification" class="rounded-[1.25rem] border border-slate-200 bg-slate-50/60 px-4 py-3 text-sm font-semibold text-slate-700 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                    <option value="">Status</option>
                    <option value="verified" @selected($verification === 'verified')>Sudah verifikasi</option>
                    <option value="unverified" @selected($verification === 'unverified')>Belum verifikasi</option>
                </select>

                <div class="flex gap-3">
                    <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-full bg-blue-600 px-5 py-3 text-sm font-black text-white shadow-lg shadow-blue-100 transition hover:bg-blue-700">
                        <span class="material-symbols-outlined text-[18px]">search</span>
                        Cari
                    </button>
                    @if ($search !== '' || $verification !== '')
                        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center justify-center rounded-full border border-slate-200 px-5 py-3 text-sm font-bold text-slate-700 transition hover:bg-slate-50">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </section>

        <section class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-[0px_20px_40px_rgba(25,28,30,0.06)]">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100">
                    <thead class="bg-slate-50/80">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-[0.18em] text-slate-500">User</th>
                            <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-[0.18em] text-slate-500">Verifikasi</th>
                            <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-[0.18em] text-slate-500">Submission</th>
                            <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-[0.18em] text-slate-500">Bergabung</th>
                            <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-[0.18em] text-slate-500">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($users as $user)
                            <tr class="hover:bg-slate-50/80">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-50 text-sm font-black text-blue-700">
                                            {{ strtoupper(substr($user->name ?: $user->email, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-950">{{ $user->name }}</div>
                                            <div class="mt-1 text-sm text-slate-500">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full {{ $user->email_verified_at ? 'bg-blue-50 text-blue-700' : 'bg-amber-50 text-amber-700' }}">
                                            <span class="material-symbols-outlined text-[19px]">
                                                {{ $user->email_verified_at ? 'verified' : 'mark_email_unread' }}
                                            </span>
                                        </div>
                                        <div>
                                            <span class="inline-flex rounded-full border px-3 py-1 text-xs font-black {{ $user->email_verified_at ? 'border-blue-100 bg-blue-50 text-blue-700' : 'border-amber-100 bg-amber-50 text-amber-700' }}">
                                                {{ $user->email_verified_at ? 'Terverifikasi' : 'Belum verifikasi' }}
                                            </span>
                                            @if ($user->email_verified_at)
                                                <p class="mt-2 text-xs text-slate-500">{{ $user->email_verified_at->format('d M Y, H:i') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-700">
                                    <div class="font-bold text-slate-950">{{ number_format($user->submissions_count) }} total</div>
                                    <div class="mt-1 text-xs text-slate-500">
                                        {{ number_format($user->verified_submissions_count) }} terverifikasi,
                                        {{ number_format($user->pending_submissions_count) }} menunggu
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm font-semibold text-slate-700">
                                    {{ $user->created_at?->format('d M Y, H:i') ?: '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <a href="{{ route('admin.users.show', $user) }}" class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-blue-600 text-white shadow-lg shadow-blue-100 transition hover:bg-blue-700" aria-label="Kelola user {{ $user->name }}" title="Kelola user">
                                        <span class="material-symbols-outlined text-[18px]">manage_accounts</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-14 text-center">
                                    <p class="text-lg font-black text-slate-950">Belum ada user untuk filter ini</p>
                                    <p class="mt-2 text-sm text-slate-500">Coba ubah kata kunci pencarian atau status verifikasi.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($users->hasPages())
                <div class="border-t border-slate-100 bg-slate-50/70 px-6 py-4">
                    {{ $users->links() }}
                </div>
            @endif
        </section>
    </div>
</x-admin-shell>
