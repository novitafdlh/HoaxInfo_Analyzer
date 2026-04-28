@php
    $displayedContentCount = $officialContents->count();
    $totalContentCount = method_exists($officialContents, 'total') ? $officialContents->total() : $displayedContentCount;
@endphp

<x-admin-shell title="Konten Resmi Admin">
    <x-slot name="pageHeader">
        <section class="space-y-4">
            <div class="flex flex-col gap-4 xl:flex-row xl:items-end xl:justify-between">
                <div>
                    <h1 class="text-4xl font-bold tracking-tight text-on-surface">Konten Resmi</h1>
                </div>

                <a href="{{ route('official.create') }}" class="inline-flex items-center justify-center gap-2 rounded-full bg-slate-900 px-6 py-3.5 text-sm font-black text-white shadow-lg shadow-slate-300/50 transition hover:bg-slate-800">
                    <span class="material-symbols-outlined text-[20px]">add_circle</span>
                    Tambah Konten Resmi
                </a>
            </div>
        </section>
    </x-slot>

    <div
        class="py-10 bg-gradient-to-b from-slate-50 to-white min-h-screen"
        x-data="{
            activeContent: null,
            filterOpen: false,
            showUploadResult: {{ session()->has('upload_result') ? 'true' : 'false' }},
            uploadResult: {{ \Illuminate\Support\Js::from(session('upload_result')) }}
        }"
        @keydown.escape.window="activeContent = null; filterOpen = false; showUploadResult = false"
    >
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid gap-8 lg:grid-cols-12">
                <div class="lg:col-span-3">
                    <div class="sticky top-24 rounded-3xl overflow-hidden border border-slate-200 bg-white shadow-sm hover:shadow-md transition-shadow">
                        @include('admin.partials.sidebar')
                    </div>
                </div>

                <div class="space-y-8 lg:col-span-9">
                    @if (session('success'))
                        <div class="flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800 shadow-sm shadow-emerald-100/50">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="rounded-3xl border border-slate-200 bg-white p-7 shadow-xl shadow-slate-100/70">
                        <div class="flex items-start justify-between gap-4">
                            <p class="text-sm text-slate-600 leading-relaxed flex items-center gap-3">
                                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Konten resmi ditampilkan dari yang terbaru. Gunakan tombol filter untuk memilih kategori tertentu saat diperlukan.
                            </p>

                            <div class="relative shrink-0" @click.away="filterOpen = false">
                                <button type="button" @click="filterOpen = !filterOpen" class="inline-flex h-12 w-12 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-700 shadow-sm transition hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-700">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h18M6 12h12m-9 8h6"></path>
                                    </svg>
                                </button>

                                <div x-cloak x-show="filterOpen" x-transition.origin.top.right class="absolute right-0 z-20 mt-3 w-64 rounded-3xl border border-slate-200 bg-white p-3 shadow-2xl shadow-slate-200/70">
                                    <p class="px-3 pb-2 text-xs font-bold uppercase tracking-[0.24em] text-slate-400">Filter Kategori</p>
                                    <div class="space-y-1">
                                        <a href="{{ route('official.index', array_filter(['search' => $search])) }}" class="flex items-center justify-between rounded-2xl px-3 py-2.5 text-sm font-semibold transition {{ $selectedCategory === '' ? 'bg-indigo-600 text-white' : 'text-slate-700 hover:bg-slate-50' }}">
                                            <span>Semua kategori</span>
                                            @if ($selectedCategory === '')
                                                <span class="text-xs font-bold">Aktif</span>
                                            @endif
                                        </a>
                                        @foreach ($categories as $category)
                                            <a href="{{ route('official.index', array_filter(['category' => $category, 'search' => $search])) }}" class="flex items-center justify-between rounded-2xl px-3 py-2.5 text-sm font-semibold transition {{ $selectedCategory === $category ? 'bg-indigo-600 text-white' : 'text-slate-700 hover:bg-slate-50' }}">
                                                <span>{{ $category }}</span>
                                                @if ($selectedCategory === $category)
                                                    <span class="text-xs font-bold">Aktif</span>
                                                @endif
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

            <form method="GET" action="{{ route('official.index') }}" class="mt-5 flex flex-col gap-3 sm:flex-row">
                <div class="relative flex-1">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                        <span class="material-symbols-outlined text-[20px]">search</span>
                    </span>
                    <input
                        type="search"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Cari konten"
                        class="w-full rounded-[1.25rem] border border-slate-200 bg-white-50/60 py-3 pl-12 pr-4 text-sm text-slate-700 shadow-sm transition placeholder:text-slate-400 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100"
                    >
                </div>

                <div class="relative flex gap-3" @click.away="categoryOpen = false">
                    <button type="submit" class="inline-flex items-center justify-center rounded-full bg-slate-900 px-5 py-3 text-sm font-black text-white shadow-lg shadow-slate-300/40 transition hover:bg-slate-800">
                        Cari 
                    </button>
                    <button type="button" @click="categoryOpen = !categoryOpen" class="inline-flex h-12 w-12 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-700 shadow-sm transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700" aria-label="Kategori">
                        <span class="material-symbols-outlined text-[20px]">category</span>
                    </button>

                    <div x-cloak x-show="categoryOpen" x-transition.origin.top.right class="absolute right-0 top-14 z-20 w-72 rounded-[1.5rem] border border-slate-200 bg-white p-3 shadow-[0px_20px_40px_rgba(25,28,30,0.12)]">
                        <p class="px-3 py-2 text-[11px] font-black uppercase tracking-[0.18em] text-slate-500">Filter Kategori</p>

                        <div class="mt-2 flex flex-wrap gap-2">
                            <a
                                href="{{ route('official.index', array_filter(['search' => $search])) }}"
                                class="{{ $selectedCategory === '' ? 'bg-blue-600 text-white shadow-lg shadow-blue-100' : 'border border-slate-200 bg-white text-slate-700 hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700' }} inline-flex items-center rounded-full px-4 py-2 text-xs font-bold transition"
                            >
                                Semua
                            </a>

                            @foreach ($categories as $category)
                                <a
                                    href="{{ route('official.index', array_filter(['category' => $category, 'search' => $search])) }}"
                                    class="{{ $selectedCategory === $category ? 'bg-blue-600 text-white shadow-lg shadow-blue-100' : 'border border-slate-200 bg-white text-slate-700 hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700' }} inline-flex items-center rounded-full px-4 py-2 text-xs font-bold transition"
                                >
                                    {{ $category }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </form>

        @if ($officialContents->isEmpty())
            <div class="rounded-[2rem] border border-slate-200 bg-white px-6 py-16 text-center shadow-[0px_20px_40px_rgba(25,28,30,0.06)]">
                <div class="mx-auto flex max-w-md flex-col items-center">
                    <div class="flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                        <span class="material-symbols-outlined text-[32px]">collections_bookmark</span>
                    </div>
                    <p class="mt-5 text-xl font-black tracking-tight text-slate-950">Belum ada konten untuk filter ini</p>
                    <p class="mt-2 text-sm leading-relaxed text-slate-500">
                        {{ $selectedCategory !== '' ? 'Kategori yang dipilih belum memiliki konten resmi.' : 'Silakan tambahkan konten visual resmi pertama untuk mulai membangun basis data referensi validasi.' }}
                    </p>
                </div>
            </div>
        @else
            <section class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-[0px_20px_40px_rgba(25,28,30,0.06)]">
                <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-[11px] font-black uppercase tracking-[0.18em] text-slate-500">
                            {{ $selectedCategory !== '' ? 'Filter aktif: '.$selectedCategory : 'Urutan terbaru' }}
                        </p>
                        <h2 class="mt-1 text-2xl font-black tracking-tight text-slate-950">Galeri Konten Resmi</h2>
                    </div>
                    <p class="text-sm font-semibold text-slate-500">{{ $displayedContentCount }} konten ditampilkan</p>
                </div>

                            <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                                @foreach ($officialContents as $content)
                                    <button
                                        type="button"
                                        @click="activeContent = {{ \Illuminate\Support\Js::from([
                                            'title' => $content->title,
                                            'category' => $content->category ?: 'Umum',
                                            'image_url' => asset('storage/'.$content->image_path),
                                            'image_hash' => $content->image_hash,
                                            'extracted_text' => $content->extracted_text,
                                            'source_type_label' => $content->source_type === 'url' ? 'URL resmi' : 'Unggah manual',
                                            'source_url' => $content->source_url,
                                            'created_at_label' => $content->created_at?->format('d M Y, H:i'),
                                            'delete_url' => route('official.destroy', $content),
                                        ]) }}"
                                        class="group relative text-left focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                    >
                                        <div class="rounded-[2rem] border border-slate-200 bg-white p-4 shadow-sm transition duration-300 group-hover:shadow-lg">
                                            <div class="relative h-[25rem] overflow-hidden rounded-[1.7rem] bg-gradient-to-br from-slate-100 via-white to-indigo-50 p-4">
                                                <div class="absolute inset-0 rounded-[1.7rem] border border-white/60"></div>

                                                <div class="absolute inset-x-6 bottom-4 z-10 rounded-[1.5rem] border border-slate-200 bg-slate-900 px-4 py-3 text-white shadow-sm transition duration-300 group-hover:opacity-0 group-hover:scale-95">
                                                    <h3 class="text-base font-bold leading-tight">{{ $content->title }}</h3>
                                                    <p class="mt-1 text-sm text-indigo-300">{{ $content->category ?: 'Umum' }}</p>
                                                    <p class="mt-2 text-[11px] leading-relaxed text-slate-300">
                                                        {{ $content->extracted_text ? \Illuminate\Support\Str::limit($content->extracted_text, 70) : 'OCR belum tersedia' }}
                                                    </p>
                                                </div>

                                <div class="mt-4 space-y-3">
                                    <span class="inline-flex rounded-full bg-blue-50 px-3 py-1 text-[11px] font-black uppercase tracking-[0.18em] text-blue-700">
                                        {{ $content->category ?: 'Umum' }}
                                    </span>
                                    <h3 class="text-lg font-black leading-tight text-slate-950">{{ $content->title }}</h3>
                                    <div class="flex items-center justify-between gap-3 text-xs font-semibold text-slate-500">
                                        <span>{{ $content->source_type === 'url' ? 'URL resmi' : 'Unggah manual' }}</span>
                                        <span class="inline-flex items-center gap-1 text-slate-900">
                                            Lihat detail
                                            <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                                        </span>
                                    </div>
                                </div>
                            </article>
                        </button>
                    @endforeach
                </div>
            </section>
        @endif

        <div x-cloak x-show="activeContent" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/80 px-4 py-6">
            <div x-show="activeContent" x-transition.scale @click.away="activeContent = null" class="w-full max-w-6xl overflow-hidden rounded-[2rem] border border-white/10 bg-white shadow-2xl shadow-slate-950/40">
                <div class="grid max-h-[90vh] overflow-y-auto lg:grid-cols-[minmax(0,1.45fr)_minmax(20rem,1fr)]">
                    <div class="flex items-center justify-center bg-[radial-gradient(circle_at_top,_rgba(59,130,246,0.35),_transparent_45%),linear-gradient(180deg,_#0f172a,_#020617)] p-4 sm:p-6">
                        <img :src="activeContent?.image_url" :alt="activeContent?.title" class="block max-h-[78vh] w-auto max-w-full rounded-[1.5rem] object-contain shadow-2xl shadow-slate-950/30">
                    </div>

                    <div class="flex flex-col justify-between p-6 sm:p-8">
                        <div>
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-[11px] font-black uppercase tracking-[0.18em] text-blue-700" x-text="activeContent?.category"></p>
                                    <h3 class="mt-3 text-2xl font-black leading-tight text-slate-950" x-text="activeContent?.title"></h3>
                                </div>
                                <button type="button" @click="activeContent = null" class="rounded-full border border-slate-200 p-2 text-slate-500 transition hover:bg-slate-50 hover:text-slate-900">
                                    <span class="material-symbols-outlined text-[20px]">close</span>
                                </button>
                            </div>

                            <div class="mt-8 space-y-4">
                                <div class="rounded-[1.5rem] border border-slate-200 bg-slate-50/80 p-4">
                                    <p class="text-xs font-black uppercase tracking-[0.18em] text-slate-500">Metode Sumber</p>
                                    <p class="mt-2 text-sm font-semibold text-slate-900" x-text="activeContent?.source_type_label"></p>
                                </div>
                                <div class="rounded-[1.5rem] border border-slate-200 bg-slate-50/80 p-4">
                                    <p class="text-xs font-black uppercase tracking-[0.18em] text-slate-500">Tanggal Upload</p>
                                    <p class="mt-2 text-sm font-semibold text-slate-900" x-text="activeContent?.created_at_label"></p>
                                </div>
                                <template x-if="activeContent?.source_url">
                                    <div class="rounded-[1.5rem] border border-slate-200 bg-slate-50/80 p-4">
                                        <p class="text-xs font-black uppercase tracking-[0.18em] text-slate-500">Sumber URL</p>
                                        <p class="mt-2 break-all text-sm text-slate-700" x-text="activeContent?.source_url"></p>
                                    </div>
                                </template>
                                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                    <p class="text-xs font-bold uppercase tracking-widest text-slate-500">Hash Gambar</p>
                                    <p class="mt-2 break-all rounded-xl border border-slate-100 bg-white px-3 py-3 font-mono text-sm text-slate-800" x-text="activeContent?.image_hash || '-'"></p>
                                </div>
                                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                    <p class="text-xs font-bold uppercase tracking-widest text-slate-500">Teks OCR</p>
                                    <p class="mt-2 rounded-xl border border-slate-100 bg-white px-3 py-3 text-sm leading-relaxed text-slate-700" x-text="activeContent?.extracted_text || 'Teks OCR belum tersedia untuk konten ini.'"></p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 flex flex-wrap gap-3">
                            <form method="POST" :action="activeContent?.delete_url" onsubmit="return confirm('Apakah Anda yakin ingin menghapus konten resmi ini dari basis data referensi?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-rose-600 px-5 py-3 text-sm font-black text-white shadow-lg shadow-rose-200/70 transition hover:bg-rose-700">
                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                    Hapus konten
                                </button>
                            </form>
                            <button type="button" @click="activeContent = null" class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 px-5 py-3 text-sm font-bold text-slate-700 transition hover:bg-slate-50">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div x-cloak x-show="showUploadResult" x-transition.opacity class="fixed inset-0 z-[60] flex items-center justify-center bg-slate-950/75 px-4 py-6">
            <div x-show="showUploadResult" x-transition.scale @click.away="showUploadResult = false" class="w-full max-w-3xl overflow-hidden rounded-[2rem] border border-white/10 bg-white shadow-2xl shadow-slate-950/40">
                <div class="grid gap-0 md:grid-cols-[minmax(0,1fr)_minmax(20rem,24rem)]">
                    <div class="bg-[radial-gradient(circle_at_top,_rgba(99,102,241,0.30),_transparent_45%),linear-gradient(180deg,_#0f172a,_#111827)] p-5 sm:p-6">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-[0.24em] text-indigo-300">Upload Berhasil</p>
                                <h3 class="mt-2 text-2xl font-black text-white">Hash dan OCR konten resmi selesai diproses</h3>
                            </div>
                            <button type="button" @click="showUploadResult = false" class="rounded-full border border-white/15 p-2 text-slate-200 transition hover:bg-white/10 hover:text-white">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <div class="mt-6 overflow-hidden rounded-3xl border border-white/10 bg-white/5 p-3">
                            <img :src="uploadResult?.image_url" :alt="uploadResult?.title" class="h-64 w-full rounded-[1.4rem] object-cover">
                        </div>
                    </div>

                    <div class="p-6 sm:p-7">
                        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                            Konten akan menampilkan hash dan hasil OCR ini juga di detail konten pada halaman Official Content.
                        </div>

                        <div class="mt-5">
                            <p class="text-xs font-bold uppercase tracking-[0.24em] text-slate-400" x-text="uploadResult?.category"></p>
                            <h4 class="mt-2 text-xl font-black text-slate-950" x-text="uploadResult?.title"></h4>
                        </div>

                        <div class="mt-5 space-y-4">
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <p class="text-xs font-bold uppercase tracking-widest text-slate-500">Hash Gambar</p>
                                <p class="mt-2 break-all rounded-xl border border-slate-100 bg-white px-3 py-3 font-mono text-sm text-slate-800" x-text="uploadResult?.image_hash || '-'"></p>
                            </div>

                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <div class="flex items-center justify-between gap-3">
                                    <p class="text-xs font-bold uppercase tracking-widest text-slate-500">Hasil OCR</p>
                                    <span
                                        class="inline-flex items-center rounded-full px-2.5 py-1 text-[11px] font-bold"
                                        :class="uploadResult?.ocr_detected ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700'"
                                        x-text="uploadResult?.ocr_detected ? 'Teks terdeteksi' : 'Tidak ada teks terdeteksi'"
                                    ></span>
                                </div>
                                <p class="mt-2 rounded-xl border border-slate-100 bg-white px-3 py-3 text-sm leading-relaxed text-slate-700" x-text="uploadResult?.ocr_preview || '-'"></p>
                            </div>
                        </div>

                        <div class="mt-6 flex flex-wrap gap-3">
                            <button
                                type="button"
                                @click="activeContent = uploadResult; showUploadResult = false"
                                class="inline-flex items-center gap-2 rounded-2xl bg-indigo-600 px-5 py-3 text-sm font-extrabold text-white shadow-lg shadow-indigo-100 transition hover:bg-indigo-700"
                            >
                                Lihat di detail konten
                            </button>
                            <button type="button" @click="showUploadResult = false" class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 px-5 py-3 text-sm font-bold text-slate-700 transition hover:bg-slate-50">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-shell>
