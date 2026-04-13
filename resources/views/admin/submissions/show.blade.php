<x-app-layout>
    {{-- Header Halaman Admin yang Modern & Tepercaya --}}
    <x-slot name="header">
        <div class="flex items-center gap-4 pb-2 border-b border-slate-100">
            {{-- Ikon Analisis/Detail (Opsional) --}}
            <div class="p-2.5 bg-indigo-100 rounded-xl text-indigo-700 shadow-inner border border-indigo-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z"></path>
                </svg>
            </div>
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight leading-tight">
                    {{ __('Detail Analisis Konten') }}
                </h2>
                <p class="text-xs font-medium text-slate-500 uppercase tracking-widest mt-0.5">Peninjauan Mendetail Submission Masyarakat</p>
            </div>
        </div>
    </x-slot>

    {{-- Background Gradien Lembut agar Lebih Santai & Nyaman --}}
    <div class="py-10 bg-gradient-to-b from-slate-50 to-white min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid gap-8 lg:grid-cols-12">
                
                {{-- Sidebar Admin (Memakai Kolom Lebih Sedikit agar Detail Luas) --}}
                <div class="lg:col-span-3">
                    <div class="sticky top-24 rounded-3xl overflow-hidden border border-slate-200 bg-white shadow-sm hover:shadow-md transition-shadow">
                        @include('admin.partials.sidebar')
                    </div>
                </div>

                {{-- Area Utama Detail Konten (Modern & Interaktif) --}}
                <div class="space-y-8 lg:col-span-9">
                    
                    {{-- Kotak Deskripsi Halaman --}}
                    <div class="rounded-3xl border border-slate-200 bg-white p-7 shadow-xl shadow-slate-100/70 transition-all duration-300 hover:shadow-indigo-50">
                        <p class="text-sm text-slate-600 leading-relaxed flex items-center gap-3">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Halaman ini menampilkan hasil analisis sistem terhadap gambar yang dikirimkan masyarakat. Silakan lakukan validasi manual berdasarkan data di bawah ini.
                        </p>
                    </div>

                    {{-- Notifikasi Sukses --}}
                    @if (session('status'))
                        <div class="flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800 shadow-sm shadow-emerald-100/50">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ session('status') }}
                        </div>
                    @endif

                    {{-- Menampilkan Error Validasi Global jika ada --}}
                    @if ($errors->any())
                        <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800 shadow-sm shadow-rose-100/50">
                            <ul class="list-disc list-inside space-y-1 font-medium">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Kartu Detail Utama (Tampilan Gambar & Data) --}}
                    <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-xl shadow-slate-100/70 transition-all duration-300 hover:shadow-indigo-50">
                        <div class="grid gap-8 md:grid-cols-2">
                            
                            {{-- Area Gambar --}}
                            <div class="group">
                                <p class="text-sm font-bold text-slate-700 ml-1">Gambar Submission</p>
                                <div class="mt-3 relative overflow-hidden rounded-2xl border-2 border-slate-200 shadow-inner group-hover:border-indigo-300 transition duration-300">
                                    <img src="{{ asset('storage/'.$submission->image_path) }}" alt="Submission" class="w-full object-cover group-hover:scale-105 transition-transform duration-500">
                                    <div class="absolute inset-0 bg-black/5 opacity-0 group-hover:opacity-100 transition duration-300"></div>
                                </div>
                            </div>

                            {{-- Area Data Analisis (List Info yang Rapi) --}}
                            <div class="space-y-6">
                                {{-- Hash --}}
                                <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/50 hover:border-indigo-100 transition">
                                    <p class="text-xs font-bold uppercase tracking-widest text-slate-500">Hash Gambar</p>
                                    <p class="mt-2 break-all rounded-lg bg-white px-4 py-3 border border-slate-100 text-sm font-mono text-slate-800 shadow-inner">{{ $submission->image_hash }}</p>
                                </div>

                                {{-- OCR --}}
                                <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/50 hover:border-indigo-100 transition">
                                    <p class="text-xs font-bold uppercase tracking-widest text-slate-500">Teks Hasil OCR</p>
                                    <p class="mt-2 rounded-lg bg-white px-4 py-3 border border-slate-100 text-sm font-medium leading-relaxed text-slate-800 shadow-inner">
                                        {{ $submission->extracted_text ?: '-' }}
                                    </p>
                                </div>

                                {{-- Score & Status --}}
                                <div class="grid grid-cols-2 gap-4">
                                    {{-- Score --}}
                                    <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/50 hover:border-indigo-100 transition">
                                        <p class="text-xs font-bold uppercase tracking-widest text-slate-500">Kecocokan (AI)</p>
                                        <p class="mt-2 text-3xl font-black {{ $submission->similarity_score >= 80 ? 'text-emerald-600' : ($submission->similarity_score >= 50 ? 'text-amber-600' : 'text-rose-600') }}">
                                            {{ $submission->similarity_score !== null ? $submission->similarity_score.'%' : '-' }}
                                        </p>
                                    </div>
                                    {{-- Status Sistem --}}
                                    <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/50 hover:border-indigo-100 transition">
                                        <p class="text-xs font-bold uppercase tracking-widest text-slate-500 whitespace-nowrap">Status Sistem</p>
                                        <p class="mt-2 text-xl font-bold text-slate-800 capitalize leading-tight">
                                            {{ $submission->system_status ?? '-' }}
                                        </p>
                                    </div>
                                </div>

                                {{-- Status Final (Badge Modern) --}}
                                <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/50 hover:border-indigo-100 transition">
                                    <p class="text-xs font-bold uppercase tracking-widest text-slate-500 mb-2">Status Final (Review Admin)</p>
                                    @if($submission->final_status === 'terverifikasi')
                                        <span class="inline-flex items-center gap-2 rounded-full bg-emerald-100 px-4 py-2 text-sm font-bold text-emerald-800 shadow-sm shadow-emerald-100/50">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Tervalidasi
                                        </span>
                                    @elseif($submission->final_status === 'tidak_valid')
                                        <span class="inline-flex items-center gap-2 rounded-full bg-rose-100 px-4 py-2 text-sm font-bold text-rose-800 shadow-sm shadow-rose-100/50">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Tidak Valid
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-2 rounded-full bg-amber-100 px-4 py-2 text-sm font-bold text-amber-800 shadow-sm shadow-amber-100/50 pulse">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Butuh Review
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Bagian Tombol Aksi Validasi (Lebih Rapi & Menonjol) --}}
                        <div class="mt-10 pt-8 flex flex-wrap items-center gap-4 border-t border-slate-100">
                            <h4 class="text-base font-bold text-slate-900 tracking-tight mr-2">Keputusan Validasi Manual Admin:</h4>
                            
                            <form method="POST" action="{{ route('admin.submissions.update-status', $submission) }}" onsubmit="return confirm('Apakah Anda yakin ingin memberikan status VALID pada konten ini?');">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="final_status" value="terverifikasi">
                                <button type="submit" class="inline-flex items-center gap-2 rounded-2xl bg-emerald-600 px-6 py-3.5 text-sm font-extrabold text-white shadow-lg shadow-emerald-100 hover:bg-emerald-700 transition active:scale-[0.98]">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Konten VALID / Benar
                                </button>
                            </form>

                            <form method="POST" action="{{ route('admin.submissions.update-status', $submission) }}" onsubmit="return confirm('Apakah Anda yakin ingin memberikan status TIDAK VALID (HOAX) pada konten ini?');">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="final_status" value="tidak_valid">
                                <button type="submit" class="inline-flex items-center gap-2 rounded-2xl bg-rose-600 px-6 py-3.5 text-sm font-extrabold text-white shadow-lg shadow-rose-100 hover:bg-rose-700 transition active:scale-[0.98]">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    TIDAK VALID / Hoax
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>