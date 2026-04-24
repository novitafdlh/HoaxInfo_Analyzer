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
            <div class="flex justify-between">
                <div>
                    <h2 class="text-3xl font-black tracking-tight text-slate-950 sm:text-4xl">Submission Masyarakat</h2>
                </div>
                <div class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-600 shadow-sm">
                    <span class="material-symbols-outlined text-[18px] text-blue-600">monitoring</span>
                    {{ number_format($totalSubmissions) }} total submission
                </div>
            </div>

            <div class="overflow-hidden rounded-[2rem] border border-blue-100 bg-gradient-to-r from-white via-blue-50 to-cyan-50 shadow-[0px_12px_28px_rgba(37,99,235,0.08)]">
                            
            </div>
        </section>
    </x-slot>

    <section class="space-y-6">
        <div class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-[0px_20px_40px_rgba(25,28,30,0.06)]">
            <div class="flex items-start gap-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-blue-50 text-blue-700">
                    <span class="material-symbols-outlined text-[20px]">lightbulb</span>
                </div>
                <div>
                    <p class="text-sm font-black uppercase tracking-[0.18em] text-slate-500">Panduan Singkat</p>
                    <p class="mt-2 text-sm leading-relaxed text-slate-600">
                        Halaman ini menampilkan antrean gambar masyarakat beserta similarity, confidence sistem, dan referensi resmi terdekat untuk membantu validasi manual admin secara lebih terstruktur.
                    </p>
                </div>
            </div>
        </div>

        <div class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-[0px_20px_40px_rgba(25,28,30,0.06)]">
            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-5">
                <div>
                    <h2 class="mt-1 text-xl font-black tracking-tight text-slate-950">Daftar review aktif</h2>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100">
                    <thead class="bg-slate-50/80">
                        <tr>
                            <th class="px-6 py-4 text-left text-[11px] font-black uppercase tracking-[0.18em] text-slate-500">Gambar</th>
                            <th class="px-6 py-4 text-left text-[11px] font-black uppercase tracking-[0.18em] text-slate-500">Similarity</th>
                            <th class="px-6 py-4 text-left text-[11px] font-black uppercase tracking-[0.18em] text-slate-500">Confidence</th>
                            <th class="px-6 py-4 text-left text-[11px] font-black uppercase tracking-[0.18em] text-slate-500">Rekomendasi Sistem</th>
                            <th class="px-6 py-4 text-left text-[11px] font-black uppercase tracking-[0.18em] text-slate-500">Status Final</th>
                            <th class="px-6 py-4 text-left text-[11px] font-black uppercase tracking-[0.18em] text-slate-500">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($submissions as $submission)
                            @php
                                $similarityTone = $submission->similarity_score >= 85 ? 'bg-emerald-500' : ($submission->similarity_score >= 60 ? 'bg-amber-500' : ($submission->similarity_score >= 30 ? 'bg-orange-500' : 'bg-rose-500'));
                                $systemTone = match ($submission->system_status) {
                                    'terverifikasi_otomatis' => 'border-emerald-200 bg-emerald-50 text-emerald-800',
                                    'mendekati_valid' => 'border-teal-200 bg-teal-50 text-teal-800',
                                    'perlu_validasi_manual' => 'border-amber-200 bg-amber-50 text-amber-800',
                                    'waspada' => 'border-orange-200 bg-orange-50 text-orange-800',
                                    'tidak_terverifikasi' => 'border-rose-200 bg-rose-50 text-rose-800',
                                    default => 'border-slate-200 bg-slate-50 text-slate-700',
                                };
                                $finalTone = match ($submission->final_status) {
                                    'terverifikasi' => 'border-emerald-200 bg-emerald-50 text-emerald-800',
                                    'perlu_tindak_lanjut', 'tidak_valid' => 'border-rose-200 bg-rose-50 text-rose-800',
                                    default => 'border-amber-200 bg-amber-50 text-amber-800',
                                };
                            @endphp
                            <tr class="transition duration-200 hover:bg-slate-50/80">
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-4">
                                        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-slate-50 shadow-sm">
                                            <img src="{{ $submission->image_url }}" alt="Submission" class="h-20 w-28 object-cover">
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-black text-slate-900">Konten masyarakat</p>
                                            <p class="mt-1 text-xs text-slate-500">
                                                {{ $submission->matchedOfficialContent?->title ? 'Referensi: '.$submission->matchedOfficialContent->title : 'Belum ada referensi resmi yang cocok' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-sm font-semibold text-slate-800">
                                    @if ($submission->similarity_score !== null)
                                        <div class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-3 py-2">
                                            <div class="h-2.5 w-2.5 rounded-full {{ $similarityTone }}"></div>
                                            {{ $submission->similarity_score.'%' }}
                                        </div>
                                    @else
                                        <span class="text-slate-400 italic">N/A</span>
                                    @endif
                                </td>
                                <td class="px-6 py-5 text-sm font-semibold text-slate-700">
                                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                                        {{ $submission->confidence_label ?? '-' }}
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-sm font-medium">
                                    <div class="inline-flex items-center gap-2 rounded-full border px-3 py-2 text-xs font-black {{ $systemTone }}">
                                        <span class="material-symbols-outlined text-[18px]">psychology</span>
                                        {{ $submission->systemStatusLabel() }}
                                    </div>
                                    <div class="mt-3 text-xs font-semibold text-slate-500">{{ $submission->analysisMethodLabel() }}</div>
                                    @if ($submission->matchedOfficialContent)
                                        <div class="mt-1 text-xs text-slate-500">{{ $submission->matchedOfficialContent->title }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-5 text-sm">
                                    <span class="inline-flex items-center gap-2 rounded-full border px-3 py-2 text-xs font-black {{ $finalTone }}">
                                        <span class="material-symbols-outlined text-[18px]">verified</span>
                                        {{ $submission->finalStatusLabel() }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 text-sm">
                                    <a href="{{ route('admin.submissions.show', $submission) }}" class="inline-flex items-center gap-2 rounded-full bg-slate-900 px-4 py-3 text-xs font-black text-white shadow-lg shadow-slate-300/40 transition hover:bg-slate-800">
                                        <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                                        Detail & Review
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
</x-admin-shell>
