@php
    $displayName = auth()->user()?->name ?: 'Admin';
    $operationalSummaryCards = [
        [
            'label' => 'Konten Resmi',
            'icon' => 'cloud_done',
            'iconClass' => 'text-on-primary-container',
            'borderClass' => 'border-on-primary-container',
            'value' => number_format($totalOfficialContent),
            'detailIcon' => 'trending_up',
            'detailIconClass' => 'text-xs text-green-600',
            'detailTextClass' => 'text-green-600 font-bold',
            'detailValue' => sprintf('%+d%%', $officialContentGrowth),
            'detailSuffix' => 'dari bulan lalu',
            'accentClass' => 'bg-primary-fixed/30',
        ],
        [
            'label' => 'Submission',
            'icon' => 'send',
            'iconClass' => 'text-on-secondary-fixed-variant',
            'borderClass' => 'border-on-secondary-fixed-variant',
            'value' => number_format($totalSubmissions),
            'detailIcon' => 'trending_up',
            'detailIconClass' => 'text-xs text-green-600',
            'detailTextClass' => 'text-green-600 font-bold',
            'detailValue' => sprintf('%+d%%', $submissionGrowth),
            'detailSuffix' => 'antrian baru',
            'accentClass' => 'bg-secondary-fixed/30',
        ],
        [
            'label' => 'Menunggu',
            'icon' => 'pending_actions',
            'iconClass' => 'text-amber-500',
            'borderClass' => 'border-amber-500',
            'value' => number_format($pendingValidation),
            'detailIcon' => $pendingValidation > 0 ? 'priority_high' : 'check_circle',
            'detailIconClass' => $pendingValidation > 0 ? 'text-xs text-error' : 'text-xs text-emerald-600',
            'detailTextClass' => $pendingValidation > 0 ? 'text-error font-bold' : 'text-emerald-600 font-bold',
            'detailValue' => $pendingValidation > 0 ? 'Prioritas Tinggi' : 'Terkendali',
            'detailSuffix' => '',
            'accentClass' => 'bg-amber-100/50',
        ],
        [
            'label' => 'Terverifikasi',
            'icon' => 'verified',
            'iconClass' => 'text-on-tertiary-container',
            'borderClass' => 'border-on-tertiary-container',
            'value' => number_format($verifiedContent),
            'detailIcon' => 'check_circle',
            'detailIconClass' => 'text-xs text-on-tertiary-container',
            'detailTextClass' => 'text-on-tertiary-container font-bold',
            'detailValue' => 'Akurasi '.number_format($verificationAccuracy, 1).'%',
            'detailSuffix' => '',
            'accentClass' => 'bg-tertiary-fixed/30',
        ],
    ];
@endphp

<x-admin-shell title="Dashboard Admin">
    <x-slot name="pageHeader">
        <section class="space-y-4">
            <div class="flex justify-between items-end">
                <div>
                    <h1 class="text-4xl font-bold tracking-tight text-on-surface">Selamat Datang, {{ $displayName }}</h1>
                    <p class="mt-2 text-lg text-on-surface-variant">Pusat monitoring integritas informasi publik dan keputusan validasi akhir.</p>
                </div>
            </div>

            <div class="rounded-[2rem] border border-slate-200 bg-white p-2">
                <div class="p-3 md:p-4">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex cursor-pointer items-center gap-3 transition-opacity hover:opacity-80" onclick="togglePanduan()">
                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-blue-50 text-blue-700">
                                <span class="material-symbols-outlined text-[20px]">lightbulb</span>
                            </div>
                            <div>
                                <p class="text-base font-bold text-blue-950">Panduan Cepat Admin</p>
                                <p class="text-xs text-blue-900/60">3 fokus utama untuk menjaga alur validasi tetap rapi.</p>
                            </div>
                        </div>
                        <button
                            aria-label="Toggle panduan admin"
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
                                <div class="mb-2 inline-flex rounded-full bg-green-100 px-2.5 py-1 text-[11px] font-extrabold tracking-[0.18em] text-blue-700">01</div>
                                <h3 class="text-sm font-bold text-slate-900">Pantau Submission Baru</h3>
                                <p class="mt-1 text-xs leading-relaxed text-slate-600">Perhatikan antrian menunggu validasi agar laporan publik tidak terlalu lama tertunda.</p>
                            </div>
                            <div class="rounded-2xl border border-slate-200 bg-white/80 p-3">
                                <div class="mb-2 inline-flex rounded-full bg-amber-100 px-2.5 py-1 text-[11px] font-extrabold tracking-[0.18em] text-amber-700">02</div>
                                <h3 class="text-sm font-bold text-slate-900">Kelola Referensi Resmi</h3>
                                <p class="mt-1 text-xs leading-relaxed text-slate-600">Semakin rapi basis konten resmi, semakin akurat sistem mengenali konten yang beredar.</p>
                            </div>
                            <div class="rounded-2xl border border-slate-200 bg-white/80 p-3">
                                <div class="mb-2 inline-flex rounded-full bg-rose-100 px-2.5 py-1 text-[11px] font-extrabold tracking-[0.18em] text-blue-700">03</div>
                                <h3 class="text-sm font-bold text-slate-900">Tetapkan Status Final</h3>
                                <p class="mt-1 text-xs leading-relaxed text-slate-600">Gunakan similarity, confidence, dan konteks sumber untuk menetapkan hasil akhir dengan hati-hati.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </x-slot>

    <div class=" p-6 md:overflow-hidden md:rounded-lg md:border bg-white/80 md:border-slate-200 md:shadow-[0px_20px_40px_rgba(25,28,30,0.06)]">
        <section>
            <div class="mb-8 flex items-center justify-between">
                <h3 class="text-2xl font-bold tracking-tight text-on-surface">Ringkasan Operasional</h3>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
                @foreach ($operationalSummaryCards as $card)
                    <div class="relative overflow-hidden rounded-lg border-l-8 {{ $card['borderClass'] }} bg-white p-8 shadow-[0px_20px_40px_rgba(25,28,30,0.06)]">
                        <div class="mb-4 flex items-start justify-between">
                            <p class="text-sm font-bold uppercase tracking-wider text-on-secondary-container">{{ $card['label'] }}</p>
                            <span class="material-symbols-outlined {{ $card['iconClass'] }}" data-icon="{{ $card['icon'] }}">{{ $card['icon'] }}</span>
                        </div>

                        <p class="mb-2 text-4xl font-black text-slate-950">{{ $card['value'] }}</p>

                        <p class="flex items-center gap-1 text-xs font-medium text-on-surface-variant">
                            <span class="material-symbols-outlined {{ $card['detailIconClass'] }}" data-icon="{{ $card['detailIcon'] }}">{{ $card['detailIcon'] }}</span>
                            <span class="{{ $card['detailTextClass'] }}">{{ $card['detailValue'] }}</span>
                            @if ($card['detailSuffix'] !== '')
                                <span>{{ $card['detailSuffix'] }}</span>
                            @endif
                        </p>

                        <div class="absolute -bottom-4 -right-4 h-24 w-24 rounded-full blur-3xl {{ $card['accentClass'] }}"></div>
                    </div>
                @endforeach
            </div>
        </section>
    </div>

    <script>
        function togglePanduan() {
            const content = document.getElementById('panduan-content');
            const icon = document.getElementById('panduan-icon');
            const isHidden = content.classList.toggle('hidden');

            icon.classList.toggle('rotate-180', isHidden);
        }
    </script>
</x-admin-shell>
