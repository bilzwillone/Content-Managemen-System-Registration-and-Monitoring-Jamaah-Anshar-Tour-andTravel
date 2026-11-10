
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Checklist Dokumen Jamaah - Ansar Travel</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen overflow-x-hidden bg-gray-50 text-gray-800">

    @php
        $documentLabels = [
            'ktp' => 'KTP',
            'kartu_keluarga' => 'Kartu Keluarga (KK)',
            'pas_foto' => 'Pas Foto',
            'paspor' => 'Paspor',
            'vaksin_meningitis' => 'Surat Vaksin Meningitis',
            'surat_polio' => 'Surat Polio',
        ];

        $statusStyles = [
            'belum_diupload' => [
                'label' => 'Belum Diupload',
                'class' => 'bg-gray-100 text-gray-600',
            ],
            'menunggu_verifikasi' => [
                'label' => 'Menunggu Verifikasi',
                'class' => 'bg-amber-50 text-amber-700',
            ],
            'terverifikasi' => [
                'label' => 'Terverifikasi',
                'class' => 'bg-green-50 text-green-700',
            ],
            'perlu_perbaikan' => [
                'label' => 'Perlu Perbaikan',
                'class' => 'bg-red-50 text-red-700',
            ],
        ];

        $verifiedCount = $documents->where('status', 'terverifikasi')->count();
        $uploadedCount = $documents->whereNotNull('file_path')->count();
        $totalDocuments = 6;
        $progress = round(($verifiedCount / $totalDocuments) * 100);
    @endphp

    <div class="flex min-h-screen">

        @include('partials.sidebar')

        <!-- KONTEN UTAMA -->
        <main class="min-w-0 flex-1">

            <div class="space-y-5 p-5 md:p-7">

                <!-- JUDUL -->
                <section class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">

                    <div>
                        <h2 class="text-xl font-bold text-gray-900">
                            Checklist Dokumen Jamaah
                        </h2>

                        <p class="mt-1 text-sm text-gray-500">
                            Kelola kelengkapan dan verifikasi dokumen perjalanan.
                        </p>
                    </div>

                    <a
                        href="{{ route('karyawan.documents.index') }}"
                        class="inline-flex w-fit shrink-0 items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-600 transition hover:bg-gray-100"
                    >
                        Kembali
                    </a>

                </section>


                <!-- NOTIFIKASI -->
                @if (session('success'))
                    <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="rounded-lg border border-red-200 bg-red-50 p-4">
                        <p class="text-sm font-semibold text-red-700">
                            Terdapat kesalahan:
                        </p>

                        <ul class="mt-2 list-inside list-disc space-y-1 text-sm text-red-600">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif


                <!-- IDENTITAS JAMAAH -->
                <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                    <div class="flex flex-col justify-between gap-4 p-5 sm:flex-row sm:items-center">

                        <div>
                            <p class="text-xs font-medium text-gray-400">
                                INFORMASI JAMAAH
                            </p>

                            <h3 class="mt-2 text-lg font-bold text-gray-900">
                                {{ $jamaah->name }}
                            </h3>

                            <div class="mt-2 space-y-1 text-sm text-gray-500">
                                <p>
                                    Nomor HP:
                                    <span class="font-medium text-gray-700">
                                        {{ $jamaah->phone }}
                                    </span>
                                </p>

                                <p>
                                    Paket:
                                    <span class="font-medium text-gray-700">
                                        {{ $jamaah->package?->name ?? 'Belum dipilih' }}
                                    </span>
                                </p>

                                <p>
                                    ID Jamaah:
                                    <span class="font-medium text-gray-700">
                                        #{{ $jamaah->id }}
                                    </span>
                                </p>
                            </div>
                        </div>

                        <div class="rounded-lg bg-gray-50 p-4 sm:min-w-52">
                            <p class="text-xs text-gray-500">
                                Progres Verifikasi
                            </p>

                            <div class="mt-2 flex items-end justify-between gap-3">
                                <h4 class="text-2xl font-bold text-gray-900">
                                    {{ $verifiedCount }}/{{ $totalDocuments }}
                                </h4>

                                <span class="text-sm font-semibold text-green-600">
                                    {{ $progress }}%
                                </span>
                            </div>

                            <div class="mt-3 h-2 overflow-hidden rounded-full bg-gray-200">
                                <div
                                    class="h-full rounded-full bg-green-500 transition-all"
                                    style="width: {{ $progress }}%"
                                ></div>
                            </div>

                            <p class="mt-2 text-xs text-gray-400">
                                {{ $uploadedCount }} dokumen telah diunggah
                            </p>
                        </div>

                    </div>

                </section>


                <!-- CHECKLIST DOKUMEN -->
                <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                    <div class="border-b border-gray-200 px-5 py-4">
                        <h3 class="font-bold text-gray-900">
                            Persyaratan Dokumen
                        </h3>

                        <p class="mt-1 text-xs text-gray-500">
                            Unggah dan verifikasi enam dokumen wajib perjalanan.
                        </p>
                    </div>


                    <div class="divide-y divide-gray-100">

                        @foreach ($documents as $document)

                            @php
                                $statusInfo = $statusStyles[$document->status] ?? [
                                    'label' => ucfirst($document->status),
                                    'class' => 'bg-gray-100 text-gray-600',
                                ];

                                $label = $documentLabels[$document->document_type]
                                    ?? $document->document_name
                                    ?? ucfirst($document->document_type);
                            @endphp

                            <div class="space-y-4 p-5">

                                <!-- INFORMASI DOKUMEN -->
                                <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">

                                    <div class="flex items-center gap-3">

                                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-gray-600">

                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 width="21" height="21" viewBox="0 0 24 24"
                                                 fill="none" stroke="currentColor"
                                                 stroke-width="1.7" stroke-linecap="round"
                                                 stroke-linejoin="round">
                                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                                <path d="M14 2v6h6M8 13h8M8 17h8"/>
                                            </svg>

                                        </div>

                                        <div>
                                            <h4 class="text-sm font-bold text-gray-900">
                                                {{ $label }}
                                            </h4>

                                            <p class="mt-1 text-xs text-gray-400">
                                                @if ($document->uploaded_at)
                                                    Diunggah:
                                                    {{ $document->uploaded_at->format('d M Y, H:i') }}
                                                @else
                                                    Belum ada berkas yang diunggah.
                                                @endif
                                            </p>
                                        </div>

                                    </div>

                                    <span class="inline-flex w-fit rounded-full px-3 py-1.5 text-xs font-semibold {{ $statusInfo['class'] }}">
                                        {{ $statusInfo['label'] }}
                                    </span>

                                </div>


                                <!-- INFORMASI BERKAS -->
                                @if ($document->file_path)
                                    <div class="flex flex-col justify-between gap-3 rounded-lg border border-gray-200 bg-gray-50 p-3 sm:flex-row sm:items-center">

                                        <div class="min-w-0">
                                            <p class="truncate text-sm font-medium text-gray-800">
                                                {{ $document->original_name }}
                                            </p>

                                            <p class="mt-1 text-xs text-gray-400">
                                                {{ $document->mime_type ?? 'File' }}
                                                @if ($document->file_size)
                                                    &middot;
                                                    {{ number_format($document->file_size / 1024, 0) }} KB
                                                @endif
                                            </p>
                                        </div>

                                        <a
                                            href="{{ route('karyawan.documents.download', [$jamaah, $document]) }}"
                                            class="shrink-0 rounded-lg border border-gray-300 bg-white px-3 py-2 text-center text-xs font-semibold text-gray-700 transition hover:bg-gray-100"
                                        >
                                            Unduh Berkas
                                        </a>

                                    </div>
                                @endif


                                <!-- CATATAN PERBAIKAN -->
                                @if ($document->status === 'perlu_perbaikan' && $document->notes)
                                    <div class="rounded-lg border border-red-100 bg-red-50 p-3">
                                        <p class="text-xs font-semibold text-red-700">
                                            Alasan Perbaikan
                                        </p>

                                        <p class="mt-1 whitespace-pre-line text-sm text-red-700">
                                            {{ $document->notes }}
                                        </p>
                                    </div>
                                @endif


                                <!-- FORM UPLOAD -->
                                <form
                                    method="POST"
                                    action="{{ route('karyawan.documents.upload', [$jamaah, $document]) }}"
                                    enctype="multipart/form-data"
                                    class="flex flex-col gap-3 rounded-lg border border-gray-100 p-3 sm:flex-row sm:items-end"
                                >
                                    @csrf

                                    <div class="min-w-0 flex-1">
                                        <label
                                            for="file_{{ $document->id }}"
                                            class="mb-2 block text-xs font-semibold text-gray-700"
                                        >
                                            {{ $document->file_path ? 'Ganti Berkas' : 'Upload Berkas' }}
                                        </label>

                                        <input
                                            type="file"
                                            id="file_{{ $document->id }}"
                                            name="file"
                                            accept=".pdf,.jpg,.jpeg,.png"
                                            required
                                            class="block w-full text-xs text-gray-600 file:mr-3 file:rounded-lg file:border-0 file:bg-gray-100 file:px-3 file:py-2 file:text-xs file:font-semibold file:text-gray-700 hover:file:bg-gray-200"
                                        >

                                        <p class="mt-1 text-[11px] text-gray-400">
                                            PDF, JPG, JPEG, atau PNG. Maksimal 5 MB.
                                        </p>
                                    </div>

                                    <button
                                        type="submit"
                                        class="shrink-0 rounded-lg bg-[#FDD52E] px-5 py-2.5 text-xs font-bold text-gray-900 transition hover:bg-yellow-400"
                                    >
                                        {{ $document->file_path ? 'Ganti Dokumen' : 'Upload Dokumen' }}
                                    </button>

                                </form>


                                <!-- FORM VERIFIKASI -->
                                @if ($document->file_path)

                                    <form
                                        method="POST"
                                        action="{{ route('karyawan.documents.verify', [$jamaah, $document]) }}"
                                        class="space-y-3 rounded-lg bg-gray-50 p-3"
                                    >
                                        @csrf
                                        @method('PATCH')

                                        <div>
                                            <label
                                                for="notes_{{ $document->id }}"
                                                class="mb-2 block text-xs font-semibold text-gray-700"
                                            >
                                                Catatan Verifikasi
                                            </label>

                                            <textarea
                                                id="notes_{{ $document->id }}"
                                                name="notes"
                                                rows="2"
                                                placeholder="Isi alasan apabila dokumen perlu diperbaiki..."
                                                class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm outline-none transition focus:border-[#D8B400] focus:ring-2 focus:ring-yellow-100"
                                            >{{ old('notes') }}</textarea>
                                        </div>

                                        <div class="flex flex-col gap-2 sm:flex-row sm:justify-end">

                                            <button
                                                type="submit"
                                                name="status"
                                                value="perlu_perbaikan"
                                                onclick="return confirm('Minta Jamaah memperbaiki dokumen ini?')"
                                                class="rounded-lg border border-red-200 bg-white px-4 py-2.5 text-xs font-semibold text-red-600 transition hover:bg-red-50"
                                            >
                                                Perlu Perbaikan
                                            </button>

                                            <button
                                                type="submit"
                                                name="status"
                                                value="terverifikasi"
                                                onclick="return confirm('Verifikasi dokumen ini?')"
                                                class="rounded-lg bg-green-600 px-4 py-2.5 text-xs font-semibold text-white transition hover:bg-green-700"
                                            >
                                                Verifikasi Dokumen
                                            </button>

                                        </div>

                                    </form>

                                @endif


                                <!-- INFORMASI VERIFIKATOR -->
                                @if ($document->verified_at)
                                    <p class="text-xs text-gray-400">
                                        Terakhir diperiksa oleh
                                        <span class="font-medium text-gray-600">
                                            {{ $document->verifier?->name ?? 'Pengguna tidak diketahui' }}
                                        </span>
                                        pada
                                        {{ $document->verified_at->format('d M Y, H:i') }}
                                    </p>
                                @endif

                            </div>

                        @endforeach

                    </div>

                </section>


                <!-- INFORMASI NOMOR HP -->
                <section class="rounded-xl border border-blue-100 bg-blue-50 p-4">
                    <p class="text-sm font-semibold text-blue-800">
                        Informasi Kontak Jamaah
                    </p>

                    <p class="mt-1 text-sm text-blue-700">
                        Nomor HP yang terdaftar:
                        <span class="font-semibold">{{ $jamaah->phone }}</span>
                    </p>

                    <p class="mt-1 text-xs text-blue-600">
                        Nomor HP disimpan sebagai data identitas Jamaah, bukan sebagai berkas upload.
                    </p>
                </section>


                <!-- FOOTER -->
                <footer class="border-t border-gray-200 pt-5 text-center text-xs text-gray-400">
                    &copy; {{ date('Y') }} Ansar Travel.
                </footer>

            </div>

        </main>

    </div>

</body>
</html>
