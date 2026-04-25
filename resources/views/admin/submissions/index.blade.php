@php
    $submissionItems = method_exists($submissions, 'items') ? collect($submissions->items()) : collect($submissions);
    $displayedSubmissions = $submissionItems->count();
    $totalSubmissions = method_exists($submissions, 'total') ? $submissions->total() : $displayedSubmissions;
    $pendingDisplayed = $submissionItems->where('final_status', 'menunggu_validasi')->count();
    $verifiedDisplayed = $submissionItems->where('final_status', 'terverifikasi')->count();
@endphp

<x-admin-shell title="Submission Admin">
    <x-slot name="pageHeader">
        <section class="space-y-4">
            <div class="flex justify-between items-end">
                <div>
                    <h2 class="text-4xl font-bold tracking-tight text-on-surface">Submission Masyarakat</h2>
                </div>
                <div class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-600 shadow-sm">
                    <span class="material-symbols-outlined text-[18px] text-blue-600">monitoring</span>
                    {{ number_format($totalSubmissions) }} total submission
                </div>
            </div>  
        </section>
    </x-slot>

    <section class="space-y-4">
        <div class="rounded-[2rem] border border-slate-200 bg-white p-2">
            <div class="p-3 md:p-4">
                <div class="flex items-center justify-between gap-4">
                    <div class="flex cursor-pointer items-center gap-3 transition-opacity hover:opacity-80" onclick="togglePanduan()">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-blue-50 text-blue-700">
                            <span class="material-symbols-outlined text-[20px]">lightbulb</span>
                        </div>
                        <div>
                            <p class="text-base font-bold text-blue-950">Panduan Singkat</p>
                            <p class="text-xs text-blue-900/60">3 fokus utama untuk menjaga antrean review admin tetap terstruktur.</p>
                        </div>
                    </div>
                    <button
                        aria-label="Toggle panduan review admin"
                        class="flex h-8 w-8 items-center justify-center rounded-full border border-blue-200 bg-white/90 text-blue-700 transition hover:bg-white"
                        type="button"
                        onclick="togglePanduan()"
                    >
                        <span class="inline-block rotate-180 text-lg font-black leading-none transition-transform duration-200" id="panduan-icon">^</span>
                    </button>
                </div>

                <div class="hidden pt-3" id="panduan-content">
                    <div class="grid grid-cols-1 gap-3 md:grid-cols-3">
                        <div class="rounded-2xl border border-slate-200 bg-white/80 p-3">
                            <div class="mb-2 inline-flex rounded-full bg-blue-100 px-2.5 py-1 text-[11px] font-extrabold tracking-[0.18em] text-blue-700">01</div>
                            <h3 class="text-sm font-bold text-slate-900">Prioritaskan Similarity</h3>
                            <p class="mt-1 text-xs leading-relaxed text-slate-600">Gunakan nilai similarity sebagai pintu masuk awal untuk menentukan tingkat kedekatan dengan referensi resmi.</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-white/80 p-3">
                            <div class="mb-2 inline-flex rounded-full bg-amber-100 px-2.5 py-1 text-[11px] font-extrabold tracking-[0.18em] text-amber-700">02</div>
                            <h3 class="text-sm font-bold text-slate-900">Baca Confidence</h3>
                            <p class="mt-1 text-xs leading-relaxed text-slate-600">Confidence membantu menilai seberapa kuat sistem mendukung rekomendasi yang ditampilkan pada submission.</p>
                        </div>
                        <div class="rounded-2xl border border-rose-200 bg-rose-50/80 p-3">
                            <div class="mb-2 inline-flex rounded-full bg-rose-100 px-2.5 py-1 text-[11px] font-extrabold tracking-[0.18em] text-rose-700">03</div>
                            <h3 class="text-sm font-bold text-slate-900">Tentukan Status Final</h3>
                            <p class="mt-1 text-xs leading-relaxed text-slate-600">Pastikan keputusan akhir selaras dengan referensi, similarity, dan konteks visual sebelum review disimpan.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-[0px_20px_40px_rgba(25,28,30,0.06)]">
            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                <div>
                    <h2 class="mt-1 text-xl font-black tracking-tight text-slate-950">Daftar review aktif</h2>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100">
                    <thead class="bg-slate-50/80">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-widest text-slate-500 text-center">Gambar</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-widest text-slate-500 text-center">Similarity</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-widest text-slate-500 text-center">Confidence</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-widest text-slate-500 text-center">Rekomendasi Sistem</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-widest text-slate-500 text-center">Status Final</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-widest text-slate-500 text-center">Aksi</th>
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
                                    'terverifikasi_otomatis' => 'bg-emerald-50 text-emerald-800 border-emerald-100',
                                    'mendekati_valid' => 'bg-cyan-50 text-cyan-800 border-cyan-100',
                                    'perlu_validasi_manual' => 'bg-amber-50 text-amber-800 border-amber-100',
                                    'waspada' => 'bg-orange-50 text-orange-800 border-orange-100',
                                    'tidak_terverifikasi' => 'bg-rose-50 text-rose-800 border-rose-100',
                                    default => 'bg-slate-50 text-slate-700 border-slate-100',
                                };
                                $finalTone = match ($submission->final_status) {
                                    'terverifikasi' => 'bg-emerald-100 text-emerald-800 shadow-emerald-100/50',
                                    'perlu_tindak_lanjut', 'tidak_valid' => 'bg-rose-100 text-rose-800 shadow-rose-100/50',
                                    default => 'bg-amber-100 text-amber-800 shadow-amber-100/50',
                                };
                            @endphp
                            <tr class="hover:bg-slate-50 transition duration-150">
                                <td class="px-6 py-4">
                                    <img src="{{ $submission->image_url }}" alt="Submission" class="mx-auto h-16 w-24 rounded-xl border border-slate-200 object-cover shadow-sm">
                                </td>
                                <td class="px-6 py-4 text-sm font-medium">
                                    @if ($submission->similarity_score !== null)
                                        <div class="inline-flex items-center gap-2 {{ $similarityTextTone }}">
                                            <div class="h-2.5 w-2.5 rounded-full {{ $similarityTone }}"></div>
                                            {{ $submission->similarity_score.'%' }}
                                        </div>
                                    @else
                                        <span class="font-normal italic text-slate-400">N/A</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <div class="mx-auto min-w-[10rem] max-w-[11rem]">
                                        <div class="text-base font-bold leading-tight {{ $confidenceTextTone }}">
                                            {{ $submission->confidence_label ?? '-' }}
                                        </div>
                                        <div class="mt-3 h-1.5 overflow-hidden rounded-full bg-slate-200">
                                            <div class="h-full rounded-full {{ $confidenceBarTone }}" style="width: {{ $confidencePercent }}%;"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-700">
                                    <div class="inline-flex rounded-full border px-3 py-1.5 text-xs font-bold {{ $systemTone }}">{{ $submission->systemStatusLabel() }}</div>
                                    <div class="mt-2 text-xs font-semibold text-cyan-700">{{ $submission->analysisMethodLabel() }}</div>
                                    @if ($submission->matchedOfficialContent)
                                        <div class="mt-1 text-xs text-slate-500">Referensi: {{ $submission->matchedOfficialContent->title }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1.5 text-xs font-bold shadow-sm {{ $finalTone }}">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        {{ $submission->finalStatusLabel() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <a
                                        href="{{ route('admin.submissions.show', $submission) }}"
                                        class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 text-slate-700 transition hover:bg-slate-50"
                                        aria-label="Detail dan review submission"
                                        title="Detail & Review"
                                    >
                                        <span class="material-symbols-outlined text-[20px]">rate_review</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div class="mx-auto flex max-w-md flex-col items-center">
                                        <div class="flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                                            <span class="material-symbols-outlined text-[32px]">inbox</span>
                                        </div>
                                        <p class="mt-5 text-xl font-black tracking-tight text-slate-950">Antrean review masih kosong</p>
                                        <p class="mt-2 text-sm leading-relaxed text-slate-500">
                                            Saat ini belum ada konten baru dari masyarakat yang perlu ditinjau admin.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($submissions->hasPages())
                <div class="border-t border-slate-100 bg-slate-50/70 px-6 py-4">
                    {{ $submissions->links() }}
                </div>
            @endif
        </div>
    </section>
    <script>
        function togglePanduan() {
            const content = document.getElementById('panduan-content');
            const icon = document.getElementById('panduan-icon');
            const isHidden = content.classList.toggle('hidden');

            icon.classList.toggle('rotate-180', isHidden);
        }
    </script>
</x-admin-shell>
