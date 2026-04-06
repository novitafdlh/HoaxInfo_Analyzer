<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Validasi Informasi Publik
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid gap-6 lg:grid-cols-4">
                <div class="lg:col-span-1">
                    @include('admin.partials.sidebar')
                </div>

                <div class="space-y-6 lg:col-span-3">
                    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                        <p class="text-sm text-gray-600">
                            Dashboard ini digunakan untuk mengelola konten informasi resmi serta melakukan validasi terhadap konten visual yang dikirimkan oleh masyarakat.
                        </p>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                            <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Total Konten Resmi</p>
                            <p class="mt-3 text-3xl font-bold text-gray-900">{{ number_format($totalOfficialContent) }}</p>
                            <p class="mt-2 text-sm text-gray-600">Jumlah seluruh konten visual resmi yang telah tersimpan dalam sistem.</p>
                        </div>

                        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                            <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Total Submission Masyarakat</p>
                            <p class="mt-3 text-3xl font-bold text-gray-900">{{ number_format($totalSubmissions) }}</p>
                            <p class="mt-2 text-sm text-gray-600">Jumlah gambar yang telah dikirimkan masyarakat untuk diverifikasi.</p>
                        </div>

                        <div class="rounded-xl border border-amber-200 bg-amber-50 p-5 shadow-sm">
                            <p class="text-xs font-semibold uppercase tracking-wide text-amber-700">Menunggu Validasi</p>
                            <p class="mt-3 text-3xl font-bold text-amber-900">{{ number_format($pendingValidation) }}</p>
                            <p class="mt-2 text-sm text-amber-800">Submission yang masih menunggu keputusan final dari admin.</p>
                        </div>

                        <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-5 shadow-sm">
                            <p class="text-xs font-semibold uppercase tracking-wide text-emerald-700">Konten Terverifikasi</p>
                            <p class="mt-3 text-3xl font-bold text-emerald-900">{{ number_format($verifiedContent) }}</p>
                            <p class="mt-2 text-sm text-emerald-800">Submission yang telah divalidasi sebagai konten yang sesuai dengan informasi resmi.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
