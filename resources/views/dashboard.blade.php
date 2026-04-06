<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Dashboard</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gradient-to-b from-slate-50 via-white to-cyan-50 text-slate-900 min-h-screen">
        @php
            $popupMessage = session('status') ?: ($errors->any() ? $errors->first() : null);
            $popupType = session('notification_type', $errors->any() ? 'error' : 'info');
            $showLoginLink = (bool) session('show_login_link', false);
            $popupConfig = [
                'success' => [
                    'title' => 'Konten Tervalidasi',
                    'badge' => 'VALID',
                    'style' => 'border-emerald-200 bg-emerald-50 text-emerald-900',
                    'badgeStyle' => 'bg-emerald-600 text-white',
                ],
                'warning' => [
                    'title' => 'Menunggu Validasi Manual',
                    'badge' => 'REVIEW',
                    'style' => 'border-amber-200 bg-amber-50 text-amber-900',
                    'badgeStyle' => 'bg-amber-600 text-white',
                ],
                'error' => [
                    'title' => 'Konten Tidak Tervalidasi',
                    'badge' => 'TIDAK VALID',
                    'style' => 'border-rose-200 bg-rose-50 text-rose-900',
                    'badgeStyle' => 'bg-rose-600 text-white',
                ],
                'info' => [
                    'title' => 'Informasi',
                    'badge' => 'INFO',
                    'style' => 'border-slate-200 bg-white text-slate-900',
                    'badgeStyle' => 'bg-slate-700 text-white',
                ],
            ];
            $activePopup = $popupConfig[$popupType] ?? $popupConfig['info'];
        @endphp

        @if ($popupMessage)
            <div x-data="{ open: true }" x-show="open" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 px-4" @click.self="open = false" @keydown.escape.window="open = false">
                <div class="w-full max-w-lg rounded-2xl border shadow-2xl {{ $activePopup['style'] }}">
                    <div class="flex items-center justify-between border-b border-current/20 px-5 py-4">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-wide opacity-80">{{ $activePopup['title'] }}</p>
                        </div>
                        <span class="rounded-full px-2.5 py-1 text-[10px] font-semibold tracking-wide {{ $activePopup['badgeStyle'] }}">
                            {{ $activePopup['badge'] }}
                        </span>
                    </div>
                    <div class="space-y-4 px-5 py-4">
                        <p class="text-sm font-medium leading-relaxed">{{ $popupMessage }}</p>

                        @if ($showLoginLink && Route::has('login'))
                            <a href="{{ route('login') }}" class="inline-flex items-center rounded-md bg-slate-900 px-4 py-2 text-xs font-semibold text-white hover:bg-slate-700">
                                Login untuk menerima hasil validasi
                            </a>
                        @endif

                        <div class="flex justify-end">
                            <button type="button" @click="open = false" class="rounded-md border border-current/30 px-3 py-1.5 text-xs font-semibold hover:bg-white/40">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <nav class="sticky top-0 z-30 border-b border-slate-200/80 bg-white/80 backdrop-blur-md">
            <div class="mx-auto flex h-16 w-full max-w-6xl items-center justify-between px-4 sm:px-6 lg:px-8">
                <a href="{{ route('dashboard') }}" class="text-sm font-semibold tracking-wide text-slate-900">
                    Public Info Verification
                </a>

                <div class="flex items-center gap-2">
                    @auth
                        @if (Auth::user()->role === 'user')
                            <a href="{{ route('user.validation-results') }}" class="rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-100">
                                Hasil Validasi
                            </a>
                        @endif
                        <span class="hidden text-sm text-slate-600 sm:inline">{{ Auth::user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-100">
                                Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-100">
                            Login
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="rounded-lg bg-slate-900 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-slate-700">
                                Register
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </nav>

        <main class="mx-auto w-full max-w-6xl px-4 pb-12 pt-10 sm:px-6 lg:px-8">
            <div class="grid gap-6 lg:grid-cols-3">
                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm lg:col-span-1">
                    <h1 class="text-xl font-semibold">Dashboard Validasi Gambar</h1>
                    <p class="mt-2 text-sm text-slate-600">
                        Unggah file gambar atau URL gambar untuk pengecekan kebenaran informasi.
                    </p>
                    <ul class="mt-4 space-y-1 text-xs text-slate-500">
                        <li>100% cocok dengan konten resmi: tervalidasi otomatis.</li>
                        <li>50% - 99% mirip: wajib login untuk proses validasi manual admin.</li>
                        <li>Di bawah 50%: tidak tervalidasi dari Kominfo.</li>
                    </ul>

                    <div class="mt-6">
                        @if ($isAuthenticated)
                            <p class="rounded-xl border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm font-medium text-emerald-700">
                                Akun login aktif: upload tanpa batas.
                            </p>
                        @else
                            <p class="rounded-xl border border-amber-200 bg-amber-50 px-3 py-2 text-sm font-medium text-amber-700">
                                Sisa upload guest: {{ $guestUploadsRemaining }} / 3.
                            </p>
                        @endif
                    </div>
                </section>

                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-lg shadow-cyan-100/60 lg:col-span-2">
                    <h2 class="text-lg font-semibold">Upload Gambar</h2>
                    <p class="mt-1 text-sm text-slate-500">Maksimum ukuran file 100MB.</p>

                    @if ($errors->any())
                        <div class="mt-4 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('dashboard.upload') }}" enctype="multipart/form-data" class="mt-6 space-y-5">
                        @csrf

                        <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 p-4">
                            <label for="image_file" class="block text-sm font-medium text-slate-700">File Gambar</label>
                            <input id="image_file" name="image_file" type="file" accept="image/*" class="mt-2 block w-full rounded-lg border-slate-300 bg-white text-sm focus:border-cyan-500 focus:ring-cyan-500">
                        </div>

                        <div>
                            <label for="image_url" class="block text-sm font-medium text-slate-700">Atau URL Gambar</label>
                            <input id="image_url" name="image_url" type="url" value="{{ old('image_url') }}" placeholder="https://contoh.com/gambar.jpg" class="mt-2 block w-full rounded-lg border-slate-300 text-sm focus:border-cyan-500 focus:ring-cyan-500">
                        </div>

                        <button type="submit" class="inline-flex items-center rounded-lg bg-cyan-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-cyan-500">
                            Upload untuk Validasi
                        </button>
                    </form>
                </section>
            </div>
        </main>
    </body>
</html>
