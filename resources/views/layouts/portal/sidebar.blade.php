@php
    $currentUser = auth()->user();
    $resolvedMode = $mode ?? ($currentUser?->role === 'admin' ? 'admin' : 'user');
    $displayName = match ($resolvedMode) {
        'admin' => $currentUser?->name ?: 'Admin',
        'guest' => 'Tamu',
        default => $currentUser?->name ?: 'Pengguna',
    };
    $displayRole = match ($resolvedMode) {
        'admin' => 'Administrator',
        'guest' => 'Pengunjung',
        default => 'Profil Pengguna',
    };
    $nameParts = preg_split('/\s+/', trim($displayName)) ?: [];
    $profileInitials = collect($nameParts)
        ->filter()
        ->take(2)
        ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
        ->implode('');
    $profileInitials = $profileInitials !== '' ? $profileInitials : match ($resolvedMode) {
        'admin' => 'AD',
        'guest' => 'TM',
        default => 'PG',
    };

    $items = match ($resolvedMode) {
        'admin' => [
            ['label' => 'Dashboard', 'icon' => 'dashboard', 'route' => route('admin.dashboard'), 'active' => request()->routeIs('admin.dashboard')],
            ['label' => 'Konten Resmi', 'icon' => 'verified', 'route' => route('official.index'), 'active' => request()->routeIs('official.*')],
            ['label' => 'Submission', 'icon' => 'inbox', 'route' => route('admin.submissions.index'), 'active' => request()->routeIs('admin.submissions.*')],
            ['label' => 'Manajemen User', 'icon' => 'group', 'route' => route('admin.users.index'), 'active' => request()->routeIs('admin.users.*')],
        ],
        'guest' => [
            ['label' => 'Dashboard', 'icon' => 'dashboard', 'route' => route('dashboard'), 'active' => request()->routeIs('dashboard')],
        ],
        default => [
            ['label' => 'Dashboard', 'icon' => 'dashboard', 'route' => route('user.dashboard'), 'active' => request()->routeIs('user.dashboard')],
            ['label' => 'Konten Resmi', 'icon' => 'verified', 'route' => route('user.official.index'), 'active' => request()->routeIs('user.official.*')],
            ['label' => 'Hasil Validasi', 'icon' => 'fact_check', 'route' => route('user.validation-results'), 'active' => request()->routeIs('user.validation-results')],
        ],
    };
@endphp

<div class="flex h-full flex-col gap-4 py-8">
    <div class="mb-4">
        <a
            class="{{ $resolvedMode === 'guest' ? 'bg-blue-50 text-blue-700 ring-1 ring-blue-100' : (request()->routeIs('profile.edit') ? 'bg-blue-50 text-blue-700 ring-1 ring-blue-100' : 'text-slate-500 hover:bg-slate-100 hover:text-slate-900') }} mx-2 flex items-center gap-4 rounded-full px-6 py-3 transition-all hover:translate-x-1"
            href="{{ $resolvedMode === 'guest' ? route('login') : route('profile.edit') }}"
        >
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-slate-800 to-slate-600 text-sm font-black tracking-tight text-white shadow-md shadow-slate-300/50">
                {{ $profileInitials }}
            </div>
            <div class="min-w-0 flex-1">
                <p class="truncate text-sm font-bold {{ $resolvedMode === 'guest' ? 'text-blue-700' : (request()->routeIs('profile.edit') ? 'text-blue-700' : 'text-slate-900') }}">{{ $displayName }}</p>
                <p class="text-xs {{ $resolvedMode === 'guest' ? 'text-blue-600' : (request()->routeIs('profile.edit') ? 'text-blue-600' : 'text-slate-500') }}">{{ $displayRole }}</p>
            </div>
            <span class="material-symbols-outlined text-[18px] {{ $resolvedMode === 'guest' ? 'text-blue-500' : (request()->routeIs('profile.edit') ? 'text-blue-500' : 'text-slate-400') }}">expand_more</span>
        </a>
    </div>

    <nav class="flex-1 space-y-2 pr-2">
        @foreach ($items as $item)
            <a
                class="{{ $item['active'] ? 'rounded-full bg-blue-50 font-bold text-blue-700' : 'text-slate-500 hover:text-slate-900' }} ml-2 flex items-center gap-4 px-6 py-3 transition-all duration-200 hover:translate-x-1"
                href="{{ $item['route'] }}"
            >
                <span class="material-symbols-outlined">{{ $item['icon'] }}</span>
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach
    </nav>

    <div class="px-8 pt-2">
        @if ($resolvedMode !== 'guest')
            <form method="POST" action="{{ route('logout') }}" data-logout-confirm data-confirm-title="Keluar dari akun {{ $displayName }}?" data-confirm-message="Anda akan mengakhiri sesi aktif untuk {{ $displayName }}. Apakah Anda yakin ingin keluar?">
                @csrf
                <button class="flex w-full items-center justify-center gap-3 rounded-full bg-rose-600 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-rose-200 transition hover:bg-rose-700" type="submit">
                    <span class="material-symbols-outlined text-[20px]">logout</span>
                    <span>Logout</span>
                </button>
            </form>
        @endif
    </div>
</div>
