<a class="flex flex-col items-center gap-1 {{ request()->routeIs('admin.dashboard') ? 'text-blue-700' : 'text-slate-500' }}" href="{{ route('admin.dashboard') }}">
    <span class="material-symbols-outlined" @if (request()->routeIs('admin.dashboard')) style="font-variation-settings: 'FILL' 1, 'wght' 600, 'GRAD' 0, 'opsz' 24;" @endif>dashboard</span>
    <span class="text-[10px] font-bold">Dashboard</span>
</a>

<a class="flex flex-col items-center gap-1 {{ request()->routeIs('official.*') ? 'text-blue-700' : 'text-slate-500' }}" href="{{ route('official.index') }}">
    <span class="material-symbols-outlined" @if (request()->routeIs('official.*')) style="font-variation-settings: 'FILL' 1, 'wght' 600, 'GRAD' 0, 'opsz' 24;" @endif>verified</span>
    <span class="text-[10px] font-bold">Konten</span>
</a>

<div class="relative -top-8">
    <a class="flex h-14 w-14 items-center justify-center rounded-full bg-slate-900 text-white shadow-xl shadow-slate-300/50 transition hover:bg-slate-800" href="{{ route('official.create') }}">
        <span class="material-symbols-outlined text-[28px]">add</span>
    </a>
</div>

<a class="flex flex-col items-center gap-1 {{ request()->routeIs('admin.submissions.*') ? 'text-blue-700' : 'text-slate-500' }}" href="{{ route('admin.submissions.index') }}">
    <span class="material-symbols-outlined" @if (request()->routeIs('admin.submissions.*')) style="font-variation-settings: 'FILL' 1, 'wght' 600, 'GRAD' 0, 'opsz' 24;" @endif>inbox</span>
    <span class="text-[10px] font-bold">Submission</span>
</a>
