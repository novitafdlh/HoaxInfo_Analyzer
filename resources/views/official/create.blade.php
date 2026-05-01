<x-admin-shell title="Tambah Konten Resmi">
    <x-slot name="pageHeader">
        <section class="space-y-4">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <h1 class="text-3xl font-black tracking-tight text-slate-950 sm:text-4xl">Tambah Konten Resmi</h1>
                    <p class="mt-2 max-w-3xl text-sm leading-relaxed text-slate-600 sm:text-base">
                        Tambahkan referensi visual resmi baru ke basis data agar sistem memiliki acuan yang lebih kuat saat memverifikasi informasi publik.
                    </p>
                </div>

                <a href="{{ route('official.index') }}" class="inline-flex items-center justify-center gap-2 rounded-full border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-700 shadow-sm transition hover:bg-slate-50">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                    Kembali ke galeri
                </a>
            </div>

            <div class="overflow-hidden rounded-[2rem] border border-blue-100 bg-gradient-to-r from-white via-blue-50 to-cyan-50 shadow-[0px_12px_28px_rgba(37,99,235,0.08)]">
                <div class="flex items-start gap-4 p-5">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-slate-900 text-white shadow-lg shadow-slate-300/40">
                        <span class="material-symbols-outlined text-[22px]">add_photo_alternate</span>
                    </div>
                    <div>
                        <p class="text-sm font-black uppercase tracking-[0.2em] text-blue-700">Form Referensi Resmi</p>
                        <p class="mt-2 text-sm leading-relaxed text-slate-600">
                            Gunakan formulir ini untuk menambahkan infografis, surat edaran, atau visual resmi lain yang akan menjadi referensi utama sistem.
                        </p>
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
            <div class="mb-8 flex items-start gap-4 rounded-[1.75rem] border border-slate-200 bg-slate-50/70 p-5">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-blue-50 text-blue-700">
                    <span class="material-symbols-outlined text-[20px]">info</span>
                </div>
                <div>
                    <p class="text-[11px] font-black uppercase tracking-[0.18em] text-slate-500">Petunjuk Pengisian</p>
                    <p class="mt-2 text-sm leading-relaxed text-slate-600">
                        Isi judul dan kategori dengan jelas. Pilih salah satu metode sumber gambar: unggah file langsung atau gunakan tautan resmi dari instansi terkait.
                    </p>
                </div>
            </div>

            <form action="{{ route('official.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf

                <div class="grid gap-6 lg:grid-cols-2">
                    <div>
                        <x-input-label for="title" :value="__('Judul Konten Informasi Resmi')" class="ml-1 font-bold text-slate-700" />
                        <p class="mt-1 ml-1 text-xs leading-relaxed text-slate-500">Masukkan judul yang jelas agar konten mudah dikenali di galeri referensi.</p>
                        <x-text-input id="title" name="title" type="text" class="mt-3 block w-full rounded-[1.25rem] border-slate-200 bg-slate-50/60 py-3.5 shadow-sm transition duration-150 focus:border-blue-500 focus:bg-white focus:ring-blue-500" :value="old('title')" required autofocus placeholder="Contoh: Infografis Status Gunung Awu April 2026" />
                    </div>

                    <div>
                        <x-input-label for="category" :value="__('Kategori Konten')" class="ml-1 font-bold text-slate-700" />
                        <p class="mt-1 ml-1 text-xs leading-relaxed text-slate-500">Pilih kategori yang sudah tersedia, atau tambahkan kategori baru jika belum ada.</p>

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
                            <span class="material-symbols-outlined pointer-events-none absolute right-5 top-1/2 -translate-y-1/2 text-slate-400">expand_more</span>
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

                <div class="grid gap-6 xl:grid-cols-2">
                    <div class="rounded-[1.75rem] border border-slate-200 bg-slate-50/70 p-5">
                        <x-input-label for="image" :value="__('Unggah Gambar Resmi')" class="ml-1 font-bold text-slate-700" />
                        <p class="mt-1 ml-1 text-xs leading-relaxed text-slate-500">Gunakan file visual resmi dari perangkat Anda. Maksimum 100MB.</p>

                        <label for="image" class="group mt-4 flex h-64 w-full cursor-pointer flex-col items-center justify-center rounded-[1.5rem] border-2 border-dashed border-slate-300 bg-white px-4 text-center transition hover:border-blue-400 hover:bg-blue-50/60">
                            <div class="flex h-16 w-16 items-center justify-center rounded-full bg-slate-50 text-slate-400 transition group-hover:bg-white group-hover:text-blue-600">
                                <span class="material-symbols-outlined text-[34px]">upload</span>
                            </div>
                            <p class="mt-4 text-sm font-black text-slate-800">Klik untuk unggah file gambar resmi</p>
                            <p class="mt-1 text-xs text-slate-500">atau seret dan lepas file dari perangkat Anda</p>
                            <p id="file-name-display" class="mt-4 max-w-full overflow-hidden text-ellipsis whitespace-nowrap rounded-full bg-blue-50 px-4 py-2 text-xs font-bold text-blue-700"></p>
                        </label>
                        <input id="image" name="image" type="file" accept="image/*" class="hidden" onchange="document.getElementById('file-name-display').innerText = this.files.length ? this.files[0].name : ''" />
                    </div>

                    <div class="rounded-[1.75rem] border border-slate-200 bg-slate-50/70 p-5">
                        <x-input-label for="image_url" :value="__('Atau Masukkan URL Gambar Resmi')" class="ml-1 font-bold text-slate-700" />
                        <p class="mt-1 ml-1 text-xs leading-relaxed text-slate-500">Masukkan tautan langsung dari website pemerintah atau kanal resmi instansi.</p>

                        <div class="mt-4 rounded-[1.5rem] border border-slate-200 bg-white p-5">
                            <div class="flex items-center gap-3">
                                <div class="flex h-11 w-11 items-center justify-center rounded-full bg-blue-50 text-blue-700">
                                    <span class="material-symbols-outlined text-[20px]">link</span>
                                </div>
                                <div>
                                    <p class="text-sm font-black text-slate-900">Gunakan tautan resmi</p>
                                    <p class="text-xs text-slate-500">Pastikan URL mengarah langsung ke gambar yang valid.</p>
                                </div>
                            </div>

                            <x-text-input id="image_url" name="image_url" type="url" :value="old('image_url')" placeholder="https://kominfo.go.id/gambar-resmi.jpg" class="mt-5 block w-full rounded-[1.25rem] border-slate-200 bg-slate-50/60 py-3.5 shadow-sm transition duration-150 focus:border-blue-500 focus:bg-white focus:ring-blue-500" />
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
