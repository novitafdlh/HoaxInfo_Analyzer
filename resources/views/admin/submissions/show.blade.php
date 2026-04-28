<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4 pb-2 border-b border-slate-100">
            <div class="p-2.5 bg-indigo-100 rounded-xl text-indigo-700 shadow-inner border border-indigo-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z"></path>
                </svg>
            </div>
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight leading-tight">
                    {{ __('Detail Analisis Konten') }}
                </h2>
                <p class="text-xs font-medium text-slate-500 uppercase tracking-widest mt-0.5">Similarity, confidence, dan keputusan admin</p>
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-gradient-to-b from-slate-50 to-white min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid gap-8 lg:grid-cols-12">
                <div class="lg:col-span-3">
                    <div class="sticky top-24 rounded-3xl overflow-hidden border border-slate-200 bg-white shadow-sm hover:shadow-md transition-shadow">
                        @include('admin.partials.sidebar')
                    </div>
                </div>

                <div class="space-y-8 lg:col-span-9">
                    <div class="rounded-3xl border border-slate-200 bg-white p-7 shadow-xl shadow-slate-100/70 transition-all duration-300 hover:shadow-indigo-50">
                        <p class="text-sm text-slate-600 leading-relaxed flex items-center gap-3">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Sistem hanya memberi rekomendasi berbasis similarity dan confidence terhadap konten resmi. Keputusan akhir tetap berada di tangan admin.
                        </p>
                    </div>

                    @if (session('status'))
                        <div class="flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800 shadow-sm shadow-emerald-100/50">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800 shadow-sm shadow-rose-100/50">
                            <ul class="list-disc list-inside space-y-1 font-medium">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @php
                        $finalTone = match ($submission->final_status) {
                            'terverifikasi' => 'bg-emerald-100 text-emerald-800 shadow-emerald-100/50',
                            'perlu_tindak_lanjut', 'tidak_valid' => 'bg-rose-100 text-rose-800 shadow-rose-100/50',
                            default => 'bg-amber-100 text-amber-800 shadow-amber-100/50',
                        };
                    @endphp

                    <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-xl shadow-slate-100/70 transition-all duration-300 hover:shadow-indigo-50">
                        <div class="grid gap-8 md:grid-cols-2">
                            <div class="group">
                                <p class="text-sm font-bold text-slate-700 ml-1">Gambar Submission</p>
                                <div class="mt-3 relative overflow-hidden rounded-2xl border-2 border-slate-200 shadow-inner group-hover:border-indigo-300 transition duration-300">
                                    <img src="{{ asset('storage/'.$submission->image_path) }}" alt="Submission" class="w-full object-cover">
                                </div>
                            </div>

                            <div class="space-y-6">
                                <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/50">
                                    <p class="text-xs font-bold uppercase tracking-widest text-slate-500">Hash Gambar</p>
                                    <p class="mt-2 break-all rounded-lg bg-white px-4 py-3 border border-slate-100 text-sm font-mono text-slate-800 shadow-inner">{{ $submission->image_hash }}</p>
                                </div>

                                <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/50">
                                    <p class="text-xs font-bold uppercase tracking-widest text-slate-500">Teks Hasil OCR</p>
                                    <p class="mt-2 rounded-lg bg-white px-4 py-3 border border-slate-100 text-sm font-medium leading-relaxed text-slate-800 shadow-inner">
                                        {{ $submission->extracted_text ?: '-' }}
                                    </p>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/50">
                                        <p class="text-xs font-bold uppercase tracking-widest text-slate-500">Similarity</p>
                                        <p class="mt-2 text-3xl font-black {{ $submission->similarity_score >= 85 ? 'text-emerald-600' : ($submission->similarity_score >= 60 ? 'text-amber-600' : ($submission->similarity_score >= 30 ? 'text-orange-600' : 'text-rose-600')) }}">
                                            {{ $submission->similarity_score !== null ? $submission->similarity_score.'%' : '-' }}
                                        </p>
                                    </div>
                                    <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/50">
                                        <p class="text-xs font-bold uppercase tracking-widest text-slate-500">Confidence</p>
                                        <p class="mt-2 text-xl font-bold text-slate-800 leading-tight">
                                            {{ $submission->confidence_label ?? '-' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/50">
                                        <p class="text-xs font-bold uppercase tracking-widest text-slate-500">Metode Analisis</p>
                                        <p class="mt-2 text-base font-bold text-slate-800 leading-tight">{{ $submission->analysisMethodLabel() }}</p>
                                    </div>
                                    <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/50">
                                        <p class="text-xs font-bold uppercase tracking-widest text-slate-500">Rekomendasi Sistem</p>
                                        <p class="mt-2 text-base font-bold text-slate-800 leading-tight">{{ $submission->systemStatusLabel() }}</p>
                                    </div>
                                </div>

                                <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/50">
                                    <p class="text-xs font-bold uppercase tracking-widest text-slate-500 mb-2">Referensi Resmi Terdekat</p>
                                    @if ($submission->matchedOfficialContent)
                                        <div class="rounded-lg bg-white px-4 py-3 border border-slate-100 text-sm text-slate-800 shadow-inner">
                                            <div class="font-semibold">{{ $submission->matchedOfficialContent->title }}</div>
                                            <div class="mt-1 text-slate-500">Kategori: {{ $submission->matchedOfficialContent->category ?: 'Umum' }}</div>

                                            <div class="mt-4">
                                                <p class="text-xs font-bold uppercase tracking-widest text-slate-500">Hash Gambar Official</p>
                                                <p class="mt-2 break-all rounded-lg border border-slate-100 bg-slate-50 px-4 py-3 font-mono text-sm text-slate-800">
                                                    {{ $submission->matchedOfficialContent->image_hash ?: '-' }}
                                                </p>
                                            </div>

                                            <div class="mt-4">
                                                <p class="text-xs font-bold uppercase tracking-widest text-slate-500">Teks OCR Official</p>
                                                <p class="mt-2 rounded-lg border border-slate-100 bg-slate-50 px-4 py-3 text-sm font-medium leading-relaxed text-slate-800">
                                                    {{ $submission->matchedOfficialContent->extracted_text ?: 'Teks OCR official belum tersedia.' }}
                                                </p>
                                            </div>
                                        </div>
                                    @else
                                        <div class="rounded-lg bg-white px-4 py-3 border border-slate-100 text-sm text-slate-500 shadow-inner">
                                            Belum ada referensi resmi yang cocok atau basis data referensi belum tersedia.
                                        </div>
                                    @endif
                                </div>

                                <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/50">
                                    <p class="text-xs font-bold uppercase tracking-widest text-slate-500 mb-2">Status Final (Review Admin)</p>
                                    <span class="inline-flex items-center gap-2 rounded-full px-4 py-2 text-sm font-bold shadow-sm {{ $finalTone }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        {{ $submission->finalStatusLabel() }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-10 pt-8 flex flex-wrap items-center gap-4 border-t border-slate-100">
                            <h4 class="text-base font-bold text-slate-900 tracking-tight mr-2">Keputusan Manual Admin:</h4>

                            <form method="POST" action="{{ route('admin.submissions.update-status', $submission) }}" onsubmit="return confirm('Apakah Anda yakin ingin menandai konten ini sebagai terverifikasi admin?');">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="final_status" value="terverifikasi">
                                <button type="submit" class="inline-flex items-center gap-2 rounded-2xl bg-emerald-600 px-6 py-3.5 text-sm font-extrabold text-white shadow-lg shadow-emerald-100 hover:bg-emerald-700 transition active:scale-[0.98]">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Tetapkan Terverifikasi
                                </button>
                            </form>

                            <form method="POST" action="{{ route('admin.submissions.update-status', $submission) }}" onsubmit="return confirm('Apakah Anda yakin konten ini perlu tindak lanjut atau verifikasi tambahan?');">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="final_status" value="perlu_tindak_lanjut">
                                <button type="submit" class="inline-flex items-center gap-2 rounded-2xl bg-rose-600 px-6 py-3.5 text-sm font-extrabold text-white shadow-lg shadow-rose-100 hover:bg-rose-700 transition active:scale-[0.98]">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Tandai Perlu Tindak Lanjut
                                </button>
                            </form>

                            <form method="POST" action="{{ route('admin.submissions.update-status', $submission) }}" onsubmit="return confirm('Kembalikan status konten ini ke menunggu validasi admin?');">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="final_status" value="menunggu_validasi">
                                <button type="submit" class="inline-flex items-center gap-2 rounded-2xl bg-slate-700 px-6 py-3.5 text-sm font-extrabold text-white shadow-lg shadow-slate-200 hover:bg-slate-800 transition active:scale-[0.98]">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                    Kembalikan ke Review
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
