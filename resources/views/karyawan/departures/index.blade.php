<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Persiapan Keberangkatan - Ansar Travel</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gray-50 font-sans antialiased">

    <div class="flex min-h-screen bg-gray-50">

        {{-- Sidebar --}}
        @include('partials.sidebar')

        {{-- Main Content --}}
        <main class="min-w-0 flex-1 p-6 lg:p-8">

            <div class="mx-auto max-w-7xl">

                {{-- Header --}}
                <div class="mb-8 flex flex-wrap items-start justify-between gap-4">

                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">
                            Persiapan Keberangkatan
                        </h1>

                        <p class="mt-1 text-sm text-gray-500">
                            Kelola dan pantau kesiapan perjalanan jamaah Ansar Travel.
                        </p>
                    </div>

                    <a
                        href="{{ route('karyawan.departures.create') }}"
                        class="inline-flex items-center rounded-lg bg-yellow-400 px-5 py-2.5 text-sm font-semibold text-gray-900 transition hover:bg-yellow-500"
                    >
                        + Buat Persiapan
                    </a>

                </div>

                {{-- Notifications --}}
                @if (session('success'))
                    <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-5 py-4 text-sm text-green-700">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700">

                        <p class="font-semibold">
                            Terjadi kesalahan:
                        </p>

                        <ul class="mt-2 list-inside list-disc space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>

                    </div>
                @endif

                {{-- Summary Cards --}}
                <div class="mb-6 grid grid-cols-1 gap-5 sm:grid-cols-3">

                    {{-- Total Schedules --}}
                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">

                        <p class="text-sm text-gray-500">
                            Total Jadwal Keberangkatan
                        </p>

                        <h2 class="mt-2 text-2xl font-bold text-gray-800">
                            {{ $schedules->total() }}
                        </h2>

                        <p class="mt-1 text-xs text-gray-400">
                            Seluruh jadwal yang terdaftar
                        </p>

                    </div>

                    {{-- Preparation Created --}}
                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">

                        <p class="text-sm text-gray-500">
                            Sudah Memiliki Persiapan
                        </p>

                        <h2 class="mt-2 text-2xl font-bold text-green-600">
                            {{ \App\Models\DeparturePreparation::count() }}
                        </h2>

                        <p class="mt-1 text-xs text-gray-400">
                            Jadwal dengan data persiapan
                        </p>

                    </div>

                    {{-- Preparation Not Created --}}
                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">

                        <p class="text-sm text-gray-500">
                            Belum Memiliki Persiapan
                        </p>

                        <h2 class="mt-2 text-2xl font-bold text-orange-600">
                            {{ \App\Models\Schedule::whereDoesntHave('departurePreparation')->count() }}
                        </h2>

                        <p class="mt-1 text-xs text-gray-400">
                            Jadwal yang belum diproses
                        </p>

                    </div>

                </div>

                {{-- Schedule Table --}}
                <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                    {{-- Table Header --}}
                    <div class="border-b border-gray-200 px-6 py-5">

                        <h2 class="font-semibold text-gray-800">
                            Daftar Jadwal dan Persiapan
                        </h2>

                        <p class="mt-1 text-sm text-gray-500">
                            Pilih jadwal untuk melihat atau membuat persiapan keberangkatan.
                        </p>

                    </div>

                    <div class="overflow-x-auto">

                        <table class="w-full min-w-[1100px] text-left text-sm">

                            <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500">

                                <tr>
                                    <th class="px-6 py-4">No.</th>
                                    <th class="px-6 py-4">Jadwal Perjalanan</th>
                                    <th class="px-6 py-4">Jenis</th>
                                    <th class="px-6 py-4">Keberangkatan</th>
                                    <th class="px-6 py-4">Kembali</th>
                                    <th class="px-6 py-4">Kelompok</th>
                                    <th class="px-6 py-4">Kapasitas</th>
                                    <th class="px-6 py-4">Persiapan</th>
                                    <th class="px-6 py-4">Aksi</th>
                                </tr>

                            </thead>

                            <tbody class="divide-y divide-gray-100">

                                @forelse ($schedules as $index => $schedule)

                                    @php
                                        $preparation = $schedule->departurePreparation;

                                        $preparationStyles = [
                                            'draft' => 'bg-gray-100 text-gray-600',
                                            'proses' => 'bg-orange-50 text-orange-700',
                                            'siap_berangkat' => 'bg-green-50 text-green-700',
                                            'selesai' => 'bg-blue-50 text-blue-700',
                                        ];

                                        $preparationLabels = [
                                            'draft' => 'Draft',
                                            'proses' => 'Dalam Proses',
                                            'siap_berangkat' => 'Siap Berangkat',
                                            'selesai' => 'Selesai',
                                        ];
                                    @endphp

                                    <tr class="transition hover:bg-gray-50">

                                        {{-- Number --}}
                                        <td class="whitespace-nowrap px-6 py-5 text-gray-500">
                                            {{ $schedules->firstItem() + $index }}
                                        </td>

                                        {{-- Schedule Name --}}
                                        <td class="px-6 py-5">

                                            <p class="font-semibold text-gray-800">
                                                {{ $schedule->name }}
                                            </p>

                                            <p class="mt-1 text-xs text-gray-400">
                                                {{ $schedule->airline ?? 'Maskapai belum ditentukan' }}
                                            </p>

                                        </td>

                                        {{-- Type --}}
                                        <td class="whitespace-nowrap px-6 py-5 text-gray-600">
                                            {{ ucfirst($schedule->type) }}
                                        </td>

                                        {{-- Departure Date --}}
                                        <td class="whitespace-nowrap px-6 py-5 text-gray-600">
                                            {{ $schedule->departure_date?->format('d M Y') ?? '-' }}
                                        </td>

                                        {{-- Return Date --}}
                                        <td class="whitespace-nowrap px-6 py-5 text-gray-600">
                                            {{ $schedule->return_date?->format('d M Y') ?? '-' }}
                                        </td>

                                        {{-- Groups --}}
                                        <td class="whitespace-nowrap px-6 py-5">

                                            <span class="font-semibold text-gray-800">
                                                {{ $schedule->groups_count }}
                                            </span>

                                            <span class="text-xs text-gray-400">
                                                Kelompok
                                            </span>

                                        </td>

                                        {{-- Capacity --}}
                                        <td class="whitespace-nowrap px-6 py-5">

                                            <p class="font-semibold text-gray-800">
                                                {{ $schedule->booked_seats }} / {{ $schedule->total_seats }}
                                            </p>

                                            <p class="mt-1 text-xs text-gray-400">
                                                {{ $schedule->available_seats }} tersedia
                                            </p>

                                        </td>

                                        {{-- Preparation Status --}}
                                        <td class="whitespace-nowrap px-6 py-5">

                                            @if ($preparation)

                                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $preparationStyles[$preparation->status] ?? 'bg-gray-100 text-gray-600' }}">
                                                    {{ $preparationLabels[$preparation->status] ?? ucfirst(str_replace('_', ' ', $preparation->status)) }}
                                                </span>

                                            @else

                                                <span class="inline-flex rounded-full bg-orange-50 px-3 py-1 text-xs font-semibold text-orange-700">
                                                    Belum Dibuat
                                                </span>

                                            @endif

                                        </td>

                                        {{-- Action --}}
                                        <td class="whitespace-nowrap px-6 py-5">

                                            @if ($preparation)

                                                <a
                                                    href="{{ route('karyawan.departures.show', $preparation) }}"
                                                    class="inline-flex rounded-lg bg-gray-900 px-3 py-2 text-xs font-semibold text-white transition hover:bg-gray-700"
                                                >
                                                    Detail
                                                </a>

                                            @else

                                                <a
                                                    href="{{ route('karyawan.departures.create') }}"
                                                    class="inline-flex rounded-lg bg-yellow-400 px-3 py-2 text-xs font-semibold text-gray-900 transition hover:bg-yellow-500"
                                                >
                                                    Buat Persiapan
                                                </a>

                                            @endif

                                        </td>

                                    </tr>

                                @empty

                                    <tr>
                                        <td colspan="9" class="px-6 py-16 text-center">

                                            <p class="font-semibold text-gray-700">
                                                Belum ada jadwal keberangkatan.
                                            </p>

                                            <p class="mt-1 text-sm text-gray-400">
                                                Jadwal keberangkatan yang dibuat oleh Admin akan muncul di sini.
                                            </p>

                                        </td>
                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                    {{-- Pagination --}}
                    @if ($schedules->hasPages())

                        <div class="border-t border-gray-200 px-6 py-4">
                            {{ $schedules->links() }}
                        </div>

                    @endif

                </div>

                {{-- Footer --}}
                <p class="mt-5 text-xs text-gray-500">
                    Data persiapan keberangkatan dikelola berdasarkan jadwal yang tersedia pada sistem.
                </p>

            </div>

        </main>

    </div>

</body>
</html>
