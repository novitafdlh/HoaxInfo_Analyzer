@php
    $currentUser = auth()->user();
    $displayName = $currentUser?->name ?: 'Admin';
    $nameParts = preg_split('/\s+/', trim($displayName)) ?: [];
    $profileInitials = collect($nameParts)
        ->filter()
        ->take(2)
        ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
        ->implode('');
    $profileInitials = $profileInitials !== '' ? $profileInitials : 'AD';
@endphp

<div class="flex h-full flex-col gap-4 py-8">
    <div class="mb-4">
        <a class="{{ request()->routeIs('profile.edit') ? 'bg-blue-50 text-blue-700 ring-1 ring-blue-100' : 'text-slate-500 hover:bg-slate-100 hover:text-slate-900' }} mx-2 flex items-center gap-4 rounded-full px-6 py-3 transition-all hover:translate-x-1" href="{{ route('profile.edit') }}">
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-slate-800 to-slate-600 text-sm font-black tracking-tight text-white shadow-md shadow-slate-300/50">
                {{ $profileInitials }}
            </div>
            <div class="min-w-0 flex-1">
                <p class="truncate text-sm font-bold {{ request()->routeIs('profile.edit') ? 'text-blue-700' : 'text-slate-900' }}">{{ $displayName }}</p>
                <p class="text-xs {{ request()->routeIs('profile.edit') ? 'text-blue-600' : 'text-slate-500' }}">Administrator</p>
            </div>
            <span class="material-symbols-outlined text-[18px] {{ request()->routeIs('profile.edit') ? 'text-blue-500' : 'text-slate-400' }}">expand_more</span>
        </a>
    </div>

    <nav class="flex-1 space-y-2 pr-2">
        <a class="{{ request()->routeIs('admin.dashboard') ? 'rounded-full bg-blue-50 text-blue-700 font-bold' : 'text-slate-500 hover:text-slate-900' }} ml-2 flex items-center gap-4 px-6 py-3 transition-all duration-200 hover:translate-x-1" href="{{ route('admin.dashboard') }}">
            <span class="material-symbols-outlined">dashboard</span>
            <span>Dashboard</span>
        </a>

        <a class="{{ request()->routeIs('official.*') ? 'rounded-full bg-blue-50 text-blue-700 font-bold' : 'text-slate-500 hover:text-slate-900' }} ml-2 flex items-center gap-4 px-6 py-3 transition-all duration-200 hover:translate-x-1" href="{{ route('official.index') }}">
            <span class="material-symbols-outlined">verified</span>
            <span>Konten Resmi</span>
        </a>

        <a class="{{ request()->routeIs('admin.submissions.*') ? 'rounded-full bg-blue-50 text-blue-700 font-bold' : 'text-slate-500 hover:text-slate-900' }} ml-2 flex items-center gap-4 px-6 py-3 transition-all duration-200 hover:translate-x-1" href="{{ route('admin.submissions.index') }}">
            <span class="material-symbols-outlined">inbox</span>
            <span>Submission</span>
        </a>

        <a class="{{ request()->routeIs('admin.users.*') ? 'rounded-full bg-blue-50 text-blue-700 font-bold' : 'text-slate-500 hover:text-slate-900' }} ml-2 flex items-center gap-4 px-6 py-3 transition-all duration-200 hover:translate-x-1" href="{{ route('admin.users.index') }}">
            <span class="material-symbols-outlined">group</span>
            <span>Manajemen User</span>
        </a>
    </nav>

    <div class="mx-6 rounded-[2rem] border border-blue-100 bg-gradient-to-br from-white via-blue-50 to-cyan-50 p-5 shadow-[0px_12px_28px_rgba(37,99,235,0.08)]">
        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-900 text-white">
            <span class="material-symbols-outlined text-[20px]">tips_and_updates</span>
        </div>
        <p class="mt-4 text-sm font-bold text-slate-900">Panel Referensi Admin</p>
        <p class="mt-1 text-xs leading-relaxed text-slate-600">
            Gunakan sidebar ini untuk berpindah cepat antara dashboard, basis konten resmi, dan antrean submission.
        </p>
    </div>

    <div class="px-8 pt-2">
        <form method="POST" action="{{ route('logout') }}" data-logout-confirm data-confirm-title="Keluar dari akun {{ $displayName }}?" data-confirm-message="Anda akan mengakhiri sesi admin untuk {{ $displayName }}. Apakah Anda yakin ingin keluar?">
            @csrf
            <button class="flex w-full items-center justify-center gap-3 rounded-full bg-rose-600 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-rose-200 transition hover:bg-rose-700" type="submit">
                <span class="material-symbols-outlined text-[20px]">logout</span>
                <span>Logout</span>
            </button>
        </form>
    </div>
</div>
