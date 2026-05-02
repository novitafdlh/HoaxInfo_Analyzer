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
                    <h1 class="text-3xl font-bold tracking-tight text-on-surface">Submission Masyarakat</h1>
                    <p class="mt-2 text-x text-on-surface-variant">Tinjau laporan masyarakat, bandingkan dengan referensi resmi, dan tetapkan status validasi akhir.</p>
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
                    <h2 class="mt-1 font-black tracking-tight text-slate-950">Daftar review aktif</h2>
                </div>
            </div>

                    <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-xl shadow-slate-100/70 transition-all duration-300 hover:shadow-indigo-50">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-100">
                                <thead class="bg-white">
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
                                            $systemTone = match ($submission->system_status) {
                                                'terverifikasi_otomatis' => 'bg-emerald-100 text-emerald-800',
                                                'mendekati_valid' => 'bg-teal-100 text-teal-800',
                                                'perlu_validasi_manual' => 'bg-amber-100 text-amber-800',
                                                'waspada' => 'bg-orange-100 text-orange-800',
                                                'tidak_terverifikasi' => 'bg-rose-100 text-rose-800',
                                                default => 'bg-slate-100 text-slate-700',
                                            };
                                            $finalTone = match ($submission->final_status) {
                                                'terverifikasi' => 'bg-emerald-100 text-emerald-800 shadow-emerald-100/50',
                                                'perlu_tindak_lanjut', 'tidak_valid' => 'bg-rose-100 text-rose-800 shadow-rose-100/50',
                                                default => 'bg-amber-100 text-amber-800 shadow-amber-100/50',
                                            };
                                        @endphp
                                        <tr class="hover:bg-slate-50 transition duration-150 group">
                                            <td class="px-6 py-4">
                                                @if ($submission->image_url)
                                                    <img src="{{ $submission->image_url }}" alt="Submission" class="h-16 w-24 rounded-xl border border-slate-200 object-cover shadow-sm">
                                                @else
                                                    <div class="mx-auto flex h-16 w-24 items-center justify-center rounded-xl border border-slate-200 bg-slate-50 text-slate-400 shadow-sm">
                                                        <span class="material-symbols-outlined text-[22px]">image_not_supported</span>
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-sm font-semibold text-slate-800">
                                                @if ($submission->similarity_score !== null)
                                                    <div class="flex items-center gap-2">
                                                        <div class="w-2.5 h-2.5 rounded-full {{ $similarityTone }}"></div>
                                                        {{ $submission->similarity_score.'%' }}
                                                    </div>
                                                @else
                                                    <span class="text-slate-400 font-normal italic">N/A</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                                                {{ $submission->confidence_label ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 text-sm font-medium">
                                                <div class="inline-flex items-center gap-1 rounded-full px-3 py-1 text-xs font-bold shadow-inner {{ $systemTone }}">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    {{ $submission->systemStatusLabel() }}
                                                </div>
                                                <div class="mt-2 text-xs text-slate-500">{{ $submission->analysisMethodLabel() }}</div>
                                                @if ($submission->matchedOfficialContent)
                                                    <div class="mt-1 text-xs text-slate-500">{{ $submission->matchedOfficialContent->title }}</div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-sm">
                                                <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1.5 text-xs font-bold shadow-sm {{ $finalTone }}">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    {{ $submission->finalStatusLabel() }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm">
                                                <a
                                                    href="{{ route('admin.submissions.show', $submission) }}"
                                                    aria-label="Detail dan review submission {{ $submission->id }}"
                                                    title="Detail & Review"
                                                    class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-indigo-600 text-white shadow-lg shadow-indigo-100 transition hover:bg-indigo-700 active:scale-[0.98]"
                                                >
                                                    <span class="material-symbols-outlined text-[20px]">fact_check</span>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-12 text-center">
                                                <svg class="w-16 h-16 mx-auto text-slate-300 mb-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0l-8 5-8-5"></path></svg>
                                                <p class="text-lg font-bold text-slate-900 tracking-tight">Antrean Review Kosong</p>
                                                <p class="text-sm text-slate-500 mt-1.5 max-w-sm mx-auto">
                                                    Saat ini belum ada konten baru dari masyarakat yang perlu ditinjau.
                                                </p>
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
