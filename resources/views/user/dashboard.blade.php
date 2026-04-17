<x-app-layout>
    @php
        $validationPopup = session('validation_popup');
    @endphp

    <x-slot name="header">
        <div class="flex items-center gap-4 pb-3 border-b border-blue-100">
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

    <div class="py-10 bg-gradient-to-b from-blue-50 via-white to-white min-h-screen" x-data="{ showValidationPopup: {{ $validationPopup ? 'true' : 'false' }} }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid gap-8 lg:grid-cols-12">
                <aside class="lg:col-span-4 space-y-6">
                    <div class="rounded-3xl border border-blue-100 bg-white p-8 shadow-lg shadow-blue-50/50 transition-all duration-300 hover:shadow-blue-100/50 hover:-translate-y-1">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="p-2.5 bg-blue-50 rounded-xl text-blue-600 shadow-inner border border-blue-100">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <h3 class="text-xl font-bold text-slate-950 tracking-tight">Panduan Cepat</h3>
                        </div>

                        <p class="text-sm text-slate-600 leading-relaxed mb-6">
                            Unggah gambar atau tempelkan URL untuk mengukur tingkat kemiripan dan confidence system terhadap konten resmi yang tersimpan.
                        </p>

                        <ul class="space-y-4 text-xs text-slate-600">
                            <li class="flex items-start gap-3">
                                <span class="flex h-5 w-5 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 text-blue-700 font-bold shadow-inner border border-blue-200">1</span>
                                <div class="flex-1"><b class="text-slate-700">Hash SHA256 identik:</b> Sistem mendeteksi kecocokan digital yang kuat dan menandai konten sebagai terverifikasi otomatis.</div>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="flex h-5 w-5 flex-shrink-0 items-center justify-center rounded-full bg-amber-50 text-amber-700 font-bold shadow-inner border border-amber-100">2</span>
                                <div class="flex-1"><b class="text-slate-700">Similarity 60% - 84%:</b> Konten mirip, tetapi wajib menunggu validasi manual admin.</div>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="flex h-5 w-5 flex-shrink-0 items-center justify-center rounded-full bg-rose-50 text-rose-700 font-bold shadow-inner border border-rose-100">3</span>
                                <div class="flex-1"><b class="text-slate-700">Similarity rendah:</b> Sistem memberi peringatan untuk diwaspadai dan ditinjau lebih lanjut, bukan keputusan final otomatis.</div>
                            </li>
                        </ul>

                        <div class="mt-10 pt-6 border-t border-blue-100">
                            <div class="flex items-center gap-3.5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800 shadow-inner shadow-emerald-100/50">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                Akun login aktif: Upload tanpa batas.
                            </div>
                        </div>
                    </div>

                    <div class="rounded-3xl border border-cyan-100 bg-white p-8 shadow-lg shadow-cyan-50/60">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-2.5 bg-cyan-50 rounded-xl text-cyan-600 shadow-inner border border-cyan-100">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            </div>
                            <h3 class="text-xl font-bold text-slate-950 tracking-tight">Konten Resmi</h3>
                        </div>
                        <p class="text-sm text-slate-600 leading-relaxed">
                            Jelajahi basis data konten resmi untuk memahami referensi yang dipakai sistem saat menghitung kemiripan.
                        </p>
                        <a href="{{ route('user.official.index') }}" class="mt-6 inline-flex items-center gap-2 rounded-2xl bg-cyan-600 px-5 py-3 text-sm font-extrabold text-white shadow-lg shadow-cyan-100 hover:bg-cyan-700 transition active:scale-[0.98]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Lihat Konten Resmi
                        </a>
                    </div>
                </aside>

                <main class="lg:col-span-8">
                    <div class="rounded-3xl border border-blue-100 bg-white p-8 shadow-xl shadow-blue-100/30 transition-all duration-300 hover:shadow-blue-100/50">
                        <div class="flex items-center justify-between mb-8 pb-4 border-b border-blue-100">
                            <h2 class="text-xl font-bold text-slate-900 tracking-tight">Mulai Validasi Gambar</h2>
                            <p class="text-xs text-blue-400 font-mono tracking-tighter">Maks. File: 100MB</p>
                        </div>

                        @if (session('status'))
                            <div class="mb-6 flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 shadow-sm shadow-emerald-100/50">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ session('status') }}
                            </div>
                        @endif

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

                            <div class="group relative">
                                <label for="image_file" class="flex flex-col items-center justify-center w-full h-60 border-2 border-dashed border-blue-200 rounded-3xl cursor-pointer bg-blue-50/50 hover:bg-blue-50 hover:border-blue-400 transition duration-150 shadow-inner">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6 text-center px-4">
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

                            <div class="relative py-4">
                                <div class="absolute inset-0 flex items-center"><span class="w-full border-t border-blue-100"></span></div>
                                <div class="relative flex justify-center text-xs uppercase"><span class="bg-white px-3 text-blue-500 font-bold tracking-widest">Atau via URL</span></div>
                            </div>

                            <div>
                                <x-input-label for="image_url" :value="__('Tempel URL Gambar di Sini')" class="font-semibold text-slate-700 ml-1" />
                                <x-text-input id="image_url" name="image_url" type="url" :value="old('image_url')" placeholder="https://domain.com/gambar-infografis.jpg" class="mt-1.5 block w-full rounded-xl border-blue-200 bg-white py-3.5 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition duration-150" />
                                <x-input-error :messages="$errors->get('image_url')" class="mt-2 ml-1" />
                            </div>

                            <div class="pt-5 flex justify-end">
                                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center rounded-2xl bg-blue-600 px-8 py-4 text-sm font-extrabold text-white shadow-lg shadow-blue-200 hover:bg-blue-700 transition active:scale-[0.98]">
                                    <svg class="w-5 h-5 mr-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Mulai Analisis Sekarang
                                </button>
                            </div>
                        </form>
                    </div>
                </main>
            </div>
        </div>

        @if ($validationPopup)
            <div
                x-cloak
                x-show="showValidationPopup"
                x-transition.opacity
                class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/55 px-4 py-6"
            >
                <div
                    @click.away="showValidationPopup = false"
                    class="w-full max-w-2xl rounded-[2rem] border border-slate-200 bg-white p-8 shadow-2xl shadow-slate-900/20"
                >
                    <div class="flex items-start justify-between gap-6">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.24em] text-blue-600">Hasil Validasi</p>
                            <h3 class="mt-2 text-2xl font-black tracking-tight text-slate-950">Konten selesai dianalisis</h3>
                            <p class="mt-2 text-sm leading-relaxed text-slate-600">
                                Sistem menghitung tingkat kemiripan terhadap basis data resmi dan menyiapkan tautan ke konten resmi yang paling mirip.
                            </p>
                        </div>
                        <button type="button" @click="showValidationPopup = false" class="rounded-full border border-slate-200 p-2 text-slate-500 hover:bg-slate-50 hover:text-slate-800 transition">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <div class="mt-8 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                        <div class="rounded-2xl border border-emerald-100 bg-emerald-50 p-5">
                            <p class="text-xs font-bold uppercase tracking-widest text-emerald-700">Kemiripan</p>
                            <p class="mt-2 text-3xl font-black text-emerald-900">{{ number_format((float) $validationPopup['similarity_score'], 2) }}%</p>
                        </div>
                        <div class="rounded-2xl border border-amber-100 bg-amber-50 p-5">
                            <p class="text-xs font-bold uppercase tracking-widest text-amber-700">Confidence</p>
                            <p class="mt-2 text-xl font-black text-amber-900">{{ $validationPopup['confidence_label'] }}</p>
                        </div>
                        <div class="rounded-2xl border border-blue-100 bg-blue-50 p-5">
                            <p class="text-xs font-bold uppercase tracking-widest text-blue-700">Status Akhir</p>
                            <p class="mt-2 text-base font-black leading-tight text-blue-900">{{ $validationPopup['final_status_label'] }}</p>
                        </div>
                        <div class="rounded-2xl border border-blue-100 bg-blue-50 p-5">
                            <p class="text-xs font-bold uppercase tracking-widest text-blue-700">Metode</p>
                            <p class="mt-2 text-base font-black leading-tight text-blue-900">{{ $validationPopup['analysis_method_label'] }}</p>
                        </div>
                    </div>

                    <div class="mt-6 rounded-2xl border border-slate-200 bg-slate-50 p-5">
                        <p class="text-xs font-bold uppercase tracking-widest text-slate-500">Rekomendasi Sistem</p>
                        <p class="mt-2 text-lg font-bold text-slate-900">{{ $validationPopup['system_status_label'] }}</p>

                        @if (!empty($validationPopup['official_title']))
                            <div class="mt-4 rounded-xl border border-slate-200 bg-white p-4">
                                <p class="text-xs font-bold uppercase tracking-widest text-slate-500">Konten resmi paling mirip</p>
                                <p class="mt-2 text-base font-bold text-slate-900">{{ $validationPopup['official_title'] }}</p>
                                @if (!empty($validationPopup['official_url']))
                                    <div class="mt-4 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                                        <p class="text-xs font-bold uppercase tracking-widest text-slate-500">URL Konten Resmi</p>
                                        <p class="mt-2 break-all text-sm text-slate-700">{{ $validationPopup['official_url'] }}</p>
                                    </div>
                                    <div class="mt-4 flex flex-wrap gap-3">
                                        <a
                                            href="{{ $validationPopup['official_url'] }}"
                                            @if (str_starts_with($validationPopup['official_url'], 'http')) target="_blank" rel="noopener noreferrer" @endif
                                            class="inline-flex items-center gap-2 rounded-2xl bg-blue-600 px-5 py-3 text-sm font-extrabold text-white shadow-lg shadow-blue-100 hover:bg-blue-700 transition"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.812a4 4 0 005.656 0l4-4a4 4 0 10-5.656-5.656l-1.1 1.1"></path></svg>
                                            {{ $validationPopup['official_url_label'] }}
                                        </a>
                                        <a href="{{ route('user.validation-results') }}" class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 px-5 py-3 text-sm font-bold text-slate-700 hover:bg-slate-50 transition">
                                            Lihat riwayat analisis
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @else
                            <p class="mt-3 text-sm text-slate-600">
                                Belum ditemukan referensi resmi yang cukup mirip untuk ditautkan secara langsung.
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
