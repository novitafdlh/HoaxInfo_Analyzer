@php
    $submissionCode = '#SUB-'.str_pad((string) $submission->id, 5, '0', STR_PAD_LEFT);
    $similarityScore = $submission->similarity_score !== null ? (float) $submission->similarity_score : 0.0;
    $similarityPercent = max(0, min(100, round($similarityScore, 2)));
    $confidenceLabel = $submission->confidence_label ?: 'Belum tersedia';
    $ocrLines = collect(preg_split("/\r\n|\n|\r/", (string) $submission->extracted_text))
        ->map(fn ($line) => trim($line))
        ->filter()
        ->values();

    $ocrItems = [
        ['label' => 'OCR 1', 'value' => $ocrLines->get(0, '-')],
        ['label' => 'OCR 2', 'value' => $ocrLines->get(1, '-')],
        ['label' => 'OCR 3', 'value' => $ocrLines->get(2, '-')],
    ];

    [$confidenceTone, $confidenceBadgeClass] = match (strtolower($confidenceLabel)) {
        'sangat tinggi', 'sangat_tinggi' => ['#10b981', 'bg-emerald-100 text-emerald-800'],
        'tinggi', 'high' => ['#84cc16', 'bg-lime-100 text-lime-800'],
        'sedang', 'medium' => ['#f59e0b', 'bg-amber-100 text-amber-800'],
        'rendah', 'low' => ['#f43f5e', 'bg-rose-100 text-rose-800'],
        'sangat rendah', 'sangat_rendah' => ['#dc2626', 'bg-red-100 text-red-800'],
        default => ['#94a3b8', 'bg-slate-100 text-slate-700'],
    };

    [$similarityTextClass, $similarityBarClass] = match (true) {
        $similarityPercent >= 85 => ['text-on-tertiary-container', 'bg-on-tertiary-container'],
        $similarityPercent >= 60 => ['text-amber-700', 'bg-amber-500'],
        $similarityPercent >= 30 => ['text-orange-700', 'bg-orange-500'],
        default => ['text-rose-700', 'bg-rose-600'],
    };

    $matchedOfficialContent = $submission->matchedOfficialContent;
    $summaryCards = [
        [
            'title' => $submissionCode,
            'subtitle' => $submission->finalStatusLabel(),
            'body' => 'Status final saat ini untuk submission yang sedang direview.',
        ],
        [
            'title' => $submission->systemStatusLabel(),
            'subtitle' => 'Rekomendasi Sistem',
            'body' => $submission->analysisMethodLabel(),
        ],
        [
            'title' => $confidenceLabel,
            'subtitle' => 'Confidence',
            'body' => 'Tingkat keyakinan sistem terhadap hasil analisis saat ini.',
        ],
        [
            'title' => $matchedOfficialContent?->title ?: 'Belum ada referensi',
            'subtitle' => 'Konten Resmi Terkait',
            'body' => $matchedOfficialContent?->category ?: 'Belum ada kategori referensi.',
        ],
    ];
@endphp

<x-admin-shell title="Detail Submission Admin">
    <x-slot name="pageHeader">
        <section class="space-y-2">
            <div class="max-w-6xl mx-auto space-y-12">
                <header class="flex justify-between items-end">
                    <div>
                        <h1 class="text-3xl font-black tracking-tight text-slate-950 sm:text-4xl">Validation Detail</h1>
                    </div>
                </header>

                @if (session('status'))
                    <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-800">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="rounded-xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-800">
                        <ul class="list-disc space-y-1 pl-5 font-medium">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-2 gap-12">
                        <section class="rounded-xl bg-surface-container-lowest p-8 shadow-[0px_20px_40px_rgba(25,28,30,0.06)]">
                            <div class="mb-8 flex justify-between items-center">
                                <h2 class="text-headline-sm font-bold flex items-center gap-3">
                                    <span class="material-symbols-outlined text-on-primary-container">description</span>
                                    Submitted Document
                                </h2>
                                <span class="px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest {{ $confidenceBadgeClass }}">{{ $confidenceLabel }}</span>
                            </div>
                            <div class="relative group aspect-[16/10] bg-surface-container rounded-lg overflow-hidden">
                                <img alt="Submitted document scan" class="w-full h-full object-cover" src="{{ $submission->image_url }}">
                                <div class="absolute inset-0 bg-primary/0 group-hover:bg-primary/10 transition-colors flex items-center justify-center">
                                    <a href="{{ $submission->image_url }}" target="_blank" rel="noopener noreferrer" class="opacity-0 group-hover:opacity-100 bg-white text-primary px-6 py-3 rounded-full font-bold transition-opacity flex items-center gap-2">
                                        <span class="material-symbols-outlined">zoom_in</span> View Full Resolution
                                    </a>
                                </div>
                            </div>
                        </section>

                        <div class="grid grid-cols-2 gap-8">
                            <section class="bg-surface-container-low rounded-xl p-8">
                                <h3 class="text-label-md font-bold text-on-surface-variant uppercase tracking-widest mb-6">OCR Extraction</h3>
                                <div class="space-y-2">
                                    @foreach ($ocrItems as $item)
                                        <div class="space-y-1">
                                            <label class="text-xs text-on-surface-variant font-medium">{{ $item['label'] }}</label>
                                            <div class="text-lg font-semibold text-on-surface break-words">{{ $item['value'] }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            </section>

                            <section class="bg-surface-container-low rounded-xl p-8">
                                <h3 class="text-label-md font-bold text-on-surface-variant uppercase tracking-widest mb-6">Similarity Analysis</h3>
                                <div class="space-y-8">
                                    <div class="flex items-center justify-between">
                                        <div class="space-y-1">
                                            <div class="text-3xl font-black {{ $similarityTextClass }}">{{ number_format($similarityPercent, 2) }}%</div>
                                            <div class="text-xs text-on-surface-variant font-medium">{{ $submission->similarity_label ?: 'Identity Match' }}</div>
                                        </div>
                                        <div class="w-16 h-16 rounded-full border-4 {{ str_replace('text-', 'border-', $similarityTextClass) }} flex items-center justify-center">
                                            <span class="material-symbols-outlined {{ $similarityTextClass }}" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                                        </div>
                                    </div>
                                    
                                    <p class="text-sm text-on-surface-variant leading-relaxed">{{ $submission->systemStatusLabel() }}. {{ $submission->analysisMethodLabel() }}.</p>
                                </div>

                            </div>
                        </div>

                        <div class="mt-10 pt-8 flex flex-wrap items-center gap-4 border-t border-slate-100">
                            <h4 class="text-base font-bold text-slate-900 tracking-tight mr-2">Keputusan Manual Admin:</h4>

                            <form
                                method="POST"
                                action="{{ route('admin.submissions.update-status', $submission) }}"
                                data-review-confirm
                                data-confirm-tone="emerald"
                                data-confirm-title="Tetapkan sebagai terverifikasi?"
                                data-confirm-message="Keputusan ini akan menandai submission sebagai konten yang sudah terverifikasi oleh admin."
                                data-confirm-button="Ya, Tetapkan"
                            >
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="final_status" value="terverifikasi">
                                <button type="submit" class="inline-flex items-center gap-2 rounded-2xl bg-emerald-600 px-6 py-3.5 text-sm font-extrabold text-white shadow-lg shadow-emerald-100 hover:bg-emerald-700 transition active:scale-[0.98]">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Tetapkan Terverifikasi
                                </button>
                            </form>

                            <form
                                method="POST"
                                action="{{ route('admin.submissions.update-status', $submission) }}"
                                data-review-confirm
                                data-confirm-tone="rose"
                                data-confirm-title="Tandai perlu tindak lanjut?"
                                data-confirm-message="Submission ini akan masuk status perlu tindak lanjut agar dapat diverifikasi atau diperiksa kembali."
                                data-confirm-button="Ya, Tandai"
                            >
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="final_status" value="perlu_tindak_lanjut">
                                <button type="submit" class="inline-flex items-center gap-2 rounded-2xl bg-rose-600 px-6 py-3.5 text-sm font-extrabold text-white shadow-lg shadow-rose-100 hover:bg-rose-700 transition active:scale-[0.98]">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Tandai Perlu Tindak Lanjut
                                </button>
                            </form>

                            <form method="POST" action="{{ route('admin.submissions.update-status', $submission) }}" onsubmit="return confirm('Kembalikan status konten ini ke menunggu validasi admin?');">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="final_status" value="menunggu_validasi">
                                <button type="submit" class="inline-flex items-center gap-2 rounded-2xl bg-slate-700 px-6 py-3.5 text-sm font-extrabold text-white shadow-lg shadow-slate-200 hover:bg-slate-800 transition active:scale-[0.98]">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                    Kembalikan ke Review
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="col-span-4 space-y-8"></div>
                </div>
            </div>
        </section>

        <div id="review-confirm-modal" class="fixed inset-0 z-[120] hidden items-center justify-center p-4" aria-hidden="true">
            <div class="absolute inset-0 bg-slate-950/45 backdrop-blur-sm" data-confirm-cancel></div>

            <div class="relative w-full max-w-md overflow-hidden rounded-[2rem] border border-white/70 bg-white p-6 shadow-[0px_30px_60px_rgba(15,23,42,0.22)] sm:p-7">
                <div class="flex items-start gap-4">
                    <div id="review-confirm-icon" class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-700">
                        <span class="material-symbols-outlined" id="review-confirm-icon-symbol">verified</span>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-xs font-black uppercase tracking-[0.18em] text-slate-500">Konfirmasi Review</p>
                        <h3 id="review-confirm-title" class="mt-2 text-xl font-black tracking-tight text-slate-900">Tetapkan sebagai terverifikasi?</h3>
                        <p id="review-confirm-message" class="mt-3 text-sm leading-relaxed text-slate-600"></p>
                    </div>
                </div>

                <div class="mt-7 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                    <button type="button" data-confirm-cancel class="inline-flex items-center justify-center rounded-full border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-700 shadow-sm transition hover:bg-slate-50">
                        Batal
                    </button>
                    <button type="button" id="review-confirm-submit" class="inline-flex items-center justify-center gap-2 rounded-full bg-emerald-600 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-emerald-100 transition hover:bg-emerald-700">
                        <span class="material-symbols-outlined text-[18px]" id="review-confirm-submit-icon">check_circle</span>
                        <span id="review-confirm-submit-label">Ya, Tetapkan</span>
                    </button>
                </div>
            </div>
        </div>

        <script>
            (() => {
                const modal = document.getElementById('review-confirm-modal');
                const title = document.getElementById('review-confirm-title');
                const message = document.getElementById('review-confirm-message');
                const icon = document.getElementById('review-confirm-icon');
                const iconSymbol = document.getElementById('review-confirm-icon-symbol');
                const submitButton = document.getElementById('review-confirm-submit');
                const submitIcon = document.getElementById('review-confirm-submit-icon');
                const submitLabel = document.getElementById('review-confirm-submit-label');
                let activeForm = null;

                const tones = {
                    emerald: {
                        iconClass: 'bg-emerald-100 text-emerald-700',
                        buttonClass: 'bg-emerald-600 hover:bg-emerald-700 shadow-emerald-100',
                        icon: 'verified',
                        submitIcon: 'check_circle',
                    },
                    rose: {
                        iconClass: 'bg-rose-100 text-rose-700',
                        buttonClass: 'bg-rose-600 hover:bg-rose-700 shadow-rose-100',
                        icon: 'priority_high',
                        submitIcon: 'flag',
                    },
                };

                const closeModal = () => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                    modal.setAttribute('aria-hidden', 'true');
                    activeForm = null;
                };

                const openModal = (form) => {
                    const tone = tones[form.dataset.confirmTone] || tones.emerald;

                    activeForm = form;
                    title.textContent = form.dataset.confirmTitle || 'Konfirmasi review?';
                    message.textContent = form.dataset.confirmMessage || 'Pastikan keputusan admin sudah sesuai dengan hasil pemeriksaan.';
                    submitLabel.textContent = form.dataset.confirmButton || 'Ya, Lanjutkan';
                    iconSymbol.textContent = tone.icon;
                    submitIcon.textContent = tone.submitIcon;
                    icon.className = `flex h-12 w-12 shrink-0 items-center justify-center rounded-full ${tone.iconClass}`;
                    submitButton.className = `inline-flex items-center justify-center gap-2 rounded-full px-5 py-3 text-sm font-bold text-white shadow-lg transition ${tone.buttonClass}`;
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                    modal.setAttribute('aria-hidden', 'false');
                    submitButton.focus();
                };

                document.querySelectorAll('form[data-review-confirm]').forEach((form) => {
                    form.addEventListener('submit', (event) => {
                        if (form.dataset.confirmed === 'true') {
                            return;
                        }

                        event.preventDefault();
                        openModal(form);
                    });
                });

                submitButton.addEventListener('click', () => {
                    if (!activeForm) {
                        return;
                    }

                    activeForm.dataset.confirmed = 'true';
                    activeForm.submit();
                    closeModal();
                });

                document.querySelectorAll('[data-confirm-cancel]').forEach((button) => {
                    button.addEventListener('click', closeModal);
                });

                document.addEventListener('keydown', (event) => {
                    if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                        closeModal();
                    }
                });
            })();
        </script>
    </x-slot>
</x-admin-shell>
