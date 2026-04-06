<aside class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
    <p class="px-2 text-xs font-semibold uppercase tracking-wide text-gray-500">Menu Admin</p>

    <nav class="mt-3 space-y-1">
        <a href="{{ route('admin.dashboard') }}"
           class="block rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50' }}">
            Home
        </a>

        <a href="{{ route('official.index') }}"
           class="block rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('official.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50' }}">
            Official Content
        </a>

        <a href="{{ route('admin.submissions.index') }}"
           class="block rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.submissions.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50' }}">
            Submission Masyarakat
        </a>
    </nav>
</aside>
