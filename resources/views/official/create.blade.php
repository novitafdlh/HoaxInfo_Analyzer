<x-app-layout>
    {{-- Header Halaman Admin yang Modern & Teperaya --}}
    <x-slot name="header">
        <div class="flex items-center gap-4 pb-2 border-b border-slate-100">
            {{-- Ikon Database/Tambah (Opsional, tapi bikin pro) --}}
            <div class="p-2.5 bg-indigo-100 rounded-xl text-indigo-700 shadow-inner border border-indigo-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight leading-tight">
                    {{ __('Tambah Konten Informasi Resmi') }}
                </h2>
                <p class="text-xs font-medium text-slate-500 uppercase tracking-widest mt-0.5">Kelola Basis Data Referensi Validasi</p>
            </div>
        </div>
    </x-slot>

    {{-- Background Gradien Lembut agar Lebih Santai & Nyaman --}}
    <div class="py-10 bg-gradient-to-b from-slate-50 to-white min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid gap-8 lg:grid-cols-12">
                
                {{-- Sidebar Admin (Memakai Kolom Lebih Sedikit agar Form Luas) --}}
                <div class="lg:col-span-3">
                    <div class="sticky top-24 rounded-3xl overflow-hidden border border-slate-200 bg-white shadow-sm">
                        @include('admin.partials.sidebar')
                    </div>
                </div>

                {{-- Area Form Utama (Interaktif & Modern) --}}
                <div class="lg:col-span-9">
                    <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-xl shadow-slate-100/70 transition-all duration-300 hover:shadow-indigo-50">
                        
                        {{-- Deskripsi Halaman --}}
                        <div class="flex items-center gap-4 mb-8 pb-5 border-b border-slate-100 relative">
                            <div class="p-3 bg-indigo-50 rounded-2xl text-indigo-500 shadow-inner">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <p class="text-sm text-slate-600 leading-relaxed flex-1">
                                Gunakan formulir ini untuk menambahkan konten visual resmi (infografis, surat edaran, dll) yang akan menjadi **referensi utama** sistem dalam proses verifikasi informasi publik.
                            </p>
                        </div>

                        {{-- Notifikasi Sukses --}}
                        @if (session('success'))
                            <div class="mb-6 flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 shadow-sm shadow-emerald-100/50">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ session('success') }}
                            </div>
                        @endif

                        {{-- Notifikasi Error --}}
                        @if (session('error'))
                            <div class="mb-6 flex items-center gap-3 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800 shadow-sm shadow-rose-100/50">
                                <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                {{ session('error') }}
                            </div>
                        @endif

                        {{-- Menampilkan Error Validasi Global jika ada --}}
                        @if ($errors->any())
                            <div class="mb-6 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800 shadow-sm shadow-rose-100/50">
                                <ul class="list-disc list-inside space-y-1 font-medium">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- Form Mulai --}}
                        <form action="{{ route('official.store') }}" method="POST" enctype="multipart/form-data" class="space-y-7">
                            @csrf

                            {{-- Input Judul --}}
                            <div class="group">
                                <x-input-label for="title" :value="__('Judul Konten Informasi Resmi')" class="font-semibold text-slate-700 ml-1" />
                                <p class="mt-1 text-xs text-slate-500 ml-1 leading-relaxed">Masukkan judul yang jelas untuk memudahkan identifikasi konten.</p>
                                <x-text-input id="title" name="title" type="text" class="mt-2 block w-full rounded-xl border-slate-200 py-3.5 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-150" :value="old('title')" required autofocus placeholder="Contoh: Infografis Status Gunung Awu April 2026" />
                            </div>

                            {{-- Input Kategori --}}
                            <div class="group">
                                <x-input-label for="category" :value="__('Kategori Konten')" class="font-semibold text-slate-700 ml-1" />
                                <p class="mt-1 text-xs text-slate-500 ml-1 leading-relaxed">Pilih atau ketik kategori (Bencana Alam, Kesehatan, Politik, dll).</p>
                                <x-text-input id="category" name="category" type="text" class="mt-2 block w-full rounded-xl border-slate-200 py-3.5 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-150" :value="old('category')" required placeholder="Contoh: Bencana Alam" />
                            </div>

                            {{-- Separator untuk Bagian Gambar --}}
                            <div class="relative py-3">
                                <div class="absolute inset-0 flex items-center"><span class="w-full border-t border-slate-100"></span></div>
                                <div class="relative flex justify-center text-xs uppercase"><span class="bg-white px-3 text-slate-400 font-bold tracking-widest">Pilih Salah Satu Metode Unggah</span></div>
                            </div>

                            {{-- Area File Upload yang Interaktif (Dropzone Style) --}}
                            <div class="group relative">
                                <x-input-label for="image" :value="__('Unggah Gambar Resmi (Maks. 100MB)')" class="font-semibold text-slate-700 ml-1 mb-2" />
                                <label for="image" class="flex flex-col items-center justify-center w-full h-56 border-2 border-dashed border-slate-300 rounded-3xl cursor-pointer bg-slate-50 hover:bg-indigo-50 hover:border-indigo-400 transition duration-150">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6 text-center px-4">
                                        <div class="mb-4 p-3.5 bg-white rounded-full shadow-inner border border-slate-100 text-slate-400 group-hover:text-indigo-600 group-hover:scale-110 transition duration-300">
                                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                        </div>
                                        <p class="mb-2 text-sm text-slate-700 font-semibold group-hover:text-indigo-800 transition">Klik untuk unggah file gambar resmi</p>
                                        <p class="text-xs text-slate-500 italic">atau seret dan lepas file dari perangkat Anda</p>
                                    </div>
                                    {{-- Menampilkan Nama File yang Dipilih --}}
                                    <p id="file-name-display" class="absolute bottom-4 text-xs text-indigo-700 font-medium whitespace-nowrap overflow-hidden text-ellipsis max-w-[90%]"></p>
                                </label>
                                <input id="image" name="image" type="file" accept="image/*" class="hidden" onchange="document.getElementById('file-name-display').innerText = this.files[0].name" />
                            </div>

                            {{-- Separator "Atau via URL" --}}
                            <div class="relative py-3">
                                <div class="absolute inset-0 flex items-center"><span class="w-full border-t border-slate-100"></span></div>
                                <div class="relative flex justify-center text-xs uppercase"><span class="bg-white px-3 text-slate-400 font-bold tracking-widest">Atau Gunakan Tautan</span></div>
                            </div>

                            {{-- Form URL Gambar (Pro & Interaktif) --}}
                            <div>
                                <x-input-label for="image_url" :value="__('Atau Masukkan URL Gambar Resmi')" class="font-semibold text-slate-700 ml-1" />
                                <p class="mt-1 text-xs text-slate-500 ml-1 leading-relaxed">Masukkan tautan gambar langsung dari website pemerintah atau akun instansi resmi.</p>
                                <x-text-input id="image_url" name="image_url" type="url" :value="old('image_url')" placeholder="https://kominfo.go.id/gambar-resmi.jpg" class="mt-2 block w-full rounded-xl border-slate-200 py-3.5 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-150" />
                            </div>

                            {{-- Tombol Action (Lebih Besar & Menonjol) --}}
                            <div class="pt-6 flex justify-end border-t border-slate-100">
                                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center rounded-2xl bg-indigo-600 px-8 py-4 text-sm font-extrabold text-white shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition active:scale-[0.98]">
                                    <svg class="w-5 h-5 mr-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                    Simpan sebagai Basis Data Resmi
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>