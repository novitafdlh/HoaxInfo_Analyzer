<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Verifikasi Konten dari Masyarakat
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
                            Halaman ini menampilkan gambar yang dikirimkan masyarakat untuk dilakukan proses verifikasi terhadap konten resmi yang tersedia dalam sistem.
                        </p>
                    </div>

                    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">Gambar</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">Similarity Score</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">Status Sistem</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">Status Final</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 bg-white">
                                    @forelse ($submissions as $submission)
                                        <tr>
                                            <td class="px-4 py-3">
                                                <img src="{{ asset('storage/'.$submission->image_path) }}" alt="Submission" class="h-16 w-20 rounded-md object-cover border border-gray-200">
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-700">
                                                {{ $submission->similarity_score !== null ? $submission->similarity_score.'%' : '-' }}
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-700">{{ $submission->system_status ?? '-' }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-700">{{ $submission->final_status }}</td>
                                            <td class="px-4 py-3 text-sm">
                                                <a href="{{ route('admin.submissions.show', $submission) }}" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-indigo-500">
                                                    Detail / Validasi
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-4 py-6 text-center text-sm text-gray-500">
                                                Belum ada submission masyarakat.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="border-t border-gray-200 px-4 py-3">
                            {{ $submissions->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
