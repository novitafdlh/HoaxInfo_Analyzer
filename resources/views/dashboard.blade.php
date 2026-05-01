@php
    $validationPopup = session('validation_popup');
@endphp

<x-portal-shell title="Dashboard Tamu" mode="guest">
    <section class="space-y-4">
        <div class="flex justify-between items-end">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-on-surface">Selamat Datang, Tamu</h1>
                <p class="mt-2 max-w-3xl text-sm leading-relaxed text-slate-600 sm:text-base">Analisis informasi visual publik tanpa perlu login.</p>
            </div>
        </div>

        <div class="rounded-[2rem] border border-slate-200 bg-white p-2">
            <div class="p-3 md:p-4">
                <div class="flex items-center justify-between gap-4">
                    <div class="flex cursor-pointer items-center gap-3 transition-opacity hover:opacity-80" onclick="togglePanduan()">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-blue-50 text-blue-700">
                            <span class="material-symbols-outlined text-[20px]" data-icon="lightbulb">lightbulb</span>
                        </div>
                        <div>
                            <p class="text-base font-bold text-blue-950">Panduan Cepat Validasi</p>
                            <p class="text-xs text-blue-900/60">3 poin singkat untuk membaca hasil analisis.</p>
                        </div>
                    </div>
                    <button
                        aria-label="Toggle panduan cepat"
                        class="flex h-8 w-8 items-center justify-center rounded-full border border-blue-200 bg-white/90 text-blue-700 transition hover:bg-white"
                        type="button"
                        onclick="togglePanduan()"
                    >
                        <span class="inline-block rotate-180 text-lg font-black leading-none transition-transform duration-200" id="panduan-icon">^</span>
                    </button>
                </div>

                <div class="hidden pt-3" id="panduan-content">
                    <div class="grid grid-cols-1 gap-3 md:grid-cols-3">
                        <div class="rounded-2xl border border-slate-200 bg-white/80 p-3">
                            <div class="mb-2 inline-flex rounded-full bg-blue-100 px-2.5 py-1 text-[11px] font-extrabold tracking-[0.18em] text-blue-700">01</div>
                            <h3 class="text-sm font-bold text-slate-900">Similarity 85-100%</h3>
                            <p class="mt-1 text-xs leading-relaxed text-slate-600">Kemiripan sangat tinggi, menunjukkan kemungkinan konten yang valid dan terverifikasi.</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-white/80 p-3">
                            <div class="mb-2 inline-flex rounded-full bg-amber-100 px-2.5 py-1 text-[11px] font-extrabold tracking-[0.18em] text-amber-700">02</div>
                            <h3 class="text-sm font-bold text-slate-900">Similarity 60-84%</h3>
                            <p class="mt-1 text-xs leading-relaxed text-slate-600">Kemiripan tinggi, tetapi masih butuh pengecekan manual oleh validator.</p>
                        </div>
                        <div class="rounded-2xl border border-rose-200 bg-rose-50/80 p-3">
                            <div class="mb-2 inline-flex rounded-full bg-rose-100 px-2.5 py-1 text-[11px] font-extrabold tracking-[0.18em] text-rose-700">03</div>
                            <h3 class="text-sm font-bold text-slate-900">Low Similarity</h3>
                            <p class="mt-1 text-xs leading-relaxed text-slate-600">Di bawah 60% biasanya menandakan konten baru atau belum terhubung ke referensi resmi.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="lg:col-span-2 bg-surface-container-lowest rounded-xl p-10 shadow-[0px_20px_40px_rgba(25,28,30,0.06)] relative overflow-hidden">
            <div class="relative z-10 space-y-8">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                    <div>
                        <h2 class="text-2xl font-bold tracking-tight mb-2">Mulai Validasi Baru</h2>
                        <p class="text-on-surface-variant">Unggah berkas atau masukkan tautan konten untuk dianalisis oleh Guardian AI.</p>
                    </div>
                    <div class="rounded-2xl border border-amber-200 bg-amber-50 px-5 py-4 text-sm text-amber-800 shadow-sm">
                        <p class="text-xs font-bold uppercase tracking-[0.24em] text-amber-700">Token Gratis Harian</p>
                        <p class="mt-2 text-2xl font-black text-amber-900">{{ $guestUploadLimitEnabled ? $guestUploadsRemaining : '' }}</p>
                        <p class="mt-1 text-xs text-amber-700">
                            {{ $guestUploadLimitEnabled ? "sisa dari {$guestUploadLimit} validasi gratis hari ini" : 'Tanpa batas di mode testing lokal' }}
                        </p>
                        <p class="mt-2 text-xs text-amber-700">{{ $guestUploadsUsed }} upload telah digunakan hari ini.</p>
                    </div>
                </div>

                @if (session('status'))
                    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-800">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-800">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="validation-form" method="POST" action="{{ route('dashboard.upload') }}" enctype="multipart/form-data" class="space-y-8">
                    @csrf

                    <input id="image_file" name="image_file" type="file" accept="image/*" class="hidden" onchange="updateSelectedFileName(this)" />

                    <div id="upload-dropzone" class="border-4 border-dashed border-surface-container-highest rounded-xl p-12 flex flex-col items-center justify-center gap-4 bg-surface-container-low/30 hover:bg-surface-container-low transition-colors cursor-pointer group relative" onclick="triggerImagePicker()">
                        <div id="upload-placeholder" class="flex flex-col items-center justify-center gap-4">
                            <div class="w-20 h-20 rounded-full bg-white flex items-center justify-center shadow-md group-hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined text-4xl text-surface-tint" data-icon="cloud_upload">cloud_upload</span>
                            </div>
                            <div class="text-center">
                                <p class="text-xl font-bold">Seret &amp; Lepas Gambar</p>
                                <p class="text-on-surface-variant text-sm">Mendukung format JPG, PNG, atau WEBP (Maks. 100MB)</p>
                            </div>
                            <button class="mt-2 rounded-full bg-blue-600 px-8 py-3 text-sm font-bold text-white shadow-md transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300" type="button" onclick="event.stopPropagation(); triggerImagePicker()">
                                Pilih Berkas
                            </button>
                        </div>

                        <div id="image-preview-container" class="hidden w-full max-w-2xl">
                            <div class="relative overflow-hidden rounded-[2rem] border border-blue-100 bg-white p-3 shadow-[0px_20px_40px_rgba(37,99,235,0.12)]">
                                <img id="image-preview" class="h-[320px] w-full rounded-[1.5rem] bg-slate-50 object-contain" alt="Preview gambar yang dipilih" />
                                <button
                                    aria-label="Hapus gambar yang dipilih"
                                    class="absolute right-7 top-7 flex h-11 w-11 items-center justify-center rounded-full bg-rose-600 text-white shadow-lg transition hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-rose-300"
                                    type="button"
                                    onclick="event.stopPropagation(); clearSelectedImage()"
                                >
                                    <span class="material-symbols-outlined text-[22px]">close</span>
                                </button>
                            </div>
                            <div class="mt-4 text-center">
                                <p class="text-sm font-semibold text-surface-tint" id="selected-file-name"></p>
                                <p class="mt-2 text-sm text-on-surface-variant">Klik area gambar untuk mengganti file lain, atau tekan `X` untuk membatalkan pilihan.</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col gap-4">
                        <div class="flex items-center gap-4 text-on-surface-variant">
                            <div class="h-px flex-1 bg-outline-variant/30"></div>
                            <span class="text-sm font-bold uppercase tracking-widest">Atau via URL</span>
                            <div class="h-px flex-1 bg-outline-variant/30"></div>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <div class="flex-1 bg-surface-container-highest rounded-full px-6 py-4 flex items-center gap-3 focus-within:bg-white focus-within:ring-2 focus-within:ring-surface-tint transition-all">
                                <span class="material-symbols-outlined text-on-surface-variant" data-icon="link">link</span>
                                <input class="bg-transparent border-none focus:ring-0 text-sm w-full placeholder:text-on-surface-variant" name="image_url" placeholder="https://media-sosial.com/konten-berita-palsu" type="url" value="{{ old('image_url') }}"/>
                            </div>
                            <button class="flex items-center justify-center gap-2 rounded-full bg-blue-600 px-8 py-4 text-sm font-bold text-white shadow-lg transition-all hover:scale-[1.02] hover:bg-blue-700 active:scale-95 focus:outline-none focus:ring-2 focus:ring-blue-300" type="submit">
                                <span class="material-symbols-outlined" data-icon="fact_check">fact_check</span>
                                Validasi Sekarang
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-surface-tint/5 rounded-full blur-3xl"></div>
        </div>
    </section>

    @if ($validationPopup)
        <div class="fixed inset-0 z-[100] flex items-center justify-center p-4" id="validationModal">
            <div class="absolute inset-0 bg-on-background/40 backdrop-blur-sm" onclick="closeValidationModal()"></div>
            <div class="relative bg-surface-container-lowest rounded-xl max-w-3xl w-full p-10 shadow-2xl space-y-6">
                <div class="flex items-start justify-between gap-6">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.24em] text-surface-tint">Hasil Validasi</p>
                        <h2 class="text-2xl font-bold mt-2">Konten selesai dianalisis</h2>
                        <p class="text-on-surface-variant mt-2">Sistem menampilkan ringkasan kemiripan, confidence, dan tindakan lanjutan yang bisa langsung Anda buka.</p>
                    </div>
                    <button class="rounded-full border border-outline-variant/40 p-2 text-on-surface-variant hover:bg-surface-container-low transition" type="button" onclick="closeValidationModal()">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
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
                    <div class="rounded-2xl border border-cyan-100 bg-cyan-50 p-5">
                        <p class="text-xs font-bold uppercase tracking-widest text-cyan-700">Metode</p>
                        <p class="mt-2 text-base font-black leading-tight text-cyan-900">{{ $validationPopup['analysis_method_label'] }}</p>
                    </div>
                </div>

                <div class="rounded-2xl border border-outline-variant/30 bg-surface-container-low p-5">
                    <p class="text-xs font-bold uppercase tracking-widest text-on-surface-variant">Rekomendasi Sistem</p>
                    <p class="mt-2 text-lg font-bold text-on-surface">{{ $validationPopup['system_status_label'] }}</p>

                    @if (!empty($validationPopup['official_title']))
                        <div class="mt-4 rounded-xl border border-outline-variant/30 bg-white p-4">
                            <p class="text-xs font-bold uppercase tracking-widest text-on-surface-variant">Konten resmi paling mirip</p>
                            <p class="mt-2 text-base font-bold text-on-surface">{{ $validationPopup['official_title'] }}</p>

                            @if (!empty($validationPopup['official_url']))
                                <div class="mt-4 rounded-xl border border-outline-variant/30 bg-surface-container-low px-4 py-3">
                                    <p class="text-xs font-bold uppercase tracking-widest text-on-surface-variant">URL Konten Resmi</p>
                                    <p class="mt-2 break-all text-sm text-on-surface">{{ $validationPopup['official_url'] }}</p>
                                </div>
                            @endif
                        </div>
                    @else
                        <p class="mt-3 text-sm text-on-surface-variant">
                            Belum ditemukan referensi resmi yang cukup mirip untuk ditautkan secara langsung.
                        </p>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <script>
        let uploadPreviewUrl = null;

        function togglePanduan() {
            const content = document.getElementById('panduan-content');
            const icon = document.getElementById('panduan-icon');
            const isHidden = content.classList.toggle('hidden');

            icon.classList.toggle('rotate-180', isHidden);
        }

        function triggerImagePicker() {
            document.getElementById('image_file').click();
        }

        function updateSelectedFileName(input) {
            const file = input.files && input.files.length ? input.files[0] : null;
            const uploadPlaceholder = document.getElementById('upload-placeholder');
            const imagePreviewContainer = document.getElementById('image-preview-container');
            const imagePreview = document.getElementById('image-preview');
            const selectedFileName = document.getElementById('selected-file-name');

            if (!file) {
                resetSelectedImageState();
                return;
            }

            if (uploadPreviewUrl) {
                URL.revokeObjectURL(uploadPreviewUrl);
            }

            uploadPreviewUrl = URL.createObjectURL(file);
            imagePreview.src = uploadPreviewUrl;
            imagePreview.alt = `Preview ${file.name}`;
            selectedFileName.innerText = `File dipilih: ${file.name}`;

            uploadPlaceholder.classList.add('hidden');
            imagePreviewContainer.classList.remove('hidden');
        }

        function clearSelectedImage() {
            const input = document.getElementById('image_file');

            input.value = '';
            resetSelectedImageState();
        }

        function resetSelectedImageState() {
            const uploadPlaceholder = document.getElementById('upload-placeholder');
            const imagePreviewContainer = document.getElementById('image-preview-container');
            const imagePreview = document.getElementById('image-preview');
            const selectedFileName = document.getElementById('selected-file-name');

            if (uploadPreviewUrl) {
                URL.revokeObjectURL(uploadPreviewUrl);
                uploadPreviewUrl = null;
            }

            imagePreview.removeAttribute('src');
            imagePreview.alt = 'Preview gambar yang dipilih';
            selectedFileName.innerText = '';

            imagePreviewContainer.classList.add('hidden');
            uploadPlaceholder.classList.remove('hidden');
        }

        function closeValidationModal() {
            const modal = document.getElementById('validationModal');

            if (modal) {
                modal.classList.add('hidden');
            }
        }
    </script>
</x-portal-shell>
