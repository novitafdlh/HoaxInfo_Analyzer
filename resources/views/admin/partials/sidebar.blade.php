<div class="flex flex-col w-64 h-full min-h-screen bg-white border-r border-slate-100 shadow-sm">
    <div class="flex items-center gap-3 h-20 px-6 border-b border-slate-100">
        <div class="p-2 bg-indigo-100 rounded-lg text-indigo-700 shadow-inner">
             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
        </div>
        <span class="text-xs font-black text-slate-900 uppercase tracking-widest leading-tight">SHA<br>Admin</span>
    </div>

    <nav class="flex-1 px-4 py-6 space-y-3 bg-white">
        
        {{-- Ikon Section Title (Opsional) --}}
        <p class="px-4 text-xs font-semibold uppercase tracking-widest text-slate-400">Pusat Navigasi</p>

        {{-- Menu Home (Dashboard Admin) --}}
        <a href="{{ route('admin.dashboard') }}" 
           class="flex items-center gap-3.5 px-4 py-3 rounded-2xl transition-all duration-150 group
            {{ request()->routeIs('admin.dashboard') 
               ? 'bg-indigo-50 text-indigo-800 font-bold' 
               : 'text-slate-600 hover:bg-slate-50/50 hover:text-slate-900' }}">
            
            {{-- Ikon Home (Heroicons) --}}
            <svg class="w-5 h-5 transition-colors duration-150
                {{ request()->routeIs('admin.dashboard') ? 'text-indigo-600' : 'text-slate-400 group-hover:text-slate-600' }}" 
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            <span class="text-sm">Home</span>
        </a>

        {{-- Menu Official Content --}}
        <a href="{{ route('official.index') }}" 
           class="flex items-center gap-3.5 px-4 py-3 rounded-2xl transition-all duration-150 group
            {{ request()->routeIs('official.*') 
               ? 'bg-indigo-50 text-indigo-800 font-bold' 
               : 'text-slate-600 hover:bg-slate-50/50 hover:text-slate-900' }}">
            
            {{-- Ikon Dokumen Bercentang (Heroicons) --}}
            <svg class="w-5 h-5 transition-colors duration-150
                {{ request()->routeIs('official.*') ? 'text-indigo-600' : 'text-slate-400 group-hover:text-slate-600' }}" 
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
            </svg>
            <span class="text-sm">Official Content</span>
        </a>

        {{-- Menu Submission Masyarakat --}}
        <a href="{{ route('admin.submissions.index') }}" 
           class="flex items-center gap-3.5 px-4 py-3 rounded-2xl transition-all duration-150 group
            {{ request()->routeIs('admin.submissions.*') 
               ? 'bg-indigo-50 text-indigo-800 font-bold' 
               : 'text-slate-600 hover:bg-slate-50/50 hover:text-slate-900' }}">
            
            {{-- Ikon Inbox/Surat Masuk (Heroicons) --}}
            <svg class="w-5 h-5 transition-colors duration-150
                {{ request()->routeIs('admin.submissions.*') ? 'text-indigo-600' : 'text-slate-400 group-hover:text-slate-600' }}" 
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0l-8 5-8-5"></path>
            </svg>
            <span class="text-sm whitespace-nowrap overflow-hidden text-ellipsis">Submission Masyarakat</span>
        </a>

    </nav>

    <div class="px-4 py-4 border-t border-slate-100 bg-slate-50/50 rounded-b-3xl">
         <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center gap-3.5 w-full px-4 py-3 text-sm font-semibold text-rose-700 rounded-2xl hover:bg-rose-100 transition-colors group">
                <svg class="w-5 h-5 text-rose-500 group-hover:text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                Keluar Aplikasi
            </button>
        </form>
    </div>
</div>