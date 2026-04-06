<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Pengguna
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid gap-6 lg:grid-cols-3">
                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm lg:col-span-1">
                    <h1 class="text-xl font-semibold">Dashboard Validasi Gambar</h1>
                    <p class="mt-2 text-sm text-slate-600">
                        Unggah file gambar atau URL gambar untuk pengecekan kebenaran informasi.
                    </p>
                    <ul class="mt-4 space-y-1 text-xs text-slate-500">
                        <li>100% cocok dengan konten resmi: tervalidasi otomatis.</li>
                        <li>50% - 99% mirip: proses validasi manual admin.</li>
                        <li>Di bawah 50%: tidak tervalidasi dari Kominfo.</li>
                    </ul>

                    <div class="mt-6">
                        <p class="rounded-xl border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm font-medium text-emerald-700">
                            Akun login aktif: upload tanpa batas.
                        </p>
                    </div>
                </section>

                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-lg shadow-cyan-100/60 lg:col-span-2">
                    <h2 class="text-lg font-semibold">Upload Gambar</h2>
                    <p class="mt-1 text-sm text-slate-500">Maksimum ukuran file 100MB.</p>

                    @if (session('status'))
                        <div class="mt-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mt-4 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('dashboard.upload') }}" enctype="multipart/form-data" class="mt-6 space-y-5">
                        @csrf

                        <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 p-4">
                            <label for="image_file" class="block text-sm font-medium text-slate-700">File Gambar</label>
                            <input id="image_file" name="image_file" type="file" accept="image/*" class="mt-2 block w-full rounded-lg border-slate-300 bg-white text-sm focus:border-cyan-500 focus:ring-cyan-500">
                        </div>

                        <div>
                            <label for="image_url" class="block text-sm font-medium text-slate-700">Atau URL Gambar</label>
                            <input id="image_url" name="image_url" type="url" value="{{ old('image_url') }}" placeholder="https://contoh.com/gambar.jpg" class="mt-2 block w-full rounded-lg border-slate-300 text-sm focus:border-cyan-500 focus:ring-cyan-500">
                        </div>

                        <button type="submit" class="inline-flex items-center rounded-lg bg-cyan-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-cyan-500">
                            Upload untuk Validasi
                        </button>
                    </form>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
