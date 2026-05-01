@php
    $displayedContentCount = $officialContents->count();
    $totalContentCount = method_exists($officialContents, 'total') ? $officialContents->total() : $displayedContentCount;
@endphp

<x-admin-shell title="Konten Resmi Admin">
    <x-slot name="pageHeader">
        <section class="space-y-4">
            <div class="flex flex-col gap-4 xl:flex-row xl:items-end xl:justify-between">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight text-on-surface">Konten Resmi</h1>
                </div>

                <a href="{{ route('official.create') }}" class="inline-flex items-center justify-center gap-2 rounded-full bg-slate-900 px-6 py-3.5 text-sm font-black text-white shadow-lg shadow-slate-300/50 transition hover:bg-slate-800">
                    <span class="material-symbols-outlined text-[20px]">add_circle</span>
                    Tambah Konten Resmi
                </a>
            </div>
        </section>
    </x-slot>

    <div x-data="{ activeContent: null, categoryOpen: false }" @keydown.escape.window="activeContent = null; categoryOpen = false" class="space-y-6">
        @if (session('success'))
            <div class="flex items-center gap-3 rounded-[1.5rem] border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-800 shadow-sm">
                <span class="material-symbols-outlined text-[22px] text-emerald-600">check_circle</span>
                {{ session('success') }}
            </div>
        @endif

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
                        <h2 class="mt-1 text-xl font-black tracking-tight text-slate-950">Galeri Konten Resmi</h2>
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
                                'image_url' => $content->image_url,
                                'source_type_label' => $content->source_type === 'url' ? 'URL resmi' : 'Unggah manual',
                                'source_url' => $content->source_url,
                                'created_at_label' => $content->created_at?->format('d M Y, H:i'),
                                'delete_url' => route('official.destroy', $content),
                            ]) }}"
                            class="group text-left focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                        >
                            <article class="h-full rounded-[2rem] border border-slate-200 bg-[#f8fbff] p-4 shadow-[0px_14px_30px_rgba(25,28,30,0.05)] transition duration-300 hover:-translate-y-1 hover:shadow-[0px_24px_44px_rgba(25,28,30,0.08)]">
                                <div class="aspect-[16/10] overflow-hidden rounded-[1.5rem] border border-white bg-white shadow-sm">
                                    <img src="{{ $content->image_url }}" alt="{{ $content->title }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-[1.03]">
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
                                <button type="button" @click="activeContent = null" class="rounded-full border border-slate-200 p-2 text-slate-500 transition hover:border-rose-200 hover:bg-rose-50 hover:text-rose-700">
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
                            <button type="button" @click="activeContent = null" class="inline-flex items-center gap-2 rounded-full border border-slate-200 px-5 py-3 text-sm font-bold text-slate-700 transition hover:bg-slate-50">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-shell>
