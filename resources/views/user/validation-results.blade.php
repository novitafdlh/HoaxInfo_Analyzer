@php
    $currentUser = auth()->user();
    $displayName = $currentUser?->name ?: 'Pengguna';
    $nameParts = preg_split('/\s+/', trim($displayName)) ?: [];
    $profileInitials = collect($nameParts)
        ->filter()
        ->take(2)
        ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
        ->implode('');
    $profileInitials = $profileInitials !== '' ? $profileInitials : 'P';
@endphp
<x-portal-shell title="Hasil Analisis Saya" mode="user">
                <section class="space-y-4">
                    <div class="flex justify-between items-end">
                        <div>
                            <h1 class="text-4xl font-bold tracking-tight text-on-surface">Hasil Analisis Saya</h1>
                            <p class="text-lg text-on-surface-variant mt-2">Riwayat similarity, confidence, dan review admin untuk setiap konten yang Anda kirimkan.</p>
                        </div>
                    </div>

                    <div class="overflow-hidden rounded-lg border border-blue-100 bg-gradient-to-r from-white via-blue-50 to-cyan-50 shadow-[0px_10px_24px_rgba(37,99,235,0.08)] transition-all duration-500">
                        <div class="p-3 md:p-4">
                            <div class="flex items-start gap-3">
                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-primary-container">
                                    <span class="material-symbols-outlined text-[20px] text-on-primary-container">lightbulb</span>
                                </div>
                                <div>
                                    <p class="text-base font-bold text-blue-950">Ringkasan Hasil Validasi</p>
                                    <p class="text-sm leading-relaxed text-blue-900/70 mt-1">
                                        Halaman ini menampilkan tingkat kemiripan, confidence system, metode analisis, dan status review admin untuk setiap konten yang Anda kirimkan.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="overflow-hidden rounded-xl border border-slate-200 bg-surface-container-lowest shadow-[0px_20px_40px_rgba(25,28,30,0.06)]">
                    <div class="border-b border-slate-100 bg-slate-50/60 px-6 py-5 md:px-8">
                        <h2 class="text-xl font-bold tracking-tight text-slate-900">Daftar Hasil Analisis</h2>
                        <p class="mt-1 text-sm text-slate-500">Riwayat ini diperbarui berdasarkan submission terbaru yang Anda unggah.</p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-100">
                            <thead class="bg-slate-50/80">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-widest text-slate-500 text-center">Gambar</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-widest text-slate-500 text-center">Similarity</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-widest text-slate-500 text-center">Confidence</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-widest text-slate-500 text-center">Rekomendasi Sistem</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-widest text-slate-500 whitespace-nowrap text-center">Status Final (Admin)</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-widest text-slate-500 whitespace-nowrap text-center">Tanggal & Waktu</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white text-center">
                                @forelse ($submissions as $submission)
                                    @php
                                        $similarityTone = $submission->similarity_score >= 85 ? 'bg-emerald-500' : ($submission->similarity_score >= 60 ? 'bg-amber-500' : ($submission->similarity_score >= 30 ? 'bg-orange-500' : 'bg-rose-500'));
                                        $similarityTextTone = $submission->similarity_score >= 85 ? 'text-emerald-700' : ($submission->similarity_score >= 60 ? 'text-amber-700' : ($submission->similarity_score >= 30 ? 'text-orange-700' : 'text-rose-700'));
                                        $confidenceLevel = strtolower((string) $submission->confidence_label);
                                        [$confidencePercent, $confidenceBarTone, $confidenceTextTone] = match ($confidenceLevel) {
                                            'sangat tinggi', 'sangat_tinggi' => [100, 'bg-emerald-500', 'text-emerald-800'],
                                            'tinggi', 'high' => [78, 'bg-lime-500', 'text-lime-800'],
                                            'sedang', 'medium' => [56, 'bg-amber-500', 'text-amber-800'],
                                            'rendah', 'low' => [24, 'bg-rose-500', 'text-rose-800'],
                                            'sangat rendah', 'sangat_rendah' => [12, 'bg-red-600', 'text-red-800'],
                                            default => [18, 'bg-slate-400', 'text-slate-700'],
                                        };
                                        $systemTone = match ($submission->system_status) {
                                            'terverifikasi', 'valid' => 'bg-emerald-50 text-emerald-800 border-emerald-100',
                                            'perlu_tindak_lanjut' => 'bg-amber-50 text-amber-800 border-amber-100',
                                            'tidak_valid', 'tidak_terverifikasi' => 'bg-rose-50 text-rose-800 border-rose-100',
                                            default => 'bg-cyan-50 text-cyan-800 border-cyan-100',
                                        };
                                        $finalTone = match ($submission->final_status) {
                                            'terverifikasi' => 'bg-emerald-100 text-emerald-800 shadow-emerald-100/50',
                                            'perlu_tindak_lanjut', 'tidak_valid' => 'bg-rose-100 text-rose-800 shadow-rose-100/50',
                                            default => 'bg-amber-100 text-amber-800 shadow-amber-100/50',
                                        };
                                    @endphp
                                    <tr class="hover:bg-slate-50 transition duration-150">
                                        <td class="px-6 py-4">
                                            <img src="{{ $submission->image_url }}" alt="Submission" class="h-16 w-24 rounded-xl border border-slate-200 object-cover shadow-sm">
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium">
                                            @if ($submission->similarity_score !== null)
                                                <div class="flex items-center gap-2 {{ $similarityTextTone }}">
                                                    <div class="w-2.5 h-2.5 rounded-full {{ $similarityTone }}"></div>
                                                    {{ $submission->similarity_score.'%' }}
                                                </div>
                                            @else
                                                <span class="text-slate-400 font-normal italic">N/A</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            <div class="min-w-[10rem] max-w-[11rem]">
                                                <div class="text-base font-bold leading-tight {{ $confidenceTextTone }}">
                                                    {{ $submission->confidence_label ?? '-' }}
                                                </div>
                                                <div class="mt-3 h-1.5 overflow-hidden rounded-full bg-slate-200">
                                                    <div
                                                        class="h-full rounded-full {{ $confidenceBarTone }}"
                                                        style="width: {{ $confidencePercent }}%;"
                                                    ></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-slate-700">
                                            <div class="inline-flex rounded-full border px-3 py-1.5 text-xs font-bold {{ $systemTone }}">{{ $submission->systemStatusLabel() }}</div>
                                            <div class="mt-2 text-xs font-semibold text-cyan-700">{{ $submission->analysisMethodLabel() }}</div>
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
                                        <td class="px-6 py-4 text-sm whitespace-nowrap">
                                            <div class="inline-flex rounded-2xl border border-violet-100 bg-violet-50 px-3 py-2 font-semibold text-violet-800">
                                                {{ $submission->created_at?->format('d M Y, H:i') }}
                                            </div>
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
                </section>
</x-portal-shell>
