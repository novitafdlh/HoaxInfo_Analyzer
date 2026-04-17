<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4 pb-2">
            <div class="p-2.5 bg-indigo-50 rounded-xl text-indigo-600 shadow-inner border border-indigo-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight leading-tight">
                    {{ __('Hasil Analisis Saya') }}
                </h2>
                <p class="text-xs font-medium text-slate-500 uppercase tracking-widest mt-0.5">Riwayat similarity, confidence, dan review admin</p>
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-gradient-to-b from-slate-50 to-white min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-xl shadow-slate-100/70">
                <p class="text-sm text-slate-600 leading-relaxed flex items-center gap-3">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Halaman ini menampilkan tingkat kemiripan, confidence system, metode analisis, dan status review admin untuk setiap konten yang Anda kirimkan.
                </p>
            </div>

            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-xl shadow-slate-100/70">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Gambar</th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Similarity</th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Confidence</th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Rekomendasi Sistem</th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-widest text-slate-500 whitespace-nowrap">Status Final (Admin)</th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-widest text-slate-500 whitespace-nowrap">Tanggal & Waktu</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse ($submissions as $submission)
                                @php
                                    $similarityTone = $submission->similarity_score >= 85 ? 'bg-emerald-500' : ($submission->similarity_score >= 60 ? 'bg-amber-500' : ($submission->similarity_score >= 30 ? 'bg-orange-500' : 'bg-rose-500'));
                                    $finalTone = match ($submission->final_status) {
                                        'terverifikasi' => 'bg-emerald-100 text-emerald-800 shadow-emerald-100/50',
                                        'perlu_tindak_lanjut', 'tidak_valid' => 'bg-rose-100 text-rose-800 shadow-rose-100/50',
                                        default => 'bg-amber-100 text-amber-800 shadow-amber-100/50',
                                    };
                                @endphp
                                <tr class="hover:bg-slate-50 transition duration-150">
                                    <td class="px-6 py-4">
                                        <img src="{{ asset('storage/'.$submission->image_path) }}" alt="Submission" class="h-16 w-24 rounded-xl border border-slate-200 object-cover shadow-sm">
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-700 font-medium">
                                        @if ($submission->similarity_score !== null)
                                            <div class="flex items-center gap-2">
                                                <div class="w-2.5 h-2.5 rounded-full {{ $similarityTone }}"></div>
                                                {{ $submission->similarity_score.'%' }}
                                            </div>
                                        @else
                                            <span class="text-slate-400 font-normal italic">N/A</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-700">
                                        {{ $submission->confidence_label ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-700">
                                        <div class="font-semibold text-slate-800">{{ $submission->systemStatusLabel() }}</div>
                                        <div class="text-xs text-slate-500 mt-1">{{ $submission->analysisMethodLabel() }}</div>
                                        @if ($submission->matchedOfficialContent)
                                            <div class="text-xs text-slate-500 mt-1">Referensi: {{ $submission->matchedOfficialContent->title }}</div>
                                            <a
                                                href="{{ $submission->matchedOfficialContent->source_url ?: route('user.official.show', $submission->matchedOfficialContent) }}"
                                                @if ($submission->matchedOfficialContent->source_url) target="_blank" rel="noopener noreferrer" @endif
                                                class="mt-2 inline-flex items-center gap-2 rounded-xl border border-slate-200 px-3 py-1.5 text-xs font-bold text-slate-700 hover:bg-slate-50 transition"
                                            >
                                                Lihat konten resmi
                                            </a>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1.5 text-xs font-bold shadow-sm {{ $finalTone }}">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            {{ $submission->finalStatusLabel() }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-700 whitespace-nowrap">
                                        {{ $submission->created_at?->format('d M Y, H:i') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <svg class="w-12 h-12 mx-auto text-slate-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        <p class="text-base font-semibold text-slate-700">Belum Ada Hasil Analisis</p>
                                        <p class="text-sm text-slate-500 mt-1">
                                            Kirimkan konten pertama Anda melalui Dashboard untuk melihat hasilnya di sini.
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($submissions->hasPages())
                    <div class="border-t border-slate-100 px-6 py-4 bg-slate-50/50">
                        {{ $submissions->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
