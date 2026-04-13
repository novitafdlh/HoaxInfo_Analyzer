<x-app-layout>
    {{-- Header Halaman yang Lebih Modern & Rapi --}}
    <x-slot name="header">
        <div class="flex items-center gap-4 pb-2">
            {{-- Ikon Arsip/Hasil (Opsional) --}}
            <div class="p-2.5 bg-indigo-50 rounded-xl text-indigo-600 shadow-inner border border-indigo-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight leading-tight">
                    {{ __('Hasil Validasi Saya') }}
                </h2>
                <p class="text-xs font-medium text-slate-500 uppercase tracking-widest mt-0.5">Riwayat pemeriksaan konten Anda</p>
            </div>
        </div>
    </x-slot>

    {{-- Background Lembut agar Lebih Santai --}}
    <div class="py-10 bg-gradient-to-b from-slate-50 to-white min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            {{-- Kotak Informasi --}}
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-xl shadow-slate-100/70">
                <p class="text-sm text-slate-600 leading-relaxed flex items-center gap-3">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Halaman ini menampilkan hasil validasi mendetail untuk setiap konten gambar yang Anda kirimkan.
                </p>
            </div>

            {{-- Bagian Tabel (Modern & Interaktif) --}}
            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-xl shadow-slate-100/70">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100">
                        {{-- Table Header --}}
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Gambar</th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Kecocokan (AI)</th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Status Sistem</th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-widest text-slate-500 whitespace-nowrap">Status Final (Admin)</th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-widest text-slate-500 whitespace-nowrap">Tanggal & Waktu</th>
                            </tr>
                        </thead>
                        
                        {{-- Table Body --}}
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse ($submissions as $submission)
                                <tr class="hover:bg-slate-50 transition duration-150">
                                    {{-- Kolom Gambar --}}
                                    <td class="px-6 py-4">
                                        <div class="flex-shrink-0">
                                            <img src="{{ asset('storage/'.$submission->image_path) }}" alt="Submission" class="h-16 w-24 rounded-xl border border-slate-200 object-cover shadow-sm group-hover:scale-105 transition-transform duration-300">
                                        </div>
                                    </td>
                                    {{-- Kolom Similarity --}}
                                    <td class="px-6 py-4 text-sm text-slate-700 font-medium">
                                        @if ($submission->similarity_score !== null)
                                            <div class="flex items-center gap-2">
                                                <div class="w-2.5 h-2.5 rounded-full {{ $submission->similarity_score == 100 ? 'bg-emerald-500' : ($submission->similarity_score >= 50 ? 'bg-amber-500' : 'bg-rose-500') }}"></div>
                                                {{ $submission->similarity_score.'%' }}
                                            </div>
                                        @else
                                            <span class="text-slate-400 font-normal italic">N/A</span>
                                        @endif
                                    </td>
                                    {{-- Kolom Status Sistem --}}
                                    <td class="px-6 py-4 text-sm text-slate-700 capitalize">
                                        {{ $submission->system_status ?? '-' }}
                                    </td>
                                    {{-- Kolom Status Final --}}
                                    <td class="px-6 py-4 text-sm">
                                        @if ($submission->final_status === 'terverifikasi')
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-3 py-1.5 text-xs font-bold text-emerald-800 shadow-sm shadow-emerald-100/50">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                Tervalidasi Kominfo
                                            </span>
                                        @elseif ($submission->final_status === 'tidak_valid')
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-rose-100 px-3 py-1.5 text-xs font-bold text-rose-800 shadow-sm shadow-rose-100/50">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                Konten Tidak Valid
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-100 px-3 py-1.5 text-xs font-bold text-amber-800 shadow-sm shadow-amber-100/50">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                Menunggu Review Admin
                                            </span>
                                        @endif
                                    </td>
                                    {{-- Kolom Tanggal --}}
                                    <td class="px-6 py-4 text-sm text-slate-700 whitespace-nowrap">
                                        {{ $submission->created_at?->format('d M Y, H:i') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <svg class="w-12 h-12 mx-auto text-slate-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        <p class="text-base font-semibold text-slate-700">Belum Ada Hasil Validasi</p>
                                        <p class="text-sm text-slate-500 mt-1">
                                            Kirimkan konten pertama Anda melalui Dashboard untuk melihat hasilnya di sini.
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
</x-app-layout>