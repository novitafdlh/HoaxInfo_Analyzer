@php
    $user = auth()->user();
@endphp

<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            {{-- LEFT --}}
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center group">
                        <span class="text-xl font-bold tracking-tight text-slate-700 group-hover:text-indigo-700 transition">
                            Sulteng <span class="text-indigo-500">Hoax Analyzer</span>
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
                            <x-nav-link :href="route('user.validation-results')">Hasil Validasi</x-nav-link>
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

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    Logout
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @endauth

            </div>
        </div>
    </div>
</nav>