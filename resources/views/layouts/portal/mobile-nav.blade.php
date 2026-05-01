@php
    $resolvedMode = $mode ?? (auth()->user()?->role === 'admin' ? 'admin' : 'user');
@endphp

@if ($resolvedMode === 'admin')
    <a class="flex flex-col items-center gap-1 {{ request()->routeIs('admin.dashboard') ? 'text-blue-700' : 'text-on-surface-variant' }}" href="{{ route('admin.dashboard') }}">
        <span class="material-symbols-outlined" @if (request()->routeIs('admin.dashboard')) style="font-variation-settings: 'FILL' 1;" @endif>dashboard</span>
        <span class="text-[10px] {{ request()->routeIs('admin.dashboard') ? 'font-bold' : 'font-medium' }}">Dashboard</span>
    </a>

    <a class="flex flex-col items-center gap-1 {{ request()->routeIs('official.*') ? 'text-blue-700' : 'text-on-surface-variant' }}" href="{{ route('official.index') }}">
        <span class="material-symbols-outlined" @if (request()->routeIs('official.*')) style="font-variation-settings: 'FILL' 1;" @endif>verified</span>
        <span class="text-[10px] {{ request()->routeIs('official.*') ? 'font-bold' : 'font-medium' }}">Konten</span>
    </a>

    <div class="relative -top-8">
        <a class="flex h-14 w-14 items-center justify-center rounded-full bg-primary text-on-primary shadow-lg" href="{{ route('official.create') }}">
            <span class="material-symbols-outlined text-2xl">add</span>
        </a>
    </div>

    <a class="flex flex-col items-center gap-1 {{ request()->routeIs('admin.submissions.*') ? 'text-blue-700' : 'text-on-surface-variant' }}" href="{{ route('admin.submissions.index') }}">
        <span class="material-symbols-outlined" @if (request()->routeIs('admin.submissions.*')) style="font-variation-settings: 'FILL' 1;" @endif>inbox</span>
        <span class="text-[10px] {{ request()->routeIs('admin.submissions.*') ? 'font-bold' : 'font-medium' }}">Submission</span>
    </a>

    <a class="flex flex-col items-center gap-1 {{ request()->routeIs('admin.users.*') ? 'text-blue-700' : 'text-on-surface-variant' }}" href="{{ route('admin.users.index') }}">
        <span class="material-symbols-outlined" @if (request()->routeIs('admin.users.*')) style="font-variation-settings: 'FILL' 1;" @endif>group</span>
        <span class="text-[10px] {{ request()->routeIs('admin.users.*') ? 'font-bold' : 'font-medium' }}">User</span>
    </a>
@elseif ($resolvedMode === 'guest')
    <a class="flex flex-col items-center gap-1 {{ request()->routeIs('dashboard') ? 'text-blue-700' : 'text-on-surface-variant' }}" href="{{ route('dashboard') }}">
        <span class="material-symbols-outlined" @if (request()->routeIs('dashboard')) style="font-variation-settings: 'FILL' 1;" @endif>dashboard</span>
        <span class="text-[10px] {{ request()->routeIs('dashboard') ? 'font-bold' : 'font-medium' }}">Dashboard</span>
    </a>
@else
    <a class="flex flex-col items-center gap-1 {{ request()->routeIs('user.dashboard') ? 'text-blue-700' : 'text-on-surface-variant' }}" href="{{ route('user.dashboard') }}">
        <span class="material-symbols-outlined" @if (request()->routeIs('user.dashboard')) style="font-variation-settings: 'FILL' 1;" @endif>dashboard</span>
        <span class="text-[10px] {{ request()->routeIs('user.dashboard') ? 'font-bold' : 'font-medium' }}">Dashboard</span>
    </a>

    <a class="flex flex-col items-center gap-1 {{ request()->routeIs('user.official.*') ? 'text-blue-700' : 'text-on-surface-variant' }}" href="{{ route('user.official.index') }}">
        <span class="material-symbols-outlined" @if (request()->routeIs('user.official.*')) style="font-variation-settings: 'FILL' 1;" @endif>verified</span>
        <span class="text-[10px] {{ request()->routeIs('user.official.*') ? 'font-bold' : 'font-medium' }}">Konten</span>
    </a>

    <div class="relative -top-8">
        <a class="flex h-14 w-14 items-center justify-center rounded-full bg-primary text-on-primary shadow-lg" href="{{ route('user.dashboard') }}#validation-form">
            <span class="material-symbols-outlined text-2xl">add</span>
        </a>
    </div>

    <a class="flex flex-col items-center gap-1 {{ request()->routeIs('user.validation-results') ? 'text-blue-700' : 'text-on-surface-variant' }}" href="{{ route('user.validation-results') }}">
        <span class="material-symbols-outlined" @if (request()->routeIs('user.validation-results')) style="font-variation-settings: 'FILL' 1;" @endif>fact_check</span>
        <span class="text-[10px] {{ request()->routeIs('user.validation-results') ? 'font-bold' : 'font-medium' }}">Hasil</span>
    </a>
@endif
