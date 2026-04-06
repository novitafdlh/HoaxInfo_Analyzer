<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Konten Informasi Resmi
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid gap-6 lg:grid-cols-4">
                <div class="lg:col-span-1">
                    @include('admin.partials.sidebar')
                </div>

                <div class="lg:col-span-3">
                    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                        <p class="text-sm text-gray-600">
                            Gunakan halaman ini untuk menambahkan konten visual resmi yang akan menjadi referensi dalam proses verifikasi informasi publik.
                        </p>

                        @if (session('success'))
                            <div class="mt-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="mt-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                                {{ session('error') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="mt-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('official.store') }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-6">
                            @csrf

                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700">Judul Konten Informasi</label>
                                <p class="mt-1 text-xs text-gray-500">Masukkan judul atau deskripsi singkat dari konten visual resmi.</p>
                                <input id="title" type="text" name="title" value="{{ old('title') }}" required class="mt-2 block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label for="category" class="block text-sm font-medium text-gray-700">Kategori Konten</label>
                                <p class="mt-1 text-xs text-gray-500">Contoh: Bencana Alam, Kesehatan, Politik, Edukasi, dan lainnya.</p>
                                <input id="category" type="text" name="category" value="{{ old('category') }}" required class="mt-2 block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Contoh: Kesehatan">
                            </div>

                            <div>
                                <label for="image" class="block text-sm font-medium text-gray-700">Upload Gambar Resmi</label>
                                <p class="mt-1 text-xs text-gray-500">Unggah file gambar infografis atau konten visual resmi dari perangkat Anda (maksimum 100MB).</p>
                                <input id="image" type="file" name="image" accept="image/*" class="mt-2 block w-full rounded-lg border-gray-300 bg-white text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label for="image_url" class="block text-sm font-medium text-gray-700">Atau Masukkan URL Gambar</label>
                                <p class="mt-1 text-xs text-gray-500">Masukkan tautan gambar dari sumber resmi seperti website pemerintah atau media sosial instansi.</p>
                                <input id="image_url" type="url" name="image_url" value="{{ old('image_url') }}" class="mt-2 block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="https://contoh.go.id/gambar-resmi.jpg">
                            </div>

                            <button type="submit" class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                                Simpan Konten Resmi
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
