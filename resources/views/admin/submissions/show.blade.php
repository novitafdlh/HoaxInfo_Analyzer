<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Analisis Konten
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid gap-6 lg:grid-cols-4">
                <div class="lg:col-span-1">
                    @include('admin.partials.sidebar')
                </div>

                <div class="space-y-4 lg:col-span-3">
                    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                        <p class="text-sm text-gray-600">
                            Halaman ini menampilkan hasil analisis sistem terhadap gambar yang diunggah oleh masyarakat.
                        </p>
                    </div>

                    @if (session('status'))
                        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                        <div class="grid gap-6 md:grid-cols-2">
                            <div>
                                <p class="text-sm font-medium text-gray-700">Gambar Submission</p>
                                <img src="{{ asset('storage/'.$submission->image_path) }}" alt="Submission" class="mt-2 w-full rounded-lg border border-gray-200 object-cover">
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Hash Gambar</p>
                                    <p class="mt-1 break-all rounded-md bg-gray-50 px-3 py-2 text-sm text-gray-700">{{ $submission->image_hash }}</p>
                                </div>

                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Teks Hasil OCR</p>
                                    <p class="mt-1 rounded-md bg-gray-50 px-3 py-2 text-sm text-gray-700">{{ $submission->extracted_text ?: '-' }}</p>
                                </div>

                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Similarity Score</p>
                                    <p class="mt-1 rounded-md bg-gray-50 px-3 py-2 text-sm text-gray-700">
                                        {{ $submission->similarity_score !== null ? $submission->similarity_score.'%' : '-' }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Status Sistem</p>
                                    <p class="mt-1 rounded-md bg-gray-50 px-3 py-2 text-sm text-gray-700">{{ $submission->system_status ?? '-' }}</p>
                                </div>

                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Status Final</p>
                                    <p class="mt-1 rounded-md bg-gray-50 px-3 py-2 text-sm text-gray-700">{{ $submission->final_status }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 flex flex-wrap gap-3">
                            <form method="POST" action="{{ route('admin.submissions.update-status', $submission) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="final_status" value="terverifikasi">
                                <button type="submit" class="inline-flex items-center rounded-md bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-500">
                                    Valid
                                </button>
                            </form>

                            <form method="POST" action="{{ route('admin.submissions.update-status', $submission) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="final_status" value="tidak_valid">
                                <button type="submit" class="inline-flex items-center rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-500">
                                    Tidak Valid
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
