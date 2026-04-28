<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4 pb-2 border-b border-cyan-100">
            <div class="p-2.5 bg-cyan-50 rounded-xl text-cyan-700 shadow-inner border border-cyan-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight leading-tight">
                    {{ $officialContent->title }}
                </h2>
                <p class="text-xs font-medium text-cyan-700 uppercase tracking-widest mt-0.5">Detail konten resmi referensi</p>
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-gradient-to-b from-cyan-50 via-white to-white min-h-screen">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-xl shadow-slate-100/70">
                <div class="grid gap-8 lg:grid-cols-2">
                    <div class="overflow-hidden rounded-3xl border border-slate-200 shadow-sm">
                        <img src="{{ asset('storage/'.$officialContent->image_path) }}" alt="{{ $officialContent->title }}" class="w-full object-cover">
                    </div>

                    <div class="space-y-6">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.24em] text-cyan-700">{{ $officialContent->category ?: 'Umum' }}</p>
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
                            <p class="text-xs font-bold uppercase tracking-widest text-slate-500">Hash gambar</p>
                            <p class="mt-3 break-all rounded-xl border border-slate-100 bg-white px-4 py-3 font-mono text-sm text-slate-800">
                                {{ $officialContent->image_hash ?: '-' }}
                            </p>
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
                                <a href="{{ $officialContent->source_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 rounded-2xl bg-cyan-600 px-5 py-3 text-sm font-extrabold text-white shadow-lg shadow-cyan-100 hover:bg-cyan-700 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.812a4 4 0 005.656 0l4-4a4 4 0 10-5.656-5.656l-1.1 1.1"></path></svg>
                                    Buka URL Resmi
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
