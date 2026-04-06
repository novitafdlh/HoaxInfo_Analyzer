<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Official Content
            </h2>
            <a href="{{ route('official.create') }}" class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                Tambah Konten Resmi
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid gap-6 lg:grid-cols-4">
                <div class="lg:col-span-1">
                    @include('admin.partials.sidebar')
                </div>

                <div class="space-y-4 lg:col-span-3">
                    @if (session('success'))
                        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                        <p class="text-sm text-gray-600">
                            Seluruh konten resmi yang telah diunggah ditampilkan berkelompok berdasarkan kategori untuk memudahkan pengelolaan.
                        </p>
                    </div>

                    @forelse ($officialContentsByCategory as $category => $contents)
                        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                            <div class="border-b border-gray-200 bg-gray-50 px-4 py-3">
                                <p class="text-sm font-semibold text-gray-800">
                                    Kategori: {{ $category }}
                                </p>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-white">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">Gambar</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">Judul</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">Sumber</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">Tanggal Upload</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 bg-white">
                                        @foreach ($contents as $content)
                                            <tr>
                                                <td class="px-4 py-3">
                                                    <img src="{{ asset('storage/'.$content->image_path) }}" alt="Official Content" class="h-16 w-24 rounded-md border border-gray-200 object-cover">
                                                </td>
                                                <td class="px-4 py-3 text-sm text-gray-700">{{ $content->title }}</td>
                                                <td class="px-4 py-3 text-sm text-gray-700">
                                                    {{ $content->source_type === 'url' ? 'URL' : 'Manual Upload' }}
                                                </td>
                                                <td class="px-4 py-3 text-sm text-gray-700">{{ $content->created_at?->format('d M Y H:i') }}</td>
                                                <td class="px-4 py-3 text-sm text-gray-700">
                                                    <form method="POST" action="{{ route('official.destroy', $content) }}" onsubmit="return confirm('Hapus konten resmi ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="inline-flex items-center rounded-md bg-red-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-red-500">
                                                            Hapus
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @empty
                        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                            <div class="px-4 py-6 text-center text-sm text-gray-500">
                                Belum ada konten resmi yang diunggah.
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
