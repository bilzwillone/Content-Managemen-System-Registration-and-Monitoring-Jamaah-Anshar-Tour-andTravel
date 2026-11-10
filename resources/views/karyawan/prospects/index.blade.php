
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Calon Jamaah & CRM - Ansar Travel</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen overflow-x-hidden bg-gray-50 text-gray-800">

    <div class="flex min-h-screen">

        @include('partials.sidebar')

        <main class="min-w-0 flex-1">

            <div class="space-y-6 p-5 md:p-8">

                <section class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">

                    <div>
                        <h2 class="text-xl font-bold text-gray-900">
                            Daftar Calon Jamaah
                        </h2>
                        <p class="mt-1 text-sm text-gray-500">
                            Kelola data prospek dan tindak lanjut calon jamaah.
                        </p>
                    </div>

                    <a
                        href="{{ route('karyawan.prospects.create') }}"
                        class="inline-flex shrink-0 items-center justify-center gap-2 rounded-lg bg-[#FDD52E] px-5 py-3 text-sm font-semibold text-gray-900 transition hover:bg-yellow-400"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg"
                             width="18" height="18" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round">
                            <path d="M12 5v14M5 12h14"/>
                        </svg>
                        Tambah Calon Jamaah
                    </a>

                </section>


                <!-- NOTIFIKASI -->
                @if (session('success'))
                    <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        {{ session('error') }}
                    </div>
                @endif


                <!-- RINGKASAN -->
                <section class="grid grid-cols-2 gap-4 xl:grid-cols-4">

                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                        <p class="text-xs font-medium text-gray-500">
                            Total Prospek
                        </p>
                        <h3 class="mt-3 text-2xl font-bold text-gray-900">
                            {{ number_format($totalProspects) }}
                        </h3>
                        <p class="mt-1 text-xs text-gray-400">
                            Seluruh calon jamaah
                        </p>
                    </div>

                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                        <p class="text-xs font-medium text-gray-500">
                            Prospek Baru
                        </p>
                        <h3 class="mt-3 text-2xl font-bold text-blue-600">
                            {{ number_format($newProspects) }}
                        </h3>
                        <p class="mt-1 text-xs text-gray-400">
                            Belum diproses lebih lanjut
                        </p>
                    </div>

                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                        <p class="text-xs font-medium text-gray-500">
                            Tertarik
                        </p>
                        <h3 class="mt-3 text-2xl font-bold text-amber-600">
                            {{ number_format($interestedProspects) }}
                        </h3>
                        <p class="mt-1 text-xs text-gray-400">
                            Menunjukkan minat pada paket
                        </p>
                    </div>

                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                        <p class="text-xs font-medium text-gray-500">
                            Sudah Mendaftar
                        </p>
                        <h3 class="mt-3 text-2xl font-bold text-green-600">
                            {{ number_format($registeredProspects) }}
                        </h3>
                        <p class="mt-1 text-xs text-gray-400">
                            Prospek masuk pendaftaran
                        </p>
                    </div>

                </section>


                <!-- TABEL CALON JAMAAH -->
                <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                    <!-- HEADER TABEL -->
                    <div class="border-b border-gray-200 px-5 py-4">
                        <h3 class="font-bold text-gray-900">
                            Data Calon Jamaah
                        </h3>
                        <p class="mt-1 text-xs text-gray-500">
                            Daftar calon jamaah yang tercatat dalam sistem.
                        </p>
                    </div>


                    <!-- PENCARIAN DAN FILTER -->
                    <form
                        method="GET"
                        action="{{ route('karyawan.prospects.index') }}"
                        class="flex flex-col gap-3 border-b border-gray-100 bg-gray-50/70 p-4 lg:flex-row lg:items-center"
                    >

                        <div class="relative min-w-0 flex-1">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 width="18" height="18" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor"
                                 stroke-width="1.8" stroke-linecap="round"
                                 stroke-linejoin="round"
                                 class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                <circle cx="11" cy="11" r="8"/>
                                <path d="m21 21-4.35-4.35"/>
                            </svg>

                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Cari nama, nomor telepon, atau email..."
                                class="w-full rounded-lg border border-gray-300 bg-white py-2.5 pl-10 pr-4 text-sm outline-none transition focus:border-[#D8B400] focus:ring-2 focus:ring-yellow-100"
                            >
                        </div>

                        <select
                            name="status"
                            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm outline-none focus:border-[#D8B400] lg:w-48"
                        >
                            <option value="">Semua Status</option>
                            <option value="baru" @selected(request('status') === 'baru')>
                                Baru
                            </option>
                            <option value="dihubungi" @selected(request('status') === 'dihubungi')>
                                Dihubungi
                            </option>
                            <option value="follow_up" @selected(request('status') === 'follow_up')>
                                Follow-up
                            </option>
                            <option value="tertarik" @selected(request('status') === 'tertarik')>
                                Tertarik
                            </option>
                            <option value="mendaftar" @selected(request('status') === 'mendaftar')>
                                Mendaftar
                            </option>
                            <option value="tidak_tertarik" @selected(request('status') === 'tidak_tertarik')>
                                Tidak Tertarik
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
                                href="{{ route('karyawan.prospects.index') }}"
                                class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-center text-sm font-medium text-gray-600 transition hover:bg-gray-100"
                            >
                                Reset
                            </a>
                        @endif

                    </form>


                    <!-- TABEL -->
                    <div class="overflow-x-auto">

                        <table class="w-full min-w-[1050px] text-left text-sm">

                            <thead class="bg-gray-50 text-xs uppercase tracking-wider text-gray-500">
                                <tr>
                                    <th class="px-5 py-4 font-semibold">No.</th>
                                    <th class="px-5 py-4 font-semibold">Calon Jamaah</th>
                                    <th class="px-5 py-4 font-semibold">Paket Diminati</th>
                                    <th class="px-5 py-4 font-semibold">PIC</th>
                                    <th class="px-5 py-4 font-semibold">Status</th>
                                    <th class="px-5 py-4 font-semibold">Follow-up Berikutnya</th>
                                    <th class="px-5 py-4 text-center font-semibold">Aktivitas</th>
                                    <th class="px-5 py-4 text-center font-semibold">Aksi</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-100">

                                @forelse ($prospects as $index => $prospect)

                                    @php
                                        $statusStyles = [
                                            'baru' => [
                                                'label' => 'Baru',
                                                'class' => 'bg-blue-50 text-blue-700'
                                            ],
                                            'dihubungi' => [
                                                'label' => 'Dihubungi',
                                                'class' => 'bg-indigo-50 text-indigo-700'
                                            ],
                                            'follow_up' => [
                                                'label' => 'Follow-up',
                                                'class' => 'bg-amber-50 text-amber-700'
                                            ],
                                            'tertarik' => [
                                                'label' => 'Tertarik',
                                                'class' => 'bg-green-50 text-green-700'
                                            ],
                                            'mendaftar' => [
                                                'label' => 'Mendaftar',
                                                'class' => 'bg-purple-50 text-purple-700'
                                            ],
                                            'tidak_tertarik' => [
                                                'label' => 'Tidak Tertarik',
                                                'class' => 'bg-red-50 text-red-700'
                                            ],
                                        ];

                                        $statusInfo = $statusStyles[$prospect->status] ?? [
                                            'label' => ucfirst($prospect->status),
                                            'class' => 'bg-gray-100 text-gray-700'
                                        ];
                                    @endphp

                                    <tr class="transition hover:bg-gray-50">

                                        <td class="px-5 py-4 text-gray-500">
                                            {{ $prospects->firstItem() + $index }}
                                        </td>

                                        <td class="px-5 py-4">
                                            <p class="font-semibold text-gray-900">
                                                {{ $prospect->name }}
                                            </p>

                                            <p class="mt-1 text-xs text-gray-500">
                                                {{ $prospect->phone }}
                                            </p>

                                            @if ($prospect->email)
                                                <p class="mt-1 text-xs text-gray-400">
                                                    {{ $prospect->email }}
                                                </p>
                                            @endif
                                        </td>

                                        <td class="px-5 py-4">
                                            <span class="text-gray-700">
                                                {{ $prospect->package?->name ?? 'Belum dipilih' }}
                                            </span>
                                        </td>

                                        <td class="px-5 py-4 text-gray-600">
                                            {{ $prospect->pic?->name ?? 'Belum ditentukan' }}
                                        </td>

                                        <td class="px-5 py-4">
                                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusInfo['class'] }}">
                                                {{ $statusInfo['label'] }}
                                            </span>
                                        </td>

                                        <td class="px-5 py-4 text-gray-600">
                                            @if ($prospect->next_follow_up_at)
                                                {{ $prospect->next_follow_up_at->format('d M Y, H:i') }}
                                            @else
                                                <span class="text-gray-400">Belum dijadwalkan</span>
                                            @endif
                                        </td>

                                        <td class="px-5 py-4 text-center">
                                            <span class="inline-flex min-w-8 justify-center rounded-md bg-gray-100 px-2 py-1 text-xs font-semibold text-gray-700">
                                                {{ $prospect->follow_ups_count }}
                                            </span>
                                        </td>

                                        <td class="px-5 py-4">
                                            <div class="flex items-center justify-center gap-2">

                                                <a
                                                    href="{{ route('karyawan.prospects.show', $prospect) }}"
                                                    class="rounded-md border border-gray-200 px-3 py-1.5 text-xs font-medium text-gray-600 transition hover:border-blue-300 hover:bg-blue-50 hover:text-blue-700"
                                                >
                                                    Detail
                                                </a>

                                                <a
                                                    href="{{ route('karyawan.prospects.edit', $prospect) }}"
                                                    class="rounded-md border border-gray-200 px-3 py-1.5 text-xs font-medium text-gray-600 transition hover:border-amber-300 hover:bg-amber-50 hover:text-amber-700"
                                                >
                                                    Edit
                                                </a>

                                                <form
                                                    method="POST"
                                                    action="{{ route('karyawan.prospects.destroy', $prospect) }}"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus data calon jamaah ini?')"
                                                >
                                                    @csrf
                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        class="rounded-md border border-gray-200 px-3 py-1.5 text-xs font-medium text-gray-600 transition hover:border-red-300 hover:bg-red-50 hover:text-red-700"
                                                    >
                                                        Hapus
                                                    </button>
                                                </form>

                                            </div>
                                        </td>

                                    </tr>

                                @empty

                                    <tr>
                                        <td colspan="8" class="px-5 py-14 text-center">

                                            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                     width="23" height="23" viewBox="0 0 24 24"
                                                     fill="none" stroke="currentColor"
                                                     stroke-width="1.7">
                                                    <circle cx="11" cy="11" r="8"/>
                                                    <path d="m21 21-4.35-4.35"/>
                                                </svg>
                                            </div>

                                            <p class="mt-3 font-semibold text-gray-800">
                                                Belum ada data calon jamaah
                                            </p>

                                            <p class="mt-1 text-sm text-gray-500">
                                                Tambahkan calon jamaah baru untuk mulai menggunakan CRM.
                                            </p>

                                            <a
                                                href="{{ route('karyawan.prospects.create') }}"
                                                class="mt-4 inline-flex rounded-lg bg-[#FDD52E] px-4 py-2 text-sm font-semibold text-gray-900 hover:bg-yellow-400"
                                            >
                                                Tambah Calon Jamaah
                                            </a>

                                        </td>
                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>


                    <!-- PAGINATION -->
                    @if ($prospects->hasPages())
                        <div class="border-t border-gray-100 px-5 py-4">
                            {{ $prospects->links() }}
                        </div>
                    @endif

                    <div class="border-t border-gray-100 px-5 py-3 text-xs text-gray-400">
                        Menampilkan {{ $prospects->count() }} dari {{ $prospects->total() }} data calon jamaah.
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
