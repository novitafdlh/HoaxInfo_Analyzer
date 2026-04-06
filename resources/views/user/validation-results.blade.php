<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Hasil Validasi Saya
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <p class="text-sm text-gray-600">
                    Halaman ini menampilkan hasil validasi untuk semua konten yang Anda kirimkan.
                </p>
            </div>

            <div class="mt-6 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">Gambar</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">Similarity</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">Status Sistem</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">Status Final</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse ($submissions as $submission)
                                <tr>
                                    <td class="px-4 py-3">
                                        <img src="{{ asset('storage/'.$submission->image_path) }}" alt="Submission" class="h-16 w-24 rounded-md border border-gray-200 object-cover">
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        {{ $submission->similarity_score !== null ? $submission->similarity_score.'%' : '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $submission->system_status ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        @if ($submission->final_status === 'terverifikasi')
                                            <span class="rounded-full bg-emerald-100 px-2 py-1 text-xs font-semibold text-emerald-700">Terverifikasi</span>
                                        @elseif ($submission->final_status === 'tidak_valid')
                                            <span class="rounded-full bg-rose-100 px-2 py-1 text-xs font-semibold text-rose-700">Tidak Valid</span>
                                        @else
                                            <span class="rounded-full bg-amber-100 px-2 py-1 text-xs font-semibold text-amber-700">Menunggu Validasi</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $submission->created_at?->format('d M Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-6 text-center text-sm text-gray-500">
                                        Belum ada hasil validasi untuk akun Anda.
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
</x-app-layout>
