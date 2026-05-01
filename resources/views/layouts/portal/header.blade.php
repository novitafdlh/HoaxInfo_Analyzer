@php
    $resolvedMode = $mode ?? (auth()->user()?->role === 'admin' ? 'admin' : 'user');
@endphp

<header class="sticky top-0 z-50 flex w-full max-w-full items-center justify-between gap-4 bg-slate-50/50 px-8 py-4 backdrop-blur-md">
    <div class="flex items-center gap-4">
        <img src="{{ asset('images/sulteng.png') }}" alt="Logo Sulawesi Tengah" class="h-10 w-10 shrink-0 object-contain">
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
    @else
        <div class="flex items-center gap-3">
            <div class="relative">
                <button
                    type="button"
                    id="notification-toggle"
                    class="relative inline-flex h-11 w-11 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-700 shadow-sm transition hover:bg-slate-50"
                    aria-label="Buka notifikasi"
                    title="Notifikasi"
                >
                    <span class="material-symbols-outlined text-[20px]">notifications</span>
                    @if (($headerNotificationCount ?? 0) > 0)
                        <span class="absolute right-0 top-0 inline-flex min-h-[1.25rem] min-w-[1.25rem] items-center justify-center rounded-full bg-rose-500 px-1 text-[10px] font-bold text-white shadow-sm">
                            {{ $headerNotificationCount > 9 ? '9+' : $headerNotificationCount }}
                        </span>
                    @endif
                </button>

                <div
                    id="notification-panel"
                    class="absolute right-0 top-14 hidden w-[22rem] overflow-hidden rounded-[1.5rem] border border-slate-200 bg-white shadow-[0px_24px_48px_rgba(15,23,42,0.14)]"
                >
                    <div class="border-b border-slate-100 px-5 py-4">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <p class="text-sm font-black text-slate-900">Notifikasi</p>
                                <p class="mt-1 text-xs text-slate-500">
                                    {{ ($headerNotificationCount ?? 0) > 0 ? 'Ada pembaruan yang perlu Anda lihat.' : 'Belum ada pembaruan baru saat ini.' }}
                                </p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="inline-flex rounded-full bg-blue-50 px-2.5 py-1 text-[11px] font-bold text-blue-700">
                                    {{ count($headerNotifications ?? []) }}
                                </span>
                                @if (($headerNotificationCount ?? 0) > 0)
                                    <form method="POST" action="{{ route('notifications.mark-all-read') }}">
                                        @csrf
                                        <button type="submit" class="text-[11px] font-bold text-blue-700 transition hover:text-blue-800">
                                            Tandai semua
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="max-h-[24rem] overflow-y-auto p-2">
                        @forelse (($headerNotifications ?? []) as $notification)
                            @php
                                $toneClasses = match ($notification['tone']) {
                                    'amber' => 'bg-amber-50 text-amber-700',
                                    'emerald' => 'bg-emerald-50 text-emerald-700',
                                    default => 'bg-blue-50 text-blue-700',
                                };
                            @endphp
                            <a
                                href="{{ $notification['url'] }}"
                                class="flex items-start gap-3 rounded-2xl px-3 py-3 transition hover:bg-slate-50 {{ !$notification['read'] ? 'bg-blue-50/40' : '' }}"
                            >
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full {{ $toneClasses }}">
                                    <span class="material-symbols-outlined text-[18px]">{{ $notification['icon'] }}</span>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-start justify-between gap-3">
                                        <p class="text-sm font-bold text-slate-900">{{ $notification['title'] }}</p>
                                        <div class="flex shrink-0 items-center gap-2">
                                            @if (!$notification['read'])
                                                <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                                            @endif
                                            <span class="text-[11px] text-slate-400">{{ $notification['time'] }}</span>
                                        </div>
                                    </div>
                                    <p class="mt-1 text-xs leading-relaxed text-slate-600">{{ $notification['message'] }}</p>
                                    @if ($notification['meta'] !== '')
                                        <p class="mt-2 text-[11px] font-semibold text-slate-400">{{ $notification['meta'] }}</p>
                                    @endif
                                </div>
                            </a>
                        @empty
                            <div class="px-4 py-8 text-center">
                                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                                    <span class="material-symbols-outlined text-[22px]">notifications_off</span>
                                </div>
                                <p class="mt-4 text-sm font-bold text-slate-900">Belum ada notifikasi</p>
                                <p class="mt-1 text-xs text-slate-500">Pembaruan terbaru akan muncul di sini.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    @endif
</header>
