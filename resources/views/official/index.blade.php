<x-app-layout>
    {{-- Header Halaman Admin yang Tepercaya & Modern --}}
    <x-slot name="header">
        <div class="flex items-center justify-between gap-6 pb-2 border-b border-slate-100">
            <div class="flex items-center gap-4">
                {{-- Ikon Database/Konten (Opsional) --}}
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
            
            {{-- Tombol Tambah yang Lebih Besar & Menonjol --}}
            <a href="{{ route('official.create') }}" class="inline-flex items-center gap-2.5 rounded-2xl bg-indigo-600 px-6 py-3.5 text-sm font-extrabold text-white shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition active:scale-[0.98]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                Tambah Konten Resmi
            </a>
        </div>
    </x-slot>

    {{-- Background Gradien Lembut agar Lebih Santai & Nyaman --}}
    <div class="py-10 bg-gradient-to-b from-slate-50 to-white min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid gap-8 lg:grid-cols-12">
                
                {{-- Sidebar Admin (Memakai Kolom Lebih Sedikit agar Tabel Luas) --}}
                <div class="lg:col-span-3">
                    <div class="sticky top-24 rounded-3xl overflow-hidden border border-slate-200 bg-white shadow-sm hover:shadow-md transition-shadow">
                        @include('admin.partials.sidebar')
                    </div>
                </div>

                {{-- Area Utama Tabel Konten (Interaktif & Modern) --}}
                <div class="space-y-8 lg:col-span-9">
                    
                    {{-- Notifikasi Sukses --}}
                    @if (session('success'))
                        <div class="flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800 shadow-sm shadow-emerald-100/50">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Kotak Deskripsi Halaman --}}
                    <div class="rounded-3xl border border-slate-200 bg-white p-7 shadow-xl shadow-slate-100/70 transition-all duration-300 hover:shadow-indigo-50">
                        <p class="text-sm text-slate-600 leading-relaxed flex items-center gap-3">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Seluruh konten resmi ditampilkan berkelompok berdasarkan kategori untuk memudahkan pengelolaan basis data referensi.
                        </p>
                    </div>

                    {{-- Iterasi Kategori Konten --}}
                    @forelse ($officialContentsByCategory as $category => $contents)
                        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-xl shadow-slate-100/70 transition-all duration-300 hover:shadow-indigo-50">
                            
                            {{-- Header Kategori --}}
                            <div class="border-b border-slate-100 bg-slate-50/50 px-6 py-4 flex items-center gap-3">
                                <span class="p-1.5 rounded-lg bg-indigo-100 text-indigo-700 shadow-inner">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10M7 11h10M7 15h10"></path></svg>
                                </span>
                                <p class="text-base font-bold text-slate-900 tracking-tight capitalize">
                                    Kategori: {{ $category }}
                                </p>
                            </div>

                            {{-- Tabel Konten (Modern & Interaktif) --}}
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-slate-100">
                                    {{-- Table Header --}}
                                    <thead class="bg-white">
                                        <tr>
                                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Gambar</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Judul</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-widest text-slate-500 whitespace-nowrap">Metode Sumber</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-widest text-slate-500 whitespace-nowrap">Tanggal Upload</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-widest text-slate-500">Aksi</th>
                                        </tr>
                                    </thead>
                                    
                                    {{-- Table Body --}}
                                    <tbody class="divide-y divide-slate-100 bg-white">
                                        @foreach ($contents as $content)
                                            <tr class="hover:bg-slate-50/50 transition duration-150 group">
                                                {{-- Kolom Gambar --}}
                                                <td class="px-6 py-4">
                                                    <div class="flex-shrink-0">
                                                        <img src="{{ asset('storage/'.$content->image_path) }}" alt="Official Content" class="h-16 w-24 rounded-xl border border-slate-200 object-cover shadow-sm group-hover:scale-105 transition-transform duration-300">
                                                    </div>
                                                </td>
                                                {{-- Kolom Judul --}}
                                                <td class="px-6 py-4 text-sm font-medium text-slate-800 leading-relaxed">
                                                    {{ $content->title }}
                                                </td>
                                                {{-- Kolom Sumber --}}
                                                <td class="px-6 py-4 text-sm text-slate-700">
                                                    @if($content->source_type === 'url')
                                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700 shadow-sm shadow-blue-100/50">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.812a4 4 0 005.656 0l4-4a4 4 0 10-5.656-5.656l-1.1 1.1"></path></svg>
                                                            Tautan URL
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700 shadow-sm shadow-slate-100/50">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                                            Unggah Manual
                                                        </span>
                                                    @endif
                                                </td>
                                                {{-- Kolom Tanggal --}}
                                                <td class="px-6 py-4 text-sm text-slate-700 whitespace-nowrap">
                                                    {{ $content->created_at?->format('d M Y, H:i') }}
                                                </td>
                                                {{-- Kolom Aksi --}}
                                                <td class="px-6 py-4 text-sm">
                                                    <form method="POST" action="{{ route('official.destroy', $content) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus konten resmi ini dari basis data referensi?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="inline-flex items-center gap-1.5 rounded-xl bg-rose-100 px-4 py-2 text-xs font-bold text-rose-800 shadow-sm shadow-rose-100/50 hover:bg-rose-600 hover:text-white hover:shadow-rose-100 transition-allduration-200">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                            Hapus
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @empty
                        {{-- Tampilan State Kosong yang Ramah --}}
                        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-xl shadow-slate-100/70 transition-all duration-300 hover:shadow-indigo-50">
                            <div class="px-6 py-12 text-center">
                                <svg class="w-16 h-16 mx-auto text-slate-300 mb-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                <p class="text-lg font-bold text-slate-900 tracking-tight">Belum Ada Konten Resmi</p>
                                <p class="text-sm text-slate-500 mt-1.5 max-w-sm mx-auto">
                                    Silakan tambahkan konten visual resmi pertama Anda untuk mulai membangun basis data referensi validasi.
                                </p>
                                <a href="{{ route('official.create') }}" class="mt-8 inline-flex items-center gap-2.5 rounded-2xl bg-indigo-600 px-6 py-3.5 text-sm font-extrabold text-white shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition active:scale-[0.98]">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                                    Tambah Konten Resmi
                                </a>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>