@props([
    'title' => 'Portal',
    'mode' => null,
    'containerClass' => 'max-w-7xl',
    'contentClass' => 'space-y-6',
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

        <script id="tailwind-config">
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
            [x-cloak] {
                display: none !important;
            }

            body {
                font-family: 'Inter', sans-serif;
            }

            body.portal-theme {
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

            body.portal-theme [class*="bg-slate-900"] {
                background-color: #2563eb !important;
            }

            body.portal-theme [class*="hover:bg-slate-800"]:hover {
                background-color: #1d4ed8 !important;
            }

            body.portal-theme [class*="bg-slate-50"],
            body.portal-theme [class*="bg-slate-100"],
            body.portal-theme [class*="bg-surface-container-low"],
            body.portal-theme [class*="bg-surface-container-high"],
            body.portal-theme [class*="bg-surface-container"] {
                background-color: #f8fbff !important;
            }

            body.portal-theme [class*="bg-rose-50"],
            body.portal-theme [class*="bg-amber-50"],
            body.portal-theme [class*="bg-emerald-50"],
            body.portal-theme [class*="bg-orange-50"],
            body.portal-theme [class*="bg-cyan-50"] {
                background-color: #eff6ff !important;
            }

            body.portal-theme [class*="border-slate-100"],
            body.portal-theme [class*="border-slate-200"],
            body.portal-theme [class*="border-blue-100"],
            body.portal-theme [class*="border-rose-200"],
            body.portal-theme [class*="border-amber-200"],
            body.portal-theme [class*="border-emerald-200"],
            body.portal-theme [class*="border-cyan-100"],
            body.portal-theme [class*="border-orange-200"] {
                border-color: #dbeafe !important;
            }

            body.portal-theme [class*="text-slate-900"],
            body.portal-theme [class*="text-blue-950"] {
                color: #1e3a8a !important;
            }

            body.portal-theme [class*="text-slate-700"],
            body.portal-theme [class*="text-slate-800"],
            body.portal-theme [class*="text-emerald-800"],
            body.portal-theme [class*="text-amber-800"],
            body.portal-theme [class*="text-rose-800"],
            body.portal-theme [class*="text-cyan-800"] {
                color: #1d4ed8 !important;
            }

            body.portal-theme [class*="text-slate-500"],
            body.portal-theme [class*="text-slate-600"],
            body.portal-theme [class*="text-on-surface-variant"],
            body.portal-theme [class*="text-emerald-700"],
            body.portal-theme [class*="text-amber-700"],
            body.portal-theme [class*="text-rose-700"],
            body.portal-theme [class*="text-cyan-700"] {
                color: #4f7db8 !important;
            }

            body.portal-theme [class*="from-slate-800"][class*="to-slate-600"] {
                background-image: linear-gradient(to bottom right, #3b82f6, #1d4ed8) !important;
            }
        </style>
    </head>
    <body class="portal-theme overflow-x-hidden bg-surface font-body text-on-surface antialiased selection:bg-primary-fixed-dim">
        <div class="flex min-h-screen">
            <aside class="sticky left-0 top-0 z-40 hidden h-screen w-72 shrink-0 rounded-r-[3rem] bg-white/80 shadow-[0px_20px_40px_rgba(25,28,30,0.06)] backdrop-blur-xl md:flex">
                @include('layouts.portal.sidebar', ['mode' => $mode])
            </aside>

            <main class="flex min-w-0 flex-1 flex-col">
                @include('layouts.portal.header')

                <div class="mx-auto w-full {{ $containerClass }} {{ $contentClass }} px-8 py-10 pb-28 md:pb-10">
                    @isset($pageHeader)
                        {{ $pageHeader }}
                    @endisset

                    {{ $slot }}
                </div>
            </main>
        </div>

        <nav class="glass-panel fixed bottom-0 left-0 right-0 z-40 flex items-center justify-between border-t border-outline-variant/10 px-6 py-3 md:hidden">
            @include('layouts.portal.mobile-nav', ['mode' => $mode])
        </nav>

        <x-logout-confirm-modal />
    </body>
</html>
