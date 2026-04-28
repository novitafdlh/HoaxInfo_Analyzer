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
<x-portal-shell title="Detail Konten Resmi" mode="user" container-class="max-w-6xl">
                <section class="space-y-4">
                    <div class="flex justify-between items-end">
                        <div>
                            <h1 class="text-4xl font-bold tracking-tight text-on-surface">{{ $officialContent->title }}</h1>
                            <p class="text-lg text-on-surface-variant mt-2">Detail konten resmi referensi yang digunakan sistem sebagai acuan analisis.</p>
                        </div>
                    </div>

                    <div class="rounded-[2rem] border border-slate-200 bg-white p-2">
                        <div class="p-3 md:p-4">
                            <div class="flex items-center justify-between gap-4">
                                <div class="flex cursor-pointer items-center gap-3 transition-opacity hover:opacity-80" onclick="togglePanduan()">
                                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-blue-50 text-blue-700">
                                        <span class="material-symbols-outlined text-[20px]">info</span>
                                    </div>
                                    <div>
                                        <p class="text-base font-bold text-blue-950">Detail Referensi Resmi</p>
                                        <p class="text-xs text-blue-900/60">3 poin utama untuk memahami referensi yang dijadikan acuan sistem.</p>
                                    </div>
                                </div>
                                <button
                                    aria-label="Toggle panduan detail referensi"
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
                                        <h3 class="text-sm font-bold text-slate-900">Baca Metadata</h3>
                                        <p class="mt-1 text-xs leading-relaxed text-slate-600">Perhatikan kategori, metode sumber, dan tanggal simpan untuk memahami konteks referensi.</p>
                                    </div>
                                    <div class="rounded-2xl border border-slate-200 bg-white/80 p-3">
                                        <div class="mb-2 inline-flex rounded-full bg-amber-100 px-2.5 py-1 text-[11px] font-extrabold tracking-[0.18em] text-amber-700">02</div>
                                        <h3 class="text-sm font-bold text-slate-900">Cek Teks OCR</h3>
                                        <p class="mt-1 text-xs leading-relaxed text-slate-600">Teks OCR membantu melihat isi referensi saat sistem melakukan pencocokan terhadap hasil unggahan.</p>
                                    </div>
                                    <div class="rounded-2xl border border-rose-200 bg-rose-50/80 p-3">
                                        <div class="mb-2 inline-flex rounded-full bg-rose-100 px-2.5 py-1 text-[11px] font-extrabold tracking-[0.18em] text-rose-700">03</div>
                                        <h3 class="text-sm font-bold text-slate-900">Buka Sumber Asli</h3>
                                        <p class="mt-1 text-xs leading-relaxed text-slate-600">Jika tersedia, gunakan URL resmi untuk memastikan referensi yang tersimpan sesuai dengan sumber publik aslinya.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="rounded-xl border border-slate-200 bg-white p-8 shadow-[0px_20px_40px_rgba(25,28,30,0.06)]">
                    <div class="grid gap-8 lg:grid-cols-2">
                        <div class="overflow-hidden rounded-3xl border border-slate-200 shadow-sm">
                            <img src="{{ $officialContent->image_url }}" alt="{{ $officialContent->title }}" class="w-full object-cover">
                        </div>

                        <div class="space-y-6">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-[0.24em] text-blue-700">{{ $officialContent->category ?: 'Umum' }}</p>
                                <h3 class="mt-2 text-3xl font-black tracking-tight text-slate-950">{{ $officialContent->title }}</h3>
                                <p class="mt-3 text-sm leading-relaxed text-slate-600">
                                    Konten ini tersimpan dalam basis data referensi resmi dan dapat menjadi tujuan tautan saat sistem menemukan kemiripan pada hasil validasi Anda.
                                </p>
                            </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                                <p class="text-xs font-bold uppercase tracking-widest text-slate-500">Metode sumber</p>
                                <p class="mt-2 text-lg font-bold text-slate-900">{{ $officialContent->source_type === 'url' ? 'URL resmi' : 'Unggah manual' }}</p>
                            </div>
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                                <p class="text-xs font-bold uppercase tracking-widest text-slate-500">Tanggal simpan</p>
                                <p class="mt-2 text-lg font-bold text-slate-900">{{ $officialContent->created_at?->format('d M Y, H:i') }}</p>
                            </div>
                        </div>

                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                                <p class="text-xs font-bold uppercase tracking-widest text-slate-500">OCR / Teks Referensi</p>
                                <p class="mt-3 text-sm leading-relaxed text-slate-700">
                                    {{ $officialContent->extracted_text ?: 'Teks OCR belum tersedia untuk konten ini.' }}
                                </p>
                            </div>

                            <div class="flex flex-wrap gap-3">
                                <a href="{{ route('user.official.index') }}" class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 px-5 py-3 text-sm font-bold text-slate-700 hover:bg-slate-50 transition">
                                    Kembali ke Daftar
                                </a>
                                @if ($officialContent->source_url)
                                    <a href="{{ $officialContent->source_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 rounded-2xl bg-blue-600 px-5 py-3 text-sm font-extrabold text-white shadow-lg shadow-blue-100 hover:bg-blue-700 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.812a4 4 0 005.656 0l4-4a4 4 0 10-5.656-5.656l-1.1 1.1"></path></svg>
                                        Buka URL Resmi
                                    </a>
                                @endif
                            </div>
                        </div>
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
</x-portal-shell>
