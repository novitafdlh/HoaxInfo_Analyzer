<x-app-layout>
    {{-- Header Halaman Admin yang Tepercaya & Modern --}}
    <x-slot name="header">
        <div class="flex items-center gap-4 pb-2 border-b border-slate-100">
            {{-- Ikon Inbox/Submission (Opsional) --}}
            <div class="p-2.5 bg-indigo-100 rounded-xl text-indigo-700 shadow-inner border border-indigo-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0l-8 5-8-5"></path>
                </svg>
            </div>
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight leading-tight">
                    {{ __('Antrean Validasi Masyarakat') }}
                </h2>
                <p class="text-xs font-medium text-slate-500 uppercase tracking-widest mt-0.5">Tinjau Konten Publik terhadap Referensi Resmi</p>
            </div>
        </div>
    </x-slot>

    {{-- Background Gradien Lembut agar Lebih Santai & Nyaman --}}
    <div class="py-10 bg-gradient-to-b from-slate-50 to-white min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid gap-8 lg:grid-cols-12">
                
                {{-- Sidebar Admin (Memakai Kolom Lebih Sedikit agar Tabel Luas) --}}
                <div class="lg:col-span-3">
                    <div class="sticky top-24 rounded-3xl overflow-hidden border border-slate-200 bg-white shadow-sm hover:shadow-md transition-shadow">
                        @include('admin.partials.sidebar')
                    </div>
                </div>

                {{-- Area Utama Tabel Konten (Interaktif & Modern) --}}
                <div class="space-y-8 lg:col-span-9">
                    
                    {{-- Kotak Deskripsi Halaman --}}
                    <div class="rounded-3xl border border-slate-200 bg-white p-7 shadow-xl shadow-slate-100/70 transition-all duration-300 hover:shadow-indigo-50">
                        <p class="text-sm text-slate-600 leading-relaxed flex items-center gap-3">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Halaman ini menampilkan antrean gambar yang dikirimkan masyarakat untuk diverifikasi kesesuaiannya dengan basis data konten resmi SHA.
                        </p>
                    </div>

                    {{-- Bagian Tabel (Modern & Interaktif) --}}
                    <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-xl shadow-slate-100/70 transition-all duration-300 hover:shadow-indigo-50">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-100">
                                {{-- Table Header --}}
                                <thead class="bg-white">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-widest text-slate-500 hover:text-indigo-600 transition-colors cursor-pointer">Gambar</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-widest text-slate-500 hover:text-indigo-600 transition-colors cursor-pointerwhitespace-nowrap">Similarity Score</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-widest text-slate-500 hover:text-indigo-600 transition-colors cursor-pointer">Status Sistem</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-widest text-slate-500 hover:text-indigo-600 transition-colors cursor-pointerwhitespace-nowrap">Status Final (Review)</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Aksi</th>
                                    </tr>
                                </thead>
                                
                                {{-- Table Body --}}
                                <tbody class="divide-y divide-slate-100 bg-white">
                                    @forelse ($submissions as $submission)
                                        <tr class="hover:bg-slate-50 transition duration-150 group">
                                            {{-- Kolom Gambar --}}
                                            <td class="px-6 py-4">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('storage/'.$submission->image_path) }}" alt="Submission" class="h-16 w-24 rounded-xl border border-slate-200 object-cover shadow-sm group-hover:scale-105 transition-transform duration-300">
                                                </div>
                                            </td>
                                            {{-- Kolom Similarity --}}
                                            <td class="px-6 py-4 text-sm font-semibold text-slate-800">
                                                @if ($submission->similarity_score !== null)
                                                    <div class="flex items-center gap-2">
                                                        <div class="w-2.5 h-2.5 rounded-full {{ $submission->similarity_score == 100 ? 'bg-emerald-500' : ($submission->similarity_score >= 50 ? 'bg-amber-500' : 'bg-rose-500') }}"></div>
                                                        {{ $submission->similarity_score.'%' }}
                                                    </div>
                                                @else
                                                    <span class="text-slate-400 font-normal italic">N/A</span>
                                                @endif
                                            </td>
                                           {{-- Kolom Status Sistem (Interaktif dengan Badges) --}}
                                            <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                                                @if ($submission->system_status === 'cocok')
                                                    {{-- Status: Cocok (Full Hijau, melambangkan validasi otomatis sukses) --}}
                                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-800 shadow-inner">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                        Tervalidasi Otomatis
                                                    </span>
                                                @elseif ($submission->system_status === 'mirip')
                                                    {{-- Status: Mirip (Kuning/Indigo, melambangkan antrean review manual) --}}
                                                    <span class="inline-flex items-center gap-1 rounded-full bg-indigo-50 px-3 py-1 text-xs font-bold text-indigo-700 shadow-inner border border-indigo-100">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                        Butuh Review
                                                    </span>
                                                @elseif ($submission->system_status === 'tidak_valid')
                                                    {{-- Status: Tidak Valid (Merah, melambangkan hoax otomatis) --}}
                                                    <span class="inline-flex items-center gap-1 rounded-full bg-rose-100 px-3 py-1 text-xs font-bold text-rose-800 shadow-inner">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                        Hoax Otomatis
                                                    </span>
                                                @else
                                                    {{-- Status: Lainnya atau Error (Abu-abu) --}}
                                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-700 shadow-inner">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                        Gagal Analisis
                                                    </span>
                                                @endif
                                            </td>
                                            {{-- Kolom Status Final (Badge) --}}
                                            <td class="px-6 py-4 text-sm">
                                                @if($submission->final_status === 'terverifikasi')
                                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-3 py-1.5 text-xs font-bold text-emerald-800 shadow-sm shadow-emerald-100/50">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                        Tervalidasi
                                                    </span>
                                                @elseif($submission->final_status === 'tidak_valid')
                                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-rose-100 px-3 py-1.5 text-xs font-bold text-rose-800 shadow-sm shadow-rose-100/50">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                        Tidak Valid
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-100 px-3 py-1.5 text-xs font-bold text-amber-800 shadow-sm shadow-amber-100/50 pulse">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                        Butuh Review
                                                    </span>
                                                @endif
                                            </td>
                                            {{-- Kolom Aksi --}}
                                            <td class="px-6 py-4 text-sm">
                                                <a href="{{ route('admin.submissions.show', $submission) }}" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-xs font-extrabold text-white shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition active:scale-[0.98]">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                                    Detail & Validasi
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        {{-- Tampilan State Kosong yang Ramah --}}
                                        <tr>
                                            <td colspan="5" class="px-6 py-12 text-center">
                                                <svg class="w-16 h-16 mx-auto text-slate-300 mb-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0l-8 5-8-5"></path></svg>
                                                <p class="text-lg font-bold text-slate-900 tracking-tight">Antrean Validasi Kosong</p>
                                                <p class="text-sm text-slate-500 mt-1.5 max-w-sm mx-auto">
                                                    Kerja bagus, Admin! Saat ini belum ada konten baru dari masyarakat yang memerlukan verifikasi manual.
                                                </p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        {{-- Pagination (Lebih Rapi) --}}
                        @if ($submissions->hasPages())
                            <div class="border-t border-slate-100 px-6 py-4 bg-slate-50/50">
                                {{ $submissions->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>