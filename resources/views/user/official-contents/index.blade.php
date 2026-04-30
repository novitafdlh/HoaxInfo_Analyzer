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
<x-portal-shell title="Konten Resmi" mode="user">
            <div x-data="{ activeContent: null, categoryOpen: false }" @keydown.escape.window="activeContent = null; categoryOpen = false" class="space-y-6">
                <section class="space-y-4">
                    <div class="flex justify-between items-end">
                        <div>
                            <h1 class="text-4xl font-bold tracking-tight text-on-surface">Konten Resmi</h1>
                            <p class="text-lg text-on-surface-variant mt-2">Referensi yang dipakai sistem untuk analisis similarity dan pencocokan konten.</p>
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
                                        <p class="text-base font-bold text-blue-950">Panduan Konten Resmi</p>
                                        <p class="text-xs text-blue-900/60">3 arahan singkat untuk mencari dan membaca referensi resmi.</p>
                                    </div>
                                </div>
                                <button
                                    aria-label="Toggle panduan konten resmi"
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
                                        <h3 class="text-sm font-bold text-slate-900">Cari Lebih Cepat</h3>
                                        <p class="mt-1 text-xs leading-relaxed text-slate-600">Gunakan kolom pencarian untuk menyaring judul referensi resmi yang paling relevan.</p>
                                    </div>
                                    <div class="rounded-2xl border border-slate-200 bg-white/80 p-3">
                                        <div class="mb-2 inline-flex rounded-full bg-amber-100 px-2.5 py-1 text-[11px] font-extrabold tracking-[0.18em] text-amber-700">02</div>
                                        <h3 class="text-sm font-bold text-slate-900">Gunakan Kategori</h3>
                                        <p class="mt-1 text-xs leading-relaxed text-slate-600">Filter kategori membantu mempersempit hasil ketika jumlah referensi resmi sudah cukup banyak.</p>
                                    </div>
                                    <div class="rounded-2xl border border-rose-200 bg-rose-50/80 p-3">
                                        <div class="mb-2 inline-flex rounded-full bg-rose-100 px-2.5 py-1 text-[11px] font-extrabold tracking-[0.18em] text-rose-700">03</div>
                                        <h3 class="text-sm font-bold text-slate-900">Buka Detail</h3>
                                        <p class="mt-1 text-xs leading-relaxed text-slate-600">Masuk ke halaman detail untuk melihat metadata, sumber, dan konteks referensi yang dipakai sistem.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-[0px_20px_40px_rgba(25,28,30,0.06)]">
                    <div class="mb-6 space-y-4">
                            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                                <div>
                                    <h3 class="mt-2 text-xl font-black text-slate-950">Galeri Konten Resmi</h3>
                                </div>
                                <div class="flex flex-wrap items-center gap-3 self-start lg:self-auto">
                                    <p class="text-italic text-slate-500">{{ $officialContents->count() }} konten ditampilkan</p>
                                </div>
                        </div>

                        <form method="GET" action="{{ route('user.official.index') }}" class="flex flex-col gap-3 xl:flex-row">
                            <div class="relative flex-1">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-4.35-4.35M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z"></path>
                                    </svg>
                                </span>
                                <input
                                    type="search"
                                    name="search"
                                    value="{{ $search }}"
                                    placeholder="Cari"
                                    class="w-full rounded-2xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm text-slate-700 shadow-sm transition placeholder:text-slate-400 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100"
                                >
                            </div>
                            <div class="relative flex flex-wrap gap-3" @click.away="categoryOpen = false">
                                <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-blue-600 px-5 py-3 text-sm font-extrabold text-white shadow-lg shadow-blue-100 transition hover:bg-blue-700">
                                    Cari
                                </button>
                                <button type="button" @click="categoryOpen = !categoryOpen" class="inline-flex h-12 w-12 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-700 shadow-sm transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700" aria-label="Kategori">
                                    <span class="material-symbols-outlined text-[20px]">category</span>
                                </button>

                                <div x-cloak x-show="categoryOpen" x-transition.origin.top.right class="absolute right-0 top-14 z-20 w-72 rounded-[1.5rem] border border-slate-200 bg-white p-3 shadow-[0px_20px_40px_rgba(25,28,30,0.12)]">
                                    <p class="px-3 py-2 text-[11px] font-black uppercase tracking-[0.18em] text-slate-500">Filter Kategori</p>

                                    <div class="mt-2 flex flex-wrap gap-2">
                                        <a
                                            href="{{ route('user.official.index', array_filter(['search' => $search])) }}"
                                            class="{{ $selectedCategory === '' ? 'bg-blue-600 text-white shadow-lg shadow-blue-100' : 'border border-slate-200 bg-white text-slate-700 hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700' }} inline-flex items-center rounded-full px-4 py-2 text-xs font-bold transition"
                                        >
                                            Semua
                                        </a>

                                        @foreach ($categories as $category)
                                            <a
                                                href="{{ route('user.official.index', array_filter(['category' => $category, 'search' => $search])) }}"
                                                class="{{ $selectedCategory === $category ? 'bg-blue-600 text-white shadow-lg shadow-blue-100' : 'border border-slate-200 bg-white text-slate-700 hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700' }} inline-flex items-center rounded-full px-4 py-2 text-xs font-bold transition"
                                            >
                                                {{ $category }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    @if ($officialContents->isEmpty())
                        <div class="rounded-[2rem] border border-dashed border-slate-200 bg-slate-50 px-6 py-14 text-center">
                            <p class="text-lg font-bold text-slate-900">Belum Ada Konten Resmi</p>
                            <p class="mt-2 text-sm text-slate-500">{{ $selectedCategory !== '' ? 'Kategori yang dipilih belum memiliki konten resmi.' : 'Basis data referensi resmi belum terisi.' }}</p>
                        </div>
                    @else
                        <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                            @foreach ($officialContents as $content)
                                <button
                                    type="button"
                                    @click="activeContent = {{ \Illuminate\Support\Js::from([
                                        'title' => $content->title,
                                        'category' => $content->category ?: 'Umum',
                                        'image_url' => $content->image_url,
                                        'source_type_label' => $content->source_type === 'url' ? 'URL resmi' : 'Unggah manual',
                                        'source_url' => $content->source_url,
                                        'created_at_label' => $content->created_at?->format('d M Y, H:i'),
                                        'detail_url' => route('user.official.show', $content),
                                    ]) }}"
                                    class="group relative h-full text-left focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                >
                                    <div class="flex h-full flex-col overflow-hidden rounded-[2rem] border border-slate-200 bg-[linear-gradient(180deg,#ffffff_0%,#f8fbff_100%)] shadow-[0_10px_24px_rgba(15,23,42,0.06)] transition-all duration-300 ease-out group-hover:-translate-y-2 group-hover:border-slate-300 group-hover:shadow-[0_24px_48px_rgba(15,23,42,0.14)]">
                                        <div class="relative bg-slate-100 p-3">
                                            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(59,130,246,0.16),_transparent_48%)] opacity-80 transition duration-300 group-hover:opacity-100"></div>
                                            <div class="relative aspect-[16/10] overflow-hidden rounded-[1.5rem]">
                                                <img
                                                    src="{{ $content->image_url }}"
                                                    alt="{{ $content->title }}"
                                                    class="h-full w-full object-cover transition duration-500 ease-out group-hover:scale-[1.04]"
                                                >
                                            </div>
                                        </div>

                                        <div class="flex flex-1 flex-col gap-3 px-5 pb-5 pt-4">
                                            <div class="flex flex-wrap items-center gap-2 text-xs font-bold uppercase tracking-[0.18em] text-slate-500">
                                                <span class="inline-flex rounded-full bg-blue-50 px-3 py-1 text-[11px] text-blue-700">
                                                    {{ $content->category ?: 'Umum' }}
                                                </span>
                                                <span class="h-1 w-1 rounded-full bg-slate-300"></span>
                                                <span>{{ $content->source_type === 'url' ? 'URL resmi' : 'Unggah manual' }}</span>
                                            </div>

                                            <h3 class="min-h-[3.5rem] text-lg font-black leading-snug text-slate-950 transition-colors duration-300 group-hover:text-blue-700">
                                                {{ $content->title }}
                                            </h3>

                                            <div class="mt-auto flex items-center justify-between gap-3 pt-2 text-sm font-semibold text-slate-500">
                                                <span>{{ $content->created_at?->format('d M Y') }}</span>
                                                <span class="inline-flex items-center gap-1 text-blue-700">
                                                    Lihat detail
                                                    <svg class="h-4 w-4 transition duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                    </svg>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    @endif
                </section>

                <div x-cloak x-show="activeContent" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/80 px-4 py-6">
                    <div x-show="activeContent" x-transition.scale @click.away="activeContent = null" class="w-full max-w-6xl overflow-hidden rounded-[2rem] border border-white/10 bg-white shadow-2xl shadow-slate-950/40">
                        <div class="grid max-h-[90vh] overflow-y-auto lg:grid-cols-[minmax(0,1.5fr)_minmax(20rem,1fr)]">
                            <div class="flex items-center justify-center bg-[radial-gradient(circle_at_top,_rgba(37,99,235,0.22),_transparent_45%),linear-gradient(180deg,_#0f172a,_#020617)] p-4 sm:p-6">
                                <img :src="activeContent?.image_url" :alt="activeContent?.title" class="block max-h-[78vh] w-auto max-w-full rounded-2xl object-contain shadow-2xl shadow-slate-950/30">
                            </div>

                            <div class="flex flex-col justify-between p-6 sm:p-8">
                                <div>
                                    <div class="flex items-start justify-between gap-4">
                                        <div>
                                            <p class="text-xs font-bold uppercase tracking-[0.24em] text-blue-600" x-text="activeContent?.category"></p>
                                            <h3 class="mt-3 text-2xl font-black leading-tight text-slate-950" x-text="activeContent?.title"></h3>
                                        </div>
                                        <button type="button" @click="activeContent = null" class="rounded-full border border-slate-200 p-2 text-slate-500 transition hover:border-rose-200 hover:bg-rose-50 hover:text-rose-700">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </div>

                                    <div class="mt-8 space-y-4">
                                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                            <p class="text-xs font-bold uppercase tracking-widest text-slate-500">Metode Sumber</p>
                                            <p class="mt-2 text-sm font-semibold text-slate-900" x-text="activeContent?.source_type_label"></p>
                                        </div>
                                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                            <p class="text-xs font-bold uppercase tracking-widest text-slate-500">Ditambahkan</p>
                                            <p class="mt-2 text-sm font-semibold text-slate-900" x-text="activeContent?.created_at_label"></p>
                                        </div>
                                        <template x-if="activeContent?.source_url">
                                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                                <p class="text-xs font-bold uppercase tracking-widest text-slate-500">Sumber URL</p>
                                                <p class="mt-2 break-all text-sm text-slate-700" x-text="activeContent?.source_url"></p>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                <div class="mt-8 flex flex-wrap gap-3">
                                    <a :href="activeContent?.detail_url" class="inline-flex items-center gap-2 rounded-2xl bg-blue-600 px-5 py-3 text-sm font-extrabold text-white shadow-lg shadow-blue-100 transition hover:bg-blue-700">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Lihat halaman detail
                                    </a>
                                    <button type="button" @click="activeContent = null" class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 px-5 py-3 text-sm font-bold text-slate-700 transition hover:bg-slate-50">
                                        Tutup
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    <script>
        function togglePanduan() {
            const content = document.getElementById('panduan-content');
            const icon = document.getElementById('panduan-icon');
            const isHidden = content.classList.toggle('hidden');

            icon.classList.toggle('rotate-180', isHidden);
        }
    </script>
</x-portal-shell>
