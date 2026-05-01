@php
    $user = auth()->user();
@endphp

<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            {{-- LEFT --}}
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                        <img src="{{ asset('images/sulteng.png') }}" alt="Logo Sulawesi Tengah" class="h-10 w-10 shrink-0 object-contain">
                        <span class="bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-xl font-bold tracking-tight text-transparent">
                            Sulteng Hoax Analyzer
                        </span>
                    </a>
                </div>

                <div class="hidden space-x-8 sm:ms-10 sm:flex">
                    @if($user && $user->role === 'admin')
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                            Dashboard Admin
                        </x-nav-link>
                    @endif
                </div>
            </div>

            {{-- RIGHT --}}
            <div class="hidden sm:flex sm:items-center sm:ms-6">

                {{-- GUEST --}}
                @guest
                    <x-nav-link :href="route('login')">Login</x-nav-link>
                    <x-nav-link :href="route('register')">Register</x-nav-link>
                @endguest

                {{-- AUTH USER --}}
                @auth
                    <div class="me-4 flex items-center gap-2">
                        @if($user->role === 'user')
                            <x-nav-link :href="route('user.dashboard')">Dashboard</x-nav-link>
                            <x-nav-link :href="route('user.official.index')">Konten Resmi</x-nav-link>
                            <x-nav-link :href="route('user.validation-results')">Hasil Analisis</x-nav-link>
                        @endif
                    </div>

                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 text-sm text-gray-500 bg-white hover:text-gray-700">
                                <div>{{ $user->name }}</div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                Profile
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}" data-logout-confirm data-confirm-title="Keluar dari akun {{ $user->name }}?" data-confirm-message="Anda akan mengakhiri sesi aktif untuk {{ $user->name }}. Apakah Anda yakin ingin keluar?">
                                @csrf
                                <button type="submit" class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-gray-100 focus:bg-gray-100 focus:outline-none">
                                    Logout
                                </button>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @endauth

            </div>
        </div>
    </div>
</nav>
