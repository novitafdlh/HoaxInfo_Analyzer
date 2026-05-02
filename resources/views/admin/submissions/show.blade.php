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

    $confidenceBadgeClass = match (strtolower($confidenceLabel)) {
        'sangat tinggi', 'sangat_tinggi' => 'bg-emerald-100 text-emerald-800',
        'tinggi', 'high' => 'bg-lime-100 text-lime-800',
        'sedang', 'medium' => 'bg-amber-100 text-amber-800',
        'rendah', 'low' => 'bg-rose-100 text-rose-800',
        'sangat rendah', 'sangat_rendah' => 'bg-red-100 text-red-800',
        default => 'bg-slate-100 text-slate-700',
    };

    [$similarityDotClass, $similarityTextClass, $similarityBarClass] = match (true) {
        $similarityPercent >= 85 => ['bg-emerald-500', 'text-emerald-700', 'bg-emerald-500'],
        $similarityPercent >= 60 => ['bg-amber-500', 'text-amber-700', 'bg-amber-500'],
        $similarityPercent >= 30 => ['bg-orange-500', 'text-orange-700', 'bg-orange-500'],
        default => ['bg-rose-500', 'text-rose-700', 'bg-rose-500'],
    };

    $finalStatusClass = match ($submission->final_status) {
        'terverifikasi' => 'bg-emerald-100 text-emerald-800 shadow-emerald-100/50',
        'perlu_tindak_lanjut', 'tidak_valid' => 'bg-rose-100 text-rose-800 shadow-rose-100/50',
        default => 'bg-amber-100 text-amber-800 shadow-amber-100/50',
    };

    $matchedOfficialContent = $submission->matchedOfficialContent;
@endphp

<x-admin-shell title="Detail Submission Admin">
    <x-slot name="pageHeader">
        <section class="space-y-4">
            <div class="flex flex-col gap-3 xl:flex-row xl:items-end xl:justify-between">
                <div>
                    <h1 class="mt-2 text-3xl font-bold tracking-tight text-on-surface">Validation Detail</h1>
                    <p class="mt-2 text-lg text-on-surface-variant">Periksa dokumen, hasil OCR, similarity, dan tetapkan status akhir submission.</p>
                </div>
                <a href="{{ route('admin.submissions.index') }}" class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-blue-600 text-white shadow-lg shadow-blue-100 transition hover:bg-blue-700" aria-label="Kembali ke daftar review" title="Kembali ke daftar review">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                </a>
            </div>
        </section>
    </x-slot>

    <div class="space-y-6">
        @if (session('status'))
            <div class="flex items-center gap-3 rounded-[1.5rem] border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-800 shadow-sm">
                <span class="material-symbols-outlined text-[22px] text-emerald-600">check_circle</span>
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-[1.5rem] border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-800 shadow-sm">
                <ul class="list-disc space-y-1 pl-5 font-medium">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-[2rem] border border-slate-200 bg-white p-5 shadow-[0px_20px_40px_rgba(25,28,30,0.06)]">
                <div class="flex items-center justify-between gap-3">
                    <p class="text-sm font-bold text-slate-600">Kode Submission</p>
                    <span class="material-symbols-outlined rounded-full border border-blue-100 bg-blue-50 p-2 text-blue-700 shadow-sm">tag</span>
                </div>
                <p class="mt-4 text-2xl font-black tracking-tight text-slate-950">{{ $submissionCode }}</p>
                <p class="mt-1 text-xs font-medium text-slate-500">{{ $submission->created_at?->format('d M Y, H:i') ?: '-' }}</p>
            </div>

            <div class="rounded-[2rem] border border-slate-200 bg-white p-5 shadow-[0px_20px_40px_rgba(25,28,30,0.06)]">
                <div class="flex items-center justify-between gap-3">
                    <p class="text-sm font-bold text-slate-600">Status Final</p>
                    <span class="material-symbols-outlined rounded-full border border-amber-100 bg-amber-50 p-2 text-amber-700 shadow-sm">pending_actions</span>
                </div>
                <span class="mt-4 inline-flex items-center gap-1.5 rounded-full px-3 py-1.5 text-xs font-bold shadow-sm {{ $finalStatusClass }}">
                    <span class="material-symbols-outlined text-[15px]">fact_check</span>
                    {{ $submission->finalStatusLabel() }}
                </span>
                <p class="mt-3 text-xs font-medium text-slate-500">Keputusan admin saat ini.</p>
            </div>

            <div class="rounded-[2rem] border border-slate-200 bg-white p-5 shadow-[0px_20px_40px_rgba(25,28,30,0.06)]">
                <div class="flex items-center justify-between gap-3">
                    <p class="text-sm font-bold text-slate-600">Similarity</p>
                    <span class="material-symbols-outlined rounded-full border border-emerald-100 bg-emerald-50 p-2 text-emerald-700 shadow-sm">monitoring</span>
                </div>
                <p class="mt-4 text-2xl font-black tracking-tight {{ $similarityTextClass }}">{{ number_format($similarityPercent, 2) }}%</p>
                <div class="mt-3 h-2 overflow-hidden rounded-full bg-slate-100">
                    <div class="h-full rounded-full {{ $similarityBarClass }}" style="width: {{ $similarityPercent }}%"></div>
                </div>
            </div>

            <div class="rounded-[2rem] border border-slate-200 bg-white p-5 shadow-[0px_20px_40px_rgba(25,28,30,0.06)]">
                <div class="flex items-center justify-between gap-3">
                    <p class="text-sm font-bold text-slate-600">Confidence</p>
                    <span class="material-symbols-outlined rounded-full border border-blue-100 bg-blue-50 p-2 text-blue-700 shadow-sm">verified_user</span>
                </div>
                <span class="mt-4 inline-flex items-center gap-1.5 rounded-full px-3 py-1.5 text-xs font-bold {{ $confidenceBadgeClass }}">
                    <span class="h-2 w-2 rounded-full {{ $similarityDotClass }}"></span>
                    {{ $confidenceLabel }}
                </span>
                <p class="mt-3 text-xs font-medium text-slate-500">{{ $submission->analysisMethodLabel() }}</p>
            </div>
        </section>

        <div class="grid gap-6 xl:grid-cols-[minmax(0,1.1fr)_minmax(0,0.9fr)]">
            <section class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-[0px_20px_40px_rgba(25,28,30,0.06)]">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm font-bold text-slate-600">Dokumen Submission</p>
                        <h2 class="mt-1 text-xl font-black tracking-tight text-slate-950">Gambar yang Dikirim</h2>
                    </div>
                    @if ($submission->image_url)
                        <a href="{{ $submission->image_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 rounded-full bg-blue-600 px-5 py-3 text-sm font-black text-white shadow-lg shadow-blue-100 transition hover:bg-blue-700">
                            <span class="material-symbols-outlined text-[18px]">zoom_in</span>
                            Buka gambar
                        </a>
                    @endif
                </div>

                <div class="mt-6 overflow-hidden rounded-[1.5rem] border border-slate-200 bg-slate-50/70">
                    @if ($submission->image_url)
                        <img alt="Dokumen submission {{ $submissionCode }}" class="h-[360px] w-full object-contain" src="{{ $submission->image_url }}">
                    @else
                        <div class="flex h-[360px] items-center justify-center text-slate-400">
                            <span class="material-symbols-outlined text-[48px]">image_not_supported</span>
                        </div>
                    @endif
                </div>
            </section>

            <section class="space-y-6">
                <div class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-[0px_20px_40px_rgba(25,28,30,0.06)]">
                    <p class="text-sm font-bold text-slate-600">OCR Extraction</p>
                    <h2 class="mt-1 text-xl font-black tracking-tight text-slate-950">Teks Terdeteksi</h2>

                    <div class="mt-5 space-y-3 text-sm">
                        @foreach ($ocrItems as $item)
                            <div class="rounded-[1.25rem] bg-slate-50/70 p-4">
                                <p class="text-xs font-black uppercase tracking-[0.16em] text-slate-500">{{ $item['label'] }}</p>
                                <p class="mt-2 break-words text-sm font-semibold leading-relaxed text-slate-900">{{ $item['value'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-[0px_20px_40px_rgba(25,28,30,0.06)]">
                    <p class="text-sm font-bold text-slate-600">Referensi Sistem</p>
                    <h2 class="mt-1 text-xl font-black tracking-tight text-slate-950">{{ $submission->systemStatusLabel() }}</h2>
                    <p class="mt-3 text-sm leading-relaxed text-slate-600">{{ $submission->analysisMethodLabel() }}.</p>

                    <div class="mt-5 rounded-[1.25rem] bg-slate-50/70 p-4">
                        <p class="text-xs font-black uppercase tracking-[0.16em] text-slate-500">Konten Resmi Terkait</p>
                        <p class="mt-2 text-sm font-bold text-slate-900">{{ $matchedOfficialContent?->title ?: 'Belum ada referensi' }}</p>
                        <p class="mt-1 text-xs text-slate-500">{{ $matchedOfficialContent?->category ?: 'Belum ada kategori referensi.' }}</p>
                    </div>
                </div>
            </section>
        </div>

        <section class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-[0px_20px_40px_rgba(25,28,30,0.06)]">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-sm font-bold text-slate-600">Keputusan Manual Admin</p>
                    <h2 class="mt-1 text-xl font-black tracking-tight text-slate-950">Tetapkan status validasi akhir</h2>
                </div>

                <div class="flex flex-wrap gap-3">
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
                        <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-emerald-600 px-5 py-3 text-sm font-black text-white shadow-lg shadow-emerald-100 transition hover:bg-emerald-700 sm:w-auto">
                            <span class="material-symbols-outlined text-[18px]">check_circle</span>
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
                        <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-rose-600 px-5 py-3 text-sm font-black text-white shadow-lg shadow-rose-100 transition hover:bg-rose-700 sm:w-auto">
                            <span class="material-symbols-outlined text-[18px]">flag</span>
                            Perlu Tindak Lanjut
                        </button>
                    </form>

                    <form
                        method="POST"
                        action="{{ route('admin.submissions.update-status', $submission) }}"
                        data-review-confirm
                        data-confirm-tone="slate"
                        data-confirm-title="Kembalikan ke review?"
                        data-confirm-message="Status submission akan dikembalikan ke menunggu validasi admin agar dapat direview ulang."
                        data-confirm-button="Ya, Kembalikan"
                    >
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="final_status" value="menunggu_validasi">
                        <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-slate-700 px-5 py-3 text-sm font-black text-white shadow-lg shadow-slate-200 transition hover:bg-slate-800 sm:w-auto">
                            <span class="material-symbols-outlined text-[18px]">replay</span>
                            Kembalikan ke Review
                        </button>
                    </form>
                </div>
            </div>
        </section>
    </div>

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
                slate: {
                    iconClass: 'bg-slate-100 text-slate-700',
                    buttonClass: 'bg-slate-700 hover:bg-slate-800 shadow-slate-200',
                    icon: 'replay',
                    submitIcon: 'replay',
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
</x-admin-shell>
