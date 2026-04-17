<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-6 pb-2 border-b border-slate-100">
            <div class="flex items-center gap-4">
                <div class="p-2.5 bg-indigo-100 rounded-xl text-indigo-700 shadow-inner border border-indigo-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight leading-tight">
                        {{ __('Basis Data Konten Resmi') }}
                    </h2>
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-widest mt-0.5">Referensi Utama Validasi Informasi</p>
                </div>
            </div>

            <a href="{{ route('official.create') }}" class="inline-flex items-center gap-2.5 rounded-2xl bg-indigo-600 px-6 py-3.5 text-sm font-extrabold text-white shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition active:scale-[0.98]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                Tambah Konten Resmi
            </a>
        </div>
    </x-slot>

    <div class="py-10 bg-gradient-to-b from-slate-50 to-white min-h-screen" x-data="{ activeContent: null, filterOpen: false }" @keydown.escape.window="activeContent = null; filterOpen = false">
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
                            @if ($selectedCategory !== '')
                                <input type="hidden" name="category" value="{{ $selectedCategory }}">
                            @endif
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
                                    placeholder="Cari judul atau kategori..."
                                    class="w-full rounded-2xl border border-slate-200 bg-white py-3 pl-11 pr-4 text-sm text-slate-700 shadow-sm transition placeholder:text-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100"
                                >
                            </div>
                            <div class="flex gap-3">
                                <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-indigo-600 px-5 py-3 text-sm font-extrabold text-white shadow-lg shadow-indigo-100 transition hover:bg-indigo-700">
                                    Cari
                                </button>
                                @if ($search !== '' || $selectedCategory !== '')
                                    <a href="{{ route('official.index') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 px-5 py-3 text-sm font-bold text-slate-700 transition hover:bg-slate-50">
                                        Reset
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>

                    @if ($officialContents->isEmpty())
                        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-xl shadow-slate-100/70">
                            <div class="px-6 py-12 text-center">
                                <svg class="w-16 h-16 mx-auto text-slate-300 mb-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                <p class="text-lg font-bold text-slate-900 tracking-tight">Belum Ada Konten Untuk Filter Ini</p>
                                <p class="text-sm text-slate-500 mt-1.5 max-w-sm mx-auto">
                                    {{ $selectedCategory !== '' ? 'Kategori yang dipilih belum memiliki konten resmi.' : 'Silakan tambahkan konten visual resmi pertama Anda untuk mulai membangun basis data referensi validasi.' }}
                                </p>
                            </div>
                        </div>
                    @else
                        <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-xl shadow-slate-100/70">
                            <div class="mb-4 flex items-center justify-between gap-4">
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-[0.24em] text-indigo-600">{{ $selectedCategory !== '' ? 'Filter aktif: '.$selectedCategory : 'Urutan terbaru' }}</p>
                                    <h3 class="mt-2 text-xl font-black text-slate-950">Galeri Konten Resmi</h3>
                                </div>
                                <p class="text-sm text-slate-500">{{ $officialContents->count() }} konten ditampilkan</p>
                            </div>

                            <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                                @foreach ($officialContents as $content)
                                    <button
                                        type="button"
                                        @click="activeContent = {{ \Illuminate\Support\Js::from([
                                            'title' => $content->title,
                                            'category' => $content->category ?: 'Umum',
                                            'image_url' => asset('storage/'.$content->image_path),
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
                                                </div>

                                                <div class="absolute inset-x-6 top-5 bottom-20 z-20 flex items-center justify-center transition duration-500 [transform:perspective(1200px)_translateZ(0)_scale(1)] group-hover:[transform:perspective(1200px)_translateZ(90px)_scale(1.08)]">
                                                    <div class="w-full max-w-[15rem] rounded-[1.6rem] border border-slate-200 bg-slate-900 p-3 shadow-xl transition duration-500 group-hover:shadow-[0_32px_60px_rgba(79,70,229,0.28)]">
                                                        <img src="{{ asset('storage/'.$content->image_path) }}" alt="{{ $content->title }}" class="h-44 w-full rounded-[1.1rem] object-cover">
                                                        <div class="px-1 pb-1 pt-3 text-white">
                                                            <h3 class="text-lg font-bold leading-tight">{{ $content->title }}</h3>
                                                            <p class="mt-1 text-sm text-indigo-300">{{ $content->category ?: 'Umum' }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </button>
                                @endforeach
                            </div>
                        </section>
                    @endif
                </div>
            </div>
        </div>

        <div x-cloak x-show="activeContent" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/80 px-4 py-6">
            <div x-show="activeContent" x-transition.scale @click.away="activeContent = null" class="w-full max-w-6xl overflow-hidden rounded-[2rem] border border-white/10 bg-white shadow-2xl shadow-slate-950/40">
                <div class="grid max-h-[90vh] overflow-y-auto lg:grid-cols-[minmax(0,1.5fr)_minmax(20rem,1fr)]">
                    <div class="flex items-center justify-center bg-[radial-gradient(circle_at_top,_rgba(99,102,241,0.35),_transparent_45%),linear-gradient(180deg,_#0f172a,_#020617)] p-4 sm:p-6">
                        <img :src="activeContent?.image_url" :alt="activeContent?.title" class="block max-h-[78vh] w-auto max-w-full rounded-2xl object-contain shadow-2xl shadow-slate-950/30">
                    </div>

                    <div class="flex flex-col justify-between p-6 sm:p-8">
                        <div>
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-[0.24em] text-indigo-600" x-text="activeContent?.category"></p>
                                    <h3 class="mt-3 text-2xl font-black leading-tight text-slate-950" x-text="activeContent?.title"></h3>
                                </div>
                                <button type="button" @click="activeContent = null" class="rounded-full border border-slate-200 p-2 text-slate-500 transition hover:bg-slate-50 hover:text-slate-900">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>

                            <div class="mt-8 space-y-4">
                                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                    <p class="text-xs font-bold uppercase tracking-widest text-slate-500">Metode Sumber</p>
                                    <p class="mt-2 text-sm font-semibold text-slate-900" x-text="activeContent?.source_type_label"></p>
                                </div>
                                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                    <p class="text-xs font-bold uppercase tracking-widest text-slate-500">Tanggal Upload</p>
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
                            <form method="POST" :action="activeContent?.delete_url" onsubmit="return confirm('Apakah Anda yakin ingin menghapus konten resmi ini dari basis data referensi?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center gap-2 rounded-2xl bg-rose-600 px-5 py-3 text-sm font-extrabold text-white shadow-lg shadow-rose-100 transition hover:bg-rose-700">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
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
    </div>
</x-app-layout>
