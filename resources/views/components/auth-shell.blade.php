@props([
    'title' => 'Autentikasi',
    'heading' => 'Autentikasi',
    'description' => null,
    'icon' => 'shield_lock',
    'containerWidth' => 'max-w-6xl',
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Sulteng Hoax Analyzer - {{ $title }}</title>

        <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
        @vite(['resources/js/app.js'])

        <script>
            tailwind.config = {
                darkMode: "class",
                theme: {
                    extend: {
                        colors: {
                            "error-container": "#ffdad6",
                            "surface": "#f7f9fb",
                            "surface-container-lowest": "#ffffff",
                            "primary-container": "#00174b",
                            "primary": "#000000",
                            "error": "#ba1a1a",
                            "on-primary": "#ffffff",
                            "surface-container-high": "#e6e8ea",
                            "surface-container-highest": "#e0e3e5",
                            "on-primary-fixed": "#00174b",
                            "on-error": "#ffffff",
                            "on-secondary-fixed-variant": "#38485d",
                            "surface-bright": "#f7f9fb",
                            "on-primary-fixed-variant": "#003ea8",
                            "outline-variant": "#c6c6cd",
                            "tertiary-fixed": "#6ffbbe",
                            "on-background": "#191c1e",
                            "on-surface": "#191c1e",
                            "on-primary-container": "#497cff",
                            "on-surface-variant": "#45464d",
                            "on-secondary-fixed": "#0b1c30",
                            "surface-variant": "#e0e3e5",
                            "surface-dim": "#d8dadc",
                            "secondary": "#505f76",
                            "on-tertiary-fixed": "#002113",
                            "inverse-surface": "#2d3133",
                            "on-tertiary-container": "#009668",
                            "surface-tint": "#0053db",
                            "outline": "#76777d",
                            "on-secondary-container": "#54647a",
                            "tertiary": "#000000",
                            "on-error-container": "#93000a",
                            "background": "#f7f9fb",
                            "secondary-fixed": "#d3e4fe",
                            "secondary-fixed-dim": "#b7c8e1",
                            "tertiary-container": "#002113",
                            "secondary-container": "#d0e1fb",
                            "primary-fixed-dim": "#b4c5ff",
                            "inverse-on-surface": "#eff1f3",
                            "on-secondary": "#ffffff",
                            "surface-container": "#eceef0",
                            "on-tertiary-fixed-variant": "#005236",
                            "tertiary-fixed-dim": "#4edea3",
                            "surface-container-low": "#f2f4f6",
                            "on-tertiary": "#ffffff",
                            "inverse-primary": "#b4c5ff",
                            "primary-fixed": "#dbe1ff"
                        },
                        borderRadius: {
                            DEFAULT: "1rem",
                            lg: "2rem",
                            xl: "3rem",
                            full: "9999px"
                        },
                        fontFamily: {
                            headline: ["Inter"],
                            body: ["Inter"],
                            label: ["Inter"]
                        }
                    },
                },
            }
        </script>

        <style>
            body {
                font-family: 'Inter', sans-serif;
                background: linear-gradient(180deg, #f8fbff 0%, #eef5ff 100%);
            }

            .material-symbols-outlined {
                font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            }

            .glass-panel {
                background: rgba(255, 255, 255, 0.8);
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
            }
        </style>
    </head>
    <body class="min-h-screen overflow-x-hidden bg-surface font-body text-on-surface antialiased selection:bg-primary-fixed-dim">
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute left-[-10rem] top-[-6rem] h-80 w-80 rounded-full bg-blue-200/30 blur-3xl"></div>
            <div class="absolute bottom-[-8rem] right-[-6rem] h-96 w-96 rounded-full bg-cyan-200/30 blur-3xl"></div>
            <div class="absolute left-1/3 top-1/3 h-72 w-72 rounded-full bg-blue-100/40 blur-3xl"></div>
        </div>

        <header class="sticky top-0 z-40 border-b border-white/40 bg-slate-50/60 backdrop-blur-md">
            <div class="mx-auto flex w-full {{ $containerWidth }} items-center justify-between px-6 py-4 lg:px-8">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-primary-container text-on-primary shadow-lg shadow-blue-200/60">
                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">shield</span>
                    </div>
                    <div>
                        <p class="text-sm font-black tracking-tight text-slate-900">Sulteng Hoax Analyzer</p>
                        <p class="text-xs text-slate-500">Portal verifikasi informasi publik</p>
                    </div>
                </a>

                <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 rounded-full border border-blue-200 bg-white px-4 py-2 text-sm font-bold text-blue-700 shadow-sm transition hover:bg-blue-50">
                    <span class="material-symbols-outlined text-[18px]">dashboard</span>
                    Dashboard
                </a>
            </div>
        </header>

        <main class="relative z-10 flex min-h-[calc(100vh-76px)] items-center px-6 py-10 lg:px-8">
            <div class="mx-auto grid w-full {{ $containerWidth }} gap-8 lg:grid-cols-6">
                <section class="lg:col-span-7">
                    <div class="mx-auto w-full max-w-2xl rounded-[2.5rem] border border-white/70 bg-white/90 p-8 shadow-[0px_20px_40px_rgba(25,28,30,0.06)] backdrop-blur-xl sm:p-10 lg:p-12">
                        <div class="mb-8 space-y-4">

                            <div class="space-y-2">
                                <h1 class="text-3xl font-black tracking-tight text-slate-900 sm:text-4xl text-center">{{ $heading }}</h1>
                                @if ($description)
                                    <p class="text-base leading-relaxed text-slate-600 text-center">{{ $description }}</p>
                                @endif
                            </div>
                        </div>

                        {{ $slot }}
                    </div>
                </section>
            </div>
        </main>

        <x-logout-confirm-modal />
    </body>
</html>
