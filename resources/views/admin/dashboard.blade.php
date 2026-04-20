<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <div class="p-2.5 bg-indigo-600 rounded-xl shadow-lg shadow-indigo-100">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                </svg>
            </div>
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight">
                    Dashboard Admin
                </h2>
                <p class="text-xs font-medium text-slate-500 uppercase tracking-widest">Sistem Validasi Informasi Publik</p>
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid gap-8 lg:grid-cols-4">
                
                <aside class="lg:col-span-1">
                    <div class="sticky top-24 rounded-3xl overflow-hidden border border-slate-200 bg-white shadow-sm">
                        @include('admin.partials.sidebar')
                    </div> 
                </aside>

                <main class="space-y-8 lg:col-span-3">
                    
                    <div class="relative overflow-hidden rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
                        <div class="relative z-10">
                            <h3 class="text-xl font-bold text-slate-900 mb-2">Selamat Datang Kembali, Admin</h3>
                            <p class="text-slate-600 leading-relaxed max-w-2xl text-sm">
                                Pantau dan kelola integritas informasi publik dalam satu panel terpadu. Verifikasi kontribusi masyarakat dan pastikan basis data konten resmi tetap akurat.
                            </p>
                        </div>
                        {{-- Background Decoration --}}
                        <div class="absolute -right-16 -top-16 h-48 w-48 rounded-full bg-indigo-50 opacity-60"></div>
                        <div class="absolute right-10 bottom-0 h-24 w-24 rounded-full bg-blue-50 opacity-40"></div>
                    </div>

                    <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-4">
                        
                        <div class="group rounded-3xl border border-slate-200 bg-white p-6 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                            <div class="flex items-center justify-between mb-5">
                                <div class="rounded-2xl bg-blue-50 p-3 text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                </div>
                            </div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Konten Resmi</p>
                            <div class="flex items-baseline gap-1 mt-1">
                                <p class="text-3xl font-black text-slate-900">{{ number_format($totalOfficialContent) }}</p>
                                <span class="text-xs font-bold text-slate-400">Unit</span>
                            </div>
                        </div>

                        <div class="group rounded-3xl border border-slate-200 bg-white p-6 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                            <div class="flex items-center justify-between mb-5">
                                <div class="rounded-2xl bg-indigo-50 p-3 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-300">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            </div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Submission</p>
                            <div class="flex items-baseline gap-1 mt-1">
                                <p class="text-3xl font-black text-slate-900">{{ number_format($totalSubmissions) }}</p>
                                <span class="text-xs font-bold text-slate-400">Berkas</span>
                            </div>
                        </div>

                        <div class="group rounded-3xl border border-amber-200 bg-amber-50 p-6 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                            <div class="flex items-center justify-between mb-5">
                                <div class="rounded-2xl bg-white p-3 text-amber-600 shadow-sm group-hover:bg-amber-600 group-hover:text-white transition-colors duration-300">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <span class="animate-pulse flex h-2 w-2 rounded-full bg-amber-500"></span>
                            </div>
                            <p class="text-xs font-bold text-amber-700 uppercase tracking-wider">Menunggu</p>
                            <div class="flex items-baseline gap-1 mt-1">
                                <p class="text-3xl font-black text-amber-900">{{ number_format($pendingValidation) }}</p>
                                <span class="text-xs font-bold text-amber-700/60">Perlu Review</span>
                            </div>
                        </div>

                        <div class="group rounded-3xl border border-emerald-200 bg-emerald-50 p-6 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                            <div class="flex items-center justify-between mb-5">
                                <div class="rounded-2xl bg-white p-3 text-emerald-600 shadow-sm group-hover:bg-emerald-600 group-hover:text-white transition-colors duration-300">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                            </div>
                            <p class="text-xs font-bold text-emerald-700 uppercase tracking-wider">Terverifikasi</p>
                            <div class="flex items-baseline gap-1 mt-1">
                                <p class="text-3xl font-black text-emerald-900">{{ number_format($verifiedContent) }}</p>
                                <span class="text-xs font-bold text-emerald-700/60">Tervalidasi</span>
                            </div>
                        </div>

                    </div>
                </main>
            </div>
        </div>
    </div>
</x-app-layout>