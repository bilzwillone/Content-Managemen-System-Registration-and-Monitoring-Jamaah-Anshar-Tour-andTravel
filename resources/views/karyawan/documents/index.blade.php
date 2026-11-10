
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dokumen Jamaah - Ansar Travel</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen overflow-x-hidden bg-gray-50 text-gray-800">

    <div class="flex min-h-screen">

        @include('partials.sidebar')

        <main class="min-w-0 flex-1">

            <div class="space-y-6 p-5 md:p-7">

                <section>
                    <h2 class="text-xl font-bold text-gray-900">
                        Dokumen Jamaah
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Kelola dan pantau kelengkapan dokumen persyaratan perjalanan Jamaah.
                    </p>
                </section>

                <section class="grid grid-cols-1 gap-4 sm:grid-cols-3">

                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                        <p class="text-sm font-medium text-gray-500">
                            Total Jamaah
                        </p>

                        <h3 class="mt-3 text-2xl font-bold text-gray-900">
                            {{ number_format($totalJamaahs) }}
                        </h3>
                    </div>

                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                        <p class="text-sm font-medium text-gray-500">
                            Dokumen Terunggah
                        </p>

                        <h3 class="mt-3 text-2xl font-bold text-blue-600">
                            {{ number_format($totalDocuments) }}
                        </h3>
                    </div>

                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                        <p class="text-sm font-medium text-gray-500">
                            Dokumen Terverifikasi
                        </p>

                        <h3 class="mt-3 text-2xl font-bold text-green-600">
                            {{ number_format($verifiedDocuments) }}
                        </h3>
                    </div>

                </section>


                <!-- DAFTAR DOKUMEN -->
                <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                    <!-- HEADER -->
                    <div class="border-b border-gray-200 px-5 py-4">
                        <h3 class="font-bold text-gray-900">
                            Daftar Kelengkapan Dokumen
                        </h3>

                        <p class="mt-1 text-xs text-gray-500">
                            Pilih Jamaah untuk melihat dan mengelola dokumen persyaratannya.
                        </p>
                    </div>


                    <!-- PENCARIAN -->
                    <form
                        method="GET"
                        action="{{ route('karyawan.documents.index') }}"
                        class="flex flex-col gap-3 border-b border-gray-100 bg-gray-50/70 p-4 sm:flex-row sm:items-center"
                    >

                        <div class="min-w-0 flex-1">
                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Cari nama, nomor telepon, atau email Jamaah..."
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm outline-none transition focus:border-[#D8B400] focus:ring-2 focus:ring-yellow-100"
                            >
                        </div>

                        <button
                            type="submit"
                            class="rounded-lg bg-[#171717] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-gray-800"
                        >
                            Cari Data
                        </button>

                        @if (request()->filled('search'))
                            <a
                                href="{{ route('karyawan.documents.index') }}"
                                class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-center text-sm font-medium text-gray-600 transition hover:bg-gray-100"
                            >
                                Reset
                            </a>
                        @endif

                    </form>


                    <!-- TABEL -->
                    <div class="overflow-x-auto">

                        <table class="w-full min-w-[850px] text-left text-sm">

                            <thead class="bg-gray-50 text-xs uppercase tracking-wider text-gray-500">
                                <tr>
                                    <th class="px-5 py-4 font-semibold">No.</th>
                                    <th class="px-5 py-4 font-semibold">Nama Jamaah</th>
                                    <th class="px-5 py-4 font-semibold">Paket Perjalanan</th>
                                    <th class="px-5 py-4 font-semibold">Dokumen Terunggah</th>
                                    <th class="px-5 py-4 font-semibold">Terverifikasi</th>
                                    <th class="px-5 py-4 font-semibold">Progres</th>
                                    <th class="px-5 py-4 text-center font-semibold">Aksi</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-100">

                                @forelse ($jamaahs as $index => $jamaah)

                                    @php
                                        $uploaded = (int) $jamaah->uploaded_documents_count;
                                        $verified = (int) $jamaah->verified_documents_count;

                                        $percentage = min(100, round(($verified / 6) * 100));

                                        if ($verified === 6) {
                                            $progressLabel = 'Lengkap';
                                            $progressClass = 'bg-green-500';
                                            $badgeClass = 'bg-green-50 text-green-700';
                                        } elseif ($uploaded === 0) {
                                            $progressLabel = 'Belum Diupload';
                                            $progressClass = 'bg-gray-400';
                                            $badgeClass = 'bg-gray-100 text-gray-600';
                                        } elseif ($uploaded > $verified) {
                                            $progressLabel = 'Menunggu Verifikasi';
                                            $progressClass = 'bg-amber-500';
                                            $badgeClass = 'bg-amber-50 text-amber-700';
                                        } else {
                                            $progressLabel = 'Dalam Proses';
                                            $progressClass = 'bg-blue-500';
                                            $badgeClass = 'bg-blue-50 text-blue-700';
                                        }
                                    @endphp

                                    <tr class="transition hover:bg-gray-50">

                                        <td class="px-5 py-4 text-gray-500">
                                            {{ $jamaahs->firstItem() + $index }}
                                        </td>

                                        <td class="px-5 py-4">
                                            <p class="font-semibold text-gray-900">
                                                {{ $jamaah->name }}
                                            </p>

                                            <p class="mt-1 text-xs text-gray-500">
                                                {{ $jamaah->phone }}
                                            </p>

                                            <p class="mt-1 text-xs text-gray-400">
                                                ID Jamaah: #{{ $jamaah->id }}
                                            </p>
                                        </td>

                                        <td class="px-5 py-4 text-gray-700">
                                            {{ $jamaah->package?->name ?? 'Belum dipilih' }}
                                        </td>

                                        <td class="px-5 py-4">
                                            <span class="font-semibold text-gray-800">
                                                {{ $uploaded }}/6
                                            </span>
                                            <span class="text-xs text-gray-400">
                                                dokumen
                                            </span>
                                        </td>

                                        <td class="px-5 py-4">
                                            <span class="font-semibold text-green-600">
                                                {{ $verified }}/6
                                            </span>
                                            <span class="text-xs text-gray-400">
                                                dokumen
                                            </span>
                                        </td>

                                        <td class="px-5 py-4">
                                            <div class="mb-2 flex items-center justify-between gap-3">
                                                <span class="text-xs font-medium text-gray-700">
                                                    {{ $progressLabel }}
                                                </span>

                                                <span class="text-xs text-gray-500">
                                                    {{ $percentage }}%
                                                </span>
                                            </div>

                                            <div class="h-2 w-32 overflow-hidden rounded-full bg-gray-100">
                                                <div
                                                    class="h-full rounded-full {{ $progressClass }}"
                                                    style="width: {{ $percentage }}%"
                                                ></div>
                                            </div>

                                            <span class="mt-1 inline-flex rounded-full px-2 py-0.5 text-[10px] font-semibold {{ $badgeClass }}">
                                                {{ $progressLabel }}
                                            </span>
                                        </td>

                                        <td class="px-5 py-4 text-center">
                                            <a
                                                href="{{ route('karyawan.documents.show', $jamaah) }}"
                                                class="inline-flex rounded-lg bg-[#FDD52E] px-4 py-2 text-xs font-semibold text-gray-900 transition hover:bg-yellow-400"
                                            >
                                                Kelola Dokumen
                                            </a>
                                        </td>

                                    </tr>

                                @empty

                                    <tr>
                                        <td colspan="7" class="px-5 py-14 text-center">

                                            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-400">

                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                     width="23" height="23" viewBox="0 0 24 24"
                                                     fill="none" stroke="currentColor"
                                                     stroke-width="1.7" stroke-linecap="round"
                                                     stroke-linejoin="round">
                                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                                    <path d="M14 2v6h6"/>
                                                    <path d="M8 13h8M8 17h8"/>
                                                </svg>

                                            </div>

                                            <p class="mt-3 font-semibold text-gray-800">
                                                Belum ada data Jamaah
                                            </p>

                                            <p class="mt-1 text-sm text-gray-500">
                                                Data dokumen akan tersedia setelah calon jamaah dikonversi menjadi Jamaah.
                                            </p>

                                        </td>
                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>


                    <!-- PAGINATION -->
                    @if ($jamaahs->hasPages())
                        <div class="border-t border-gray-100 px-5 py-4">
                            {{ $jamaahs->links() }}
                        </div>
                    @endif

                    <div class="border-t border-gray-100 px-5 py-3 text-xs text-gray-400">
                        Menampilkan {{ $jamaahs->count() }} dari {{ $jamaahs->total() }} Jamaah.
                    </div>

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
