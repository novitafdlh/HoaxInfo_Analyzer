@php
    $resolvedMode = $mode ?? (auth()->user()?->role === 'admin' ? 'admin' : 'user');
@endphp

<header class="sticky top-0 z-50 flex w-full max-w-full items-center justify-between gap-4 bg-slate-50/50 px-8 py-4 backdrop-blur-md">
    <div class="flex items-center gap-4">
        <span class="bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-xl font-bold text-transparent">
            Sulteng Hoax Analyzer
        </span>
    </div>

    @if ($resolvedMode === 'guest')
        <div class="flex items-center gap-3">
            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-bold text-slate-900 shadow-sm transition hover:bg-slate-50">
                <span class="material-symbols-outlined text-[18px]">login</span>
                Masuk
            </a>

            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="inline-flex items-center gap-2 rounded-full bg-blue-600 px-4 py-2 text-sm font-bold text-white shadow-lg transition hover:bg-blue-700">
                    <span class="material-symbols-outlined text-[18px]">person_add</span>
                    Daftar
                </a>
            @endif
        </div>
    @endif
</header>
