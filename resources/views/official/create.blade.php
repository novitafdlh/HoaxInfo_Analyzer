<x-admin-shell title="Tambah Konten Resmi">
    <x-slot name="pageHeader">
        <section class="space-y-4">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight text-on-surface">Tambah Konten Resmi</h1>
                    <p class="mt-2 max-w-3xl text-sm leading-relaxed text-slate-600 sm:text-base">
                        Tambahkan referensi visual resmi baru ke basis data agar sistem memiliki acuan yang lebih kuat saat memverifikasi.
                    </p>
                </div>

                <a href="{{ route('official.index') }}" class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-blue-600 text-white shadow-lg shadow-blue-100 transition hover:bg-blue-700" aria-label="Kembali ke galeri" title="Kembali ke galeri">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                </a>
            </div>

            <div class="rounded-[2rem] border border-slate-200 bg-white p-2">
                <div class="p-3 md:p-4">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex cursor-pointer items-center gap-3 transition-opacity hover:opacity-80" onclick="toggleOfficialGuide()">
                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-blue-50 text-blue-700">
                                <span class="material-symbols-outlined text-[20px]">lightbulb</span>
                            </div>
                            <div>
                                <p class="text-base font-bold text-blue-950">Petunjuk Pengisian</p>
                                <p class="text-xs text-blue-900/60">3 langkah singkat untuk menambahkan referensi resmi.</p>
                            </div>
                        </div>
                        <button
                            aria-label="Toggle petunjuk pengisian"
                            class="flex h-8 w-8 items-center justify-center rounded-full border border-blue-200 bg-white/90 text-blue-700 transition hover:bg-white"
                            type="button"
                            onclick="toggleOfficialGuide()"
                        >
                            <span class="inline-block rotate-180 text-lg font-black leading-none transition-transform duration-200" id="official-guide-icon">^</span>
                        </button>
                    </div>

                    <div class="hidden pt-3" id="official-guide-content">
                        <div class="grid grid-cols-1 gap-3 md:grid-cols-3">
                            <div class="rounded-2xl border border-slate-200 bg-white/80 p-3">
                                <div class="mb-2 inline-flex rounded-full bg-blue-100 px-2.5 py-1 text-[11px] font-extrabold tracking-[0.18em] text-blue-700">01</div>
                                <h3 class="text-sm font-bold text-slate-900">Lengkapi Identitas</h3>
                                <p class="mt-1 text-xs leading-relaxed text-slate-600">Isi judul dan kategori agar konten mudah dicari di galeri referensi resmi.</p>
                            </div>
                            <div class="rounded-2xl border border-slate-200 bg-white/80 p-3">
                                <div class="mb-2 inline-flex rounded-full bg-amber-100 px-2.5 py-1 text-[11px] font-extrabold tracking-[0.18em] text-amber-700">02</div>
                                <h3 class="text-sm font-bold text-slate-900">Pilih Sumber Gambar</h3>
                                <p class="mt-1 text-xs leading-relaxed text-slate-600">Unggah file dari perangkat atau gunakan URL gambar resmi yang dapat diakses.</p>
                            </div>
                            <div class="rounded-2xl border border-slate-200 bg-white/80 p-3">
                                <div class="mb-2 inline-flex rounded-full bg-rose-100 px-2.5 py-1 text-[11px] font-extrabold tracking-[0.18em] text-rose-700">03</div>
                                <h3 class="text-sm font-bold text-slate-900">Simpan Referensi</h3>
                                <p class="mt-1 text-xs leading-relaxed text-slate-600">Konten tersimpan akan langsung menjadi basis pembanding pada proses validasi.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </x-slot>

    <section class="space-y-6">
        @if (session('success'))
            <div class="flex items-center gap-3 rounded-[1.5rem] border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-800 shadow-sm">
                <span class="material-symbols-outlined text-[22px] text-emerald-600">check_circle</span>
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="flex items-center gap-3 rounded-[1.5rem] border border-rose-200 bg-rose-50 px-5 py-4 text-sm font-semibold text-rose-800 shadow-sm">
                <span class="material-symbols-outlined text-[22px] text-rose-600">error</span>
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-[1.5rem] border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-800 shadow-sm">
                <ul class="list-disc space-y-1 pl-5 font-medium">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-[0px_20px_40px_rgba(25,28,30,0.06)] sm:p-8">
            <form action="{{ route('official.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf

                <div class="grid gap-6 lg:grid-cols-2">
                    <div>
                        <x-input-label for="title" :value="__('Judul Konten Informasi Resmi')" class="ml-1 font-bold text-slate-700" />
                        <x-text-input id="title" name="title" type="text" class="mt-3 block w-full rounded-[1.25rem] border-slate-200 bg-slate-50/60 py-3.5 shadow-sm transition duration-150 focus:border-blue-500 focus:bg-white focus:ring-blue-500" :value="old('title')" required autofocus placeholder="Contoh: Infografis Status Gunung Awu April 2026" />
                    </div>

                    <div>
                        <x-input-label for="category" :value="__('Kategori Konten')" class="ml-1 font-bold text-slate-700" />

                        @php
                            $oldCategory = old('category', $categories->isNotEmpty() ? $categories->first() : '__new__');
                            $showNewCategory = $oldCategory === '__new__';
                        @endphp

                        <div class="relative mt-3">
                            <span class="material-symbols-outlined absolute left-5 top-1/2 -translate-y-1/2 text-slate-400">category</span>
                            <select
                                id="category"
                                name="category"
                                required
                                class="block w-full appearance-none rounded-[1.25rem] border border-slate-200 bg-slate-50/60 py-3.5 pl-14 pr-12 text-sm text-slate-900 shadow-sm transition duration-150 focus:border-blue-500 focus:bg-white focus:ring-blue-500"
                            >
                                @foreach ($categories as $category)
                                    <option value="{{ $category }}" @selected($oldCategory === $category)>{{ $category }}</option>
                                @endforeach
                                <option value="__new__" @selected($showNewCategory)>+ Tambah kategori baru</option>
                            </select>
                        </div>

                        <div id="new-category-wrapper" class="{{ $showNewCategory ? '' : 'hidden' }} mt-3">
                            <x-text-input id="category_new" name="category_new" type="text" class="block w-full rounded-[1.25rem] border-slate-200 bg-slate-50/60 py-3.5 shadow-sm transition duration-150 focus:border-blue-500 focus:bg-white focus:ring-blue-500" :value="old('category_new')" placeholder="Contoh: Bencana Alam" />
                            <p class="mt-2 ml-1 text-xs leading-relaxed text-slate-500">Kategori baru akan tersedia di pilihan berikutnya setelah konten resmi disimpan.</p>
                        </div>
                    </div>
                </div>

                <div class="relative py-1">
                    <div class="absolute inset-0 flex items-center">
                        <span class="w-full border-t border-slate-200"></span>
                    </div>
                    <div class="relative flex justify-center">
                        <span class="rounded-full border border-slate-200 bg-white px-4 py-1 text-[11px] font-black uppercase tracking-[0.18em] text-slate-400">
                            Pilih salah satu metode sumber
                        </span>
                    </div>
                </div>

                <div class="rounded-[1.75rem] border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="space-y-8">
                        <div>
                            <x-input-label for="image" :value="__('Unggah Gambar Resmi')" class="ml-1 font-bold text-slate-700" />
                            <p class="mt-1 ml-1 text-xs leading-relaxed text-slate-500">Gunakan file visual resmi dari perangkat Anda. Maksimum 100MB.</p>
                        </div>

                        <input id="image" name="image" type="file" accept="image/*" class="hidden" onchange="updateOfficialFileName(this)" />
                        <label for="image" class="group flex min-h-80 w-full cursor-pointer flex-col items-center justify-center gap-4 rounded-xl border-4 border-dashed border-surface-container-highest bg-surface-container-low/30 p-12 text-center transition-colors hover:bg-surface-container-low">
                            <div class="flex h-20 w-20 items-center justify-center rounded-full bg-white shadow-md transition-transform group-hover:scale-110">
                                <span class="material-symbols-outlined text-4xl text-surface-tint">cloud_upload</span>
                            </div>
                            <div>
                                <p class="text-xl font-bold text-slate-950">Seret &amp; Lepas Gambar</p>
                                <p class="text-sm text-on-surface-variant">Mendukung format JPG, PNG, atau WEBP</p>
                            </div>
                            <span class="mt-2 rounded-full bg-blue-600 px-8 py-3 text-sm font-bold text-white shadow-md transition hover:bg-blue-700">
                                Pilih Berkas
                            </span>
                            <p id="file-name-display" class="max-w-full overflow-hidden text-ellipsis whitespace-nowrap text-sm font-semibold text-surface-tint"></p>
                        </label>

                        <div class="flex flex-col gap-4">
                            <div class="flex items-center gap-4 text-on-surface-variant">
                                <div class="h-px flex-1 bg-outline-variant/30"></div>
                                <span class="text-sm font-bold uppercase tracking-widest">Atau via URL</span>
                                <div class="h-px flex-1 bg-outline-variant/30"></div>
                            </div>

                            <div class="flex items-center gap-3 rounded-full bg-surface-container-highest px-6 py-4 transition-all focus-within:bg-white focus-within:ring-2 focus-within:ring-surface-tint">
                                <span class="material-symbols-outlined text-on-surface-variant">link</span>
                                <input
                                    id="image_url"
                                    name="image_url"
                                    type="url"
                                    value="{{ old('image_url') }}"
                                    placeholder="https://kominfo.go.id/gambar-resmi.jpg"
                                    class="w-full border-none bg-transparent text-sm placeholder:text-on-surface-variant focus:ring-0"
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-3 border-t border-slate-100 pt-6 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-sm text-slate-500">Data yang tersimpan akan langsung tersedia di galeri referensi resmi admin.</p>
                    <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-slate-900 px-8 py-4 text-sm font-black text-white shadow-lg shadow-slate-300/50 transition hover:bg-slate-800 sm:w-auto">
                        <span class="material-symbols-outlined text-[20px]">save</span>
                        Simpan sebagai Basis Data Resmi
                    </button>
                </div>
            </form>
        </div>
    </section>

    <script>
        function toggleOfficialGuide() {
            const content = document.getElementById('official-guide-content');
            const icon = document.getElementById('official-guide-icon');
            const isHidden = content.classList.toggle('hidden');

            icon.classList.toggle('rotate-180', isHidden);
        }

        function updateOfficialFileName(input) {
            const display = document.getElementById('file-name-display');
            display.innerText = input.files.length ? `File dipilih: ${input.files[0].name}` : '';
        }

        const categorySelect = document.getElementById('category');
        const newCategoryWrapper = document.getElementById('new-category-wrapper');
        const newCategoryInput = document.getElementById('category_new');

        if (categorySelect && newCategoryWrapper && newCategoryInput) {
            const syncNewCategoryField = () => {
                const shouldShow = categorySelect.value === '__new__';

                newCategoryWrapper.classList.toggle('hidden', ! shouldShow);
                newCategoryInput.required = shouldShow;

                if (! shouldShow) {
                    newCategoryInput.value = '';
                }
            };

            categorySelect.addEventListener('change', syncNewCategoryField);
            syncNewCategoryField();
        }
    </script>
</x-admin-shell>
