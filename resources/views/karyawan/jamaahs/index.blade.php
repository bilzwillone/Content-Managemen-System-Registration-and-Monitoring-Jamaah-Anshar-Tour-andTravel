
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Data Jamaah - Ansar Travel</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen overflow-x-hidden bg-gray-50 text-gray-800">

    <div class="flex min-h-screen">

        @include('partials.sidebar')

        <main class="min-w-0 flex-1">

            <div class="space-y-6 p-5 md:p-7">

                <section>
                    <h2 class="text-xl font-bold text-gray-900">
                        Data Jamaah
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Kelola data jamaah yang telah terdaftar di Ansar Travel.
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
                            Sudah Memiliki Paket
                        </p>

                        <h3 class="mt-3 text-2xl font-bold text-green-600">
                            {{ number_format($jamaahsWithPackage) }}
                        </h3>
                    </div>

                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                        <p class="text-sm font-medium text-gray-500">
                            Belum Memiliki Paket
                        </p>

                        <h3 class="mt-3 text-2xl font-bold text-amber-600">
                            {{ number_format($jamaahsWithoutPackage) }}
                        </h3>
                    </div>

                </section>

                <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                    <!-- HEADER -->
                    <div class="border-b border-gray-200 px-5 py-4">
                        <h3 class="font-bold text-gray-900">
                            Daftar Jamaah
                        </h3>

                        <p class="mt-1 text-xs text-gray-500">
                            Data jamaah hasil pendaftaran dan konversi CRM.
                        </p>
                    </div>

                    <form
                        method="GET"
                        action="{{ route('karyawan.jamaahs.index') }}"
                        class="flex flex-col gap-3 border-b border-gray-100 bg-gray-50/70 p-4 lg:flex-row lg:items-center"
                    >

                        <div class="min-w-0 flex-1">
                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Cari nama, nomor telepon, atau email..."
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm outline-none transition focus:border-[#D8B400] focus:ring-2 focus:ring-yellow-100"
                            >
                        </div>

                        <select
                            name="status"
                            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm outline-none focus:border-[#D8B400] lg:w-48"
                        >
                            <option value="">Semua Status</option>
                            <option value="terdaftar" @selected(request('status') === 'terdaftar')>
                                Terdaftar
                            </option>
                        </select>

                        <button
                            type="submit"
                            class="rounded-lg bg-[#171717] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-gray-800"
                        >
                            Cari Data
                        </button>

                        @if (request()->filled('search') || request()->filled('status'))
                            <a
                                href="{{ route('karyawan.jamaahs.index') }}"
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
                                    <th class="px-5 py-4 font-semibold">Kontak</th>
                                    <th class="px-5 py-4 font-semibold">Paket Perjalanan</th>
                                    <th class="px-5 py-4 font-semibold">Tanggal Pendaftaran</th>
                                    <th class="px-5 py-4 font-semibold">Status</th>
                                    <th class="px-5 py-4 text-center font-semibold">Asal Data</th>
                                    <th class="px-5 py-4 text-center font-semibold">Aksi</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-100">

                                @forelse ($jamaahs as $index => $jamaah)

                                    <tr class="transition hover:bg-gray-50">

                                        <td class="px-5 py-4 text-gray-500">
                                            {{ $jamaahs->firstItem() + $index }}
                                        </td>

                                        <td class="px-5 py-4">
                                            <p class="font-semibold text-gray-900">
                                                {{ $jamaah->name }}
                                            </p>

                                            <p class="mt-1 text-xs text-gray-400">
                                                ID Jamaah: #{{ $jamaah->id }}
                                            </p>
                                        </td>

                                        <td class="px-5 py-4">
                                            <p class="text-gray-700">
                                                {{ $jamaah->phone }}
                                            </p>

                                            <p class="mt-1 text-xs text-gray-400">
                                                {{ $jamaah->email ?: 'Email belum diisi' }}
                                            </p>
                                        </td>

                                        <td class="px-5 py-4 text-gray-700">
                                            {{ $jamaah->package?->name ?? 'Belum dipilih' }}
                                        </td>

                                        <td class="px-5 py-4 text-gray-600">
                                            {{ $jamaah->registration_date?->format('d M Y') ?? '-' }}
                                        </td>

                                        <td class="px-5 py-4">
                                            <span class="inline-flex rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">
                                                {{ ucfirst(str_replace('_', ' ', $jamaah->status)) }}
                                            </span>
                                        </td>

                                        <td class="px-5 py-4 text-center">
                                            @if ($jamaah->prospect)
                                                <a
                                                    href="{{ route('karyawan.prospects.show', $jamaah->prospect) }}"
                                                    class="text-xs font-medium text-blue-600 transition hover:text-blue-800 hover:underline"
                                                >
                                                    Lihat CRM
                                                </a>
                                            @else
                                                <span class="text-xs text-gray-400">-</span>
                                            @endif
                                        </td>

                                        <!-- KOLOM AKSI -->
                                        <td class="px-5 py-4">
                                            <div class="flex items-center justify-center gap-2 whitespace-nowrap">

                                                <a
                                                    href="{{ route('karyawan.jamaahs.show', $jamaah) }}"
                                                    class="rounded-md border border-gray-200 px-3 py-1.5 text-xs font-medium text-gray-600 transition hover:border-blue-300 hover:bg-blue-50 hover:text-blue-700"
                                                >
                                                    Detail
                                                </a>

                                                <a
                                                    href="{{ route('karyawan.jamaahs.edit', $jamaah) }}"
                                                    class="rounded-md border border-gray-200 px-3 py-1.5 text-xs font-medium text-gray-600 transition hover:border-amber-300 hover:bg-amber-50 hover:text-amber-700"
                                                >
                                                    Edit
                                                </a>
                                            </div>
                                        </td>

                                    </tr>

                                @empty

                                    <tr>
                                        <td colspan="8" class="px-5 py-14 text-center">

                                            <p class="font-semibold text-gray-800">
                                                Belum ada data Jamaah
                                            </p>

                                            <p class="mt-1 text-sm text-gray-500">
                                                Data Jamaah akan muncul setelah calon jamaah dikonversi melalui CRM.
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
                        Menampilkan {{ $jamaahs->count() }} dari {{ $jamaahs->total() }} data Jamaah.
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
