<x-app-layout>
    {{-- Header Halaman: Nuansa Biru Lembut --}}
    <x-slot name="header">
        <div class="flex items-center gap-4 pb-3 border-b border-blue-100">
            {{-- Ikon Identitas Dashboard: Latar Biru muda --}}
            <div class="p-2.5 bg-blue-50 rounded-xl text-blue-600 shadow-inner border border-blue-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
            </div>
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight leading-tight">
                    {{ __('Dashboard Pengguna') }}
                </h2>
                <p class="text-xs font-medium text-blue-700 uppercase tracking-widest mt-0.5">Pusat Validasi Informasi Visual Anda</p>
            </div>
        </div>
    </x-slot>

    {{-- Background Utama: Gradien Biru Sangat Muda ke Putih --}}
    <div class="py-10 bg-gradient-to-b from-blue-50 via-white to-white min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid gap-8 lg:grid-cols-12">
                
                {{-- Kotak Kiri: Panduan & Status --}}
                <aside class="lg:col-span-4 space-y-6">
                    {{-- Card dengan Border Biru Tipis dan Shadow Biru Halus --}}
                    <div class="rounded-3xl border border-blue-100 bg-white p-8 shadow-lg shadow-blue-50/50 transition-all duration-300 hover:shadow-blue-100/50 hover:-translate-y-1">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="p-2.5 bg-blue-50 rounded-xl text-blue-600 shadow-inner border border-blue-100">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <h3 class="text-xl font-bold text-slate-950 tracking-tight">Panduan Cepat</h3>
                        </div>
                        
                        <p class="text-sm text-slate-600 leading-relaxed mb-6">
                            Unggah gambar atau tempelkan URL untuk mengecek kebenaran informasi berdasarkan basis data konten resmi.
                        </p>
                        
                        <ul class="space-y-4 text-xs text-slate-600">
                            <li class="flex items-start gap-3">
                                {{-- Badge Angka Biru --}}
                                <span class="flex h-5 w-5 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 text-blue-700 font-bold shadow-inner border border-blue-200">1</span>
                                <div class="flex-1"><b class="text-slate-700">100% Cocok:</b> Tervalidasi otomatis oleh sistem.</div>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="flex h-5 w-5 flex-shrink-0 items-center justify-center rounded-full bg-amber-50 text-amber-700 font-bold shadow-inner border border-amber-100">2</span>
                                <div class="flex-1"><b class="text-slate-700">50% - 99% Mirip:</b> Wajib menunggu review manual admin.</div>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="flex h-5 w-5 flex-shrink-0 items-center justify-center rounded-full bg-rose-50 text-rose-700 font-bold shadow-inner border border-rose-100">3</span>
                                <div class="flex-1"><b class="text-slate-700">< 50% Match:</b> Tidak tervalidasi (Potensi Hoax).</div>
                            </li>
                        </ul>

                        {{-- Status Akun: Nuansa Hijau tetap dipertahankan karena melambangkan 'Aktif/Sukses' --}}
                        <div class="mt-10 pt-6 border-t border-blue-100">
                            <div class="flex items-center gap-3.5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800 shadow-inner shadow-emerald-100/50">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                Akun login aktif: Upload tanpa batas.
                            </div>
                        </div>
                    </div>
                </aside>

                {{-- Kotak Kanan: Form Upload --}}
                <main class="lg:col-span-8">
                    {{-- Card dengan Shadow Biru Lembut --}}
                    <div class="rounded-3xl border border-blue-100 bg-white p-8 shadow-xl shadow-blue-100/30 transition-all duration-300 hover:shadow-blue-100/50">
                        <div class="flex items-center justify-between mb-8 pb-4 border-b border-blue-100">
                            <h2 class="text-xl font-bold text-slate-900 tracking-tight">Mulai Validasi Gambar</h2>
                            <p class="text-xs text-blue-400 font-mono tracking-tighter">Maks. File: 100MB</p>
                        </div>

                        {{-- Notifikasi Sukses: Tetap Hijau --}}
                        @if (session('status'))
                            <div class="mb-6 flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 shadow-sm shadow-emerald-100/50">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ session('status') }}
                            </div>
                        @endif

                        {{-- Menampilkan Error Global: Tetap Merah --}}
                        @if ($errors->any())
                            <div class="mb-6 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800 shadow-sm shadow-rose-100/50">
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('dashboard.upload') }}" enctype="multipart/form-data" class="space-y-6">
                            @csrf

                            {{-- Area File Upload: Perubahan warna ke Biru saat Hover --}}
                            <div class="group relative">
                                <label for="image_file" class="flex flex-col items-center justify-center w-full h-60 border-2 border-dashed border-blue-200 rounded-3xl cursor-pointer bg-blue-50/50 hover:bg-blue-50 hover:border-blue-400 transition duration-150 shadow-inner">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6 text-center px-4">
                                        {{-- Ikon Box warna Biru --}}
                                        <div class="mb-4 p-3.5 bg-white rounded-full shadow border border-blue-100 text-blue-400 group-hover:text-blue-600 group-hover:scale-110 transition duration-300">
                                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                        </div>
                                        <p class="mb-2 text-sm text-slate-700 font-semibold group-hover:text-blue-900 transition">Klik untuk unggah gambar</p>
                                        <p class="text-xs text-slate-500 italic">atau seret dan lepas file di sini</p>
                                    </div>
                                    <p id="file-name-display" class="absolute bottom-4 text-xs text-blue-700 font-medium whitespace-nowrap overflow-hidden text-ellipsis max-w-[90%]"></p>
                                </label>
                                <input id="image_file" name="image_file" type="file" accept="image/*" class="hidden" onchange="document.getElementById('file-name-display').innerText = this.files[0].name" />
                                <x-input-error :messages="$errors->get('image_file')" class="mt-2" />
                            </div>

                            {{-- Separator "Atau via URL": Garis Biru Tipis --}}
                            <div class="relative py-4">
                                <div class="absolute inset-0 flex items-center"><span class="w-full border-t border-blue-100"></span></div>
                                <div class="relative flex justify-center text-xs uppercase"><span class="bg-white px-3 text-blue-500 font-bold tracking-widest">Atau via URL</span></div>
                            </div>

                            {{-- Form URL Gambar: Input dengan Focus Biru --}}
                            <div>
                                <x-input-label for="image_url" :value="__('Tempel URL Gambar di Sini')" class="font-semibold text-slate-700 ml-1" />
                                {{-- x-text-input perlu dipastikan di komponennya mendukung override class border-slate-200, jika tidak, ganti dengan input html biasa --}}
                                <x-text-input id="image_url" name="image_url" type="url" :value="old('image_url')" placeholder="https://domain.com/gambar-infografis.jpg" class="mt-1.5 block w-full rounded-xl border-blue-200 bg-white py-3.5 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition duration-150" />
                                <x-input-error :messages="$errors->get('image_url')" class="mt-2 ml-1" />
                            </div>

                            {{-- Tombol Action: Menjadi Biru (bg-blue-600) --}}
                            <div class="pt-5 flex justify-end">
                                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center rounded-2xl bg-blue-600 px-8 py-4 text-sm font-extrabold text-white shadow-lg shadow-blue-200 hover:bg-blue-700 transition active:scale-[0.98]">
                                    <svg class="w-5 h-5 mr-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Mulai Validasi Sekarang
                                </button>
                            </div>
                        </form>
                    </div>
                </main>
            </div>
        </div>
    </div>
</x-app-layout>