<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dokumen Saya - Ansar Travel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50 font-sans antialiased">
    <div class="flex min-h-screen">
        @include('partials.sidebar')

        <main class="min-w-0 flex-1 p-5 lg:p-8">
            <div class="mx-auto max-w-7xl space-y-5">

                {{-- Header --}}
                <header class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">Data &amp; Dokumen Jamaah</h1>
                        <p class="mt-1 text-sm text-gray-500">Unggah dokumen persyaratan dan pantau hasil pemeriksaan Karyawan.</p>
                    </div>
                    <span class="rounded-full bg-white px-3 py-1.5 text-xs font-medium text-gray-600 ring-1 ring-gray-200">
                        Maksimal 5 MB per dokumen
                    </span>
                </header>

                {{-- Notifications --}}
                @if (session('success'))
                    <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        <p class="font-semibold">Dokumen belum dapat diproses.</p>
                        <ul class="mt-1 list-inside list-disc">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Summary --}}
                @php
                    $totalDocuments = $documents->count();
                    $uploadedDocuments = $documents->filter(fn ($item) => !empty($item->file_path))->count();
                    $verifiedDocuments = $documents->where('status', 'terverifikasi')->count();
                    $pendingDocuments = $documents->whereIn('status', ['menunggu_verifikasi', 'perlu_perbaikan'])->count();

                    $statusStyles = [
                        'belum_diupload' => 'bg-gray-100 text-gray-600',
                        'menunggu_verifikasi' => 'bg-amber-50 text-amber-700',
                        'terverifikasi' => 'bg-green-50 text-green-700',
                        'perlu_perbaikan' => 'bg-red-50 text-red-700',
                    ];

                    $statusLabels = [
                        'belum_diupload' => 'Belum Diunggah',
                        'menunggu_verifikasi' => 'Menunggu Verifikasi',
                        'terverifikasi' => 'Terverifikasi',
                        'perlu_perbaikan' => 'Perlu Perbaikan',
                    ];
                @endphp

                <section class="grid grid-cols-2 gap-3 lg:grid-cols-4">
                    <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                        <p class="text-xs text-gray-500">Total Dokumen</p>
                        <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $totalDocuments }}</p>
                    </div>
                    <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                        <p class="text-xs text-gray-500">Sudah Diunggah</p>
                        <p class="mt-1 text-2xl font-semibold text-blue-700">{{ $uploadedDocuments }}</p>
                    </div>
                    <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                        <p class="text-xs text-gray-500">Terverifikasi</p>
                        <p class="mt-1 text-2xl font-semibold text-green-700">{{ $verifiedDocuments }}</p>
                    </div>
                    <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                        <p class="text-xs text-gray-500">Perlu Ditindaklanjuti</p>
                        <p class="mt-1 text-2xl font-semibold text-amber-700">{{ $pendingDocuments }}</p>
                    </div>
                </section>

                {{-- Documents table --}}
                <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                    <div class="border-b border-gray-200 px-5 py-4">
                        <h2 class="text-sm font-semibold text-gray-900">Daftar Dokumen Persyaratan</h2>
                        <p class="mt-1 text-xs text-gray-500">Pilih file pada baris dokumen terkait, kemudian unggah atau ganti file.</p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[1050px] text-left text-sm">
                            <thead class="bg-gray-50 text-[11px] font-semibold uppercase tracking-wide text-gray-500">
                                <tr>
                                    <th class="w-12 px-4 py-3">No.</th>
                                    <th class="px-4 py-3">Jenis Dokumen</th>
                                    <th class="px-4 py-3">File Diunggah</th>
                                    <th class="px-4 py-3">Tanggal Unggah</th>
                                    <th class="px-4 py-3">Status</th>
                                    <th class="px-4 py-3">Catatan Karyawan</th>
                                    <th class="px-4 py-3">Unggah / Ganti</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-100">
                                @forelse ($documents as $index => $document)
                                    <tr class="align-top hover:bg-gray-50/70">
                                        <td class="px-4 py-4 text-gray-500">{{ $index + 1 }}</td>

                                        <td class="px-4 py-4">
                                            <p class="font-medium text-gray-900">
                                                {{ $document->document_name ?? ucfirst(str_replace('_', ' ', $document->document_type)) }}
                                            </p>
                                            <p class="mt-1 text-xs text-gray-400">{{ strtoupper(str_replace('_', ' ', $document->document_type)) }}</p>
                                        </td>

                                        <td class="px-4 py-4">
                                            @if ($document->original_name)
                                                <p class="max-w-[190px] truncate font-medium text-gray-700" title="{{ $document->original_name }}">
                                                    {{ $document->original_name }}
                                                </p>
                                                @if ($document->file_size)
                                                    <p class="mt-1 text-xs text-gray-400">{{ number_format($document->file_size / 1024, 1, ',', '.') }} KB</p>
                                                @endif
                                            @else
                                                <span class="text-xs text-gray-400">Belum ada file</span>
                                            @endif
                                        </td>

                                        <td class="whitespace-nowrap px-4 py-4 text-gray-600">
                                            {{ $document->uploaded_at?->format('d M Y, H:i') ?? '-' }}
                                        </td>

                                        <td class="px-4 py-4">
                                            <span class="inline-flex whitespace-nowrap rounded-full px-2.5 py-1 text-[11px] font-semibold {{ $statusStyles[$document->status] ?? 'bg-gray-100 text-gray-600' }}">
                                                {{ $statusLabels[$document->status] ?? ucfirst(str_replace('_', ' ', $document->status)) }}
                                            </span>
                                        </td>

                                        <td class="max-w-[190px] px-4 py-4">
                                            @if ($document->notes)
                                                <p class="text-xs leading-5 text-red-700">{{ $document->notes }}</p>
                                            @else
                                                <span class="text-xs text-gray-400">-</span>
                                            @endif
                                        </td>

                                        <td class="px-4 py-4">
                                            <form
                                                action="{{ route('jamaah.documents.upload', $document) }}"
                                                method="POST"
                                                enctype="multipart/form-data"
                                                class="flex min-w-[220px] flex-col gap-2"
                                            >
                                                @csrf
                                                <input
                                                    type="file"
                                                    name="document"
                                                    required
                                                    accept=".pdf,.jpg,.jpeg,.png"
                                                    class="block w-full rounded-md border border-gray-300 bg-white px-2 py-2 text-xs text-gray-600 file:mr-2 file:rounded file:border-0 file:bg-gray-100 file:px-2.5 file:py-1.5 file:text-xs file:font-medium file:text-gray-700 hover:file:bg-gray-200 focus:border-yellow-400 focus:outline-none focus:ring-2 focus:ring-yellow-100"
                                                >
                                                <button
                                                    type="submit"
                                                    class="inline-flex w-fit items-center justify-center rounded-md bg-yellow-400 px-3 py-2 text-xs font-semibold text-gray-900 transition hover:bg-yellow-500"
                                                >
                                                    {{ $document->file_path ? 'Ganti Dokumen' : 'Unggah Dokumen' }}
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-4 py-12 text-center">
                                            <p class="text-sm font-medium text-gray-700">Belum ada daftar dokumen.</p>
                                            <p class="mt-1 text-xs text-gray-400">Daftar dokumen persyaratan akan ditampilkan di sini.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="border-t border-gray-200 bg-gray-50 px-5 py-3">
                        <p class="text-xs text-gray-500">
                            Dokumen yang baru diunggah atau diganti akan berstatus Menunggu Verifikasi sampai diperiksa oleh Karyawan.
                        </p>
                    </div>
                </section>

            </div>
        </main>
    </div>
</body>
</html>
