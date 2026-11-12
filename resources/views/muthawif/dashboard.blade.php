<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Muthawif - Ansar Travel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50 font-sans antialiased">
    <div class="flex min-h-screen bg-gray-50">
        @include('partials.sidebar')

        <main class="min-w-0 flex-1 p-6 lg:p-8">
            <div class="mx-auto max-w-7xl">
                <div class="mb-8">
                    <h1 class="text-2xl font-bold text-gray-800">Dashboard Muthawif</h1>
                    <p class="mt-1 text-sm text-gray-500">
                        Ringkasan kelompok dan jadwal perjalanan yang menjadi tanggung jawab Anda.
                    </p>
                </div>

                @if (session('success'))
                    <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-5 py-4 text-sm text-green-700">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="mb-6 grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-3">
                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                        <p class="text-sm text-gray-500">Kelompok yang Ditugaskan</p>
                        <p class="mt-2 text-3xl font-bold text-gray-800">{{ $totalGroups }}</p>
                        <p class="mt-1 text-xs text-gray-400">Kelompok sebagai Muthawif utama atau pendamping</p>
                    </div>
                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                        <p class="text-sm text-gray-500">Total Jamaah Aktif</p>
                        <p class="mt-2 text-3xl font-bold text-gray-800">{{ $totalJamaah }}</p>
                        <p class="mt-1 text-xs text-gray-400">Anggota aktif dari kelompok yang ditugaskan</p>
                    </div>
                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                        <p class="text-sm text-gray-500">Jadwal Akan Berangkat</p>
                        <p class="mt-2 text-3xl font-bold text-gray-800">{{ $upcomingGroups->count() }}</p>
                        <p class="mt-1 text-xs text-gray-400">Kelompok dengan tanggal keberangkatan hari ini atau setelahnya</p>
                    </div>
                </div>

                <div class="mb-6 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-200 px-6 py-5">
                        <div>
                            <h2 class="font-semibold text-gray-800">Kelompok Tanggung Jawab Saya</h2>
                            <p class="mt-1 text-sm text-gray-500">Kelompok yang ditetapkan oleh Karyawan kepada akun Anda.</p>
                        </div>
                        <a href="{{ route('muthawif.groups.index') }}"
                           class="rounded-lg bg-yellow-400 px-4 py-2 text-sm font-semibold text-gray-900 transition hover:bg-yellow-500">
                            Lihat Semua Kelompok
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[850px] text-left text-sm">
                            <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
                                <tr>
                                    <th class="px-6 py-4">Kelompok</th>
                                    <th class="px-6 py-4">Jadwal</th>
                                    <th class="px-6 py-4">Keberangkatan</th>
                                    <th class="px-6 py-4">Peran Saya</th>
                                    <th class="px-6 py-4">Jamaah</th>
                                    <th class="px-6 py-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse ($groups->take(5) as $group)
                                    @php
                                        $myAssignment = $group->muthawifs->firstWhere('id', auth()->id());
                                    @endphp
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-5">
                                            <p class="font-semibold text-gray-800">{{ $group->name }}</p>
                                            <p class="mt-1 text-xs text-gray-400">{{ $group->group_code }}</p>
                                        </td>
                                        <td class="px-6 py-5 text-gray-600">{{ $group->schedule?->name ?? '-' }}</td>
                                        <td class="whitespace-nowrap px-6 py-5 text-gray-600">
                                            {{ $group->schedule?->departure_date?->format('d M Y') ?? '-' }}
                                        </td>
                                        <td class="px-6 py-5">
                                            <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $myAssignment?->pivot?->assignment_type === 'utama' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-50 text-blue-700' }}">
                                                {{ ucfirst($myAssignment?->pivot?->assignment_type ?? '-') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-5 text-gray-700">{{ $group->members->count() }} Jamaah</td>
                                        <td class="px-6 py-5">
                                            <a href="{{ route('muthawif.groups.show', $group) }}"
                                               class="rounded-lg bg-gray-900 px-3 py-2 text-xs font-semibold text-white transition hover:bg-gray-700">
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-14 text-center">
                                            <p class="font-semibold text-gray-700">Belum ada kelompok yang ditugaskan.</p>
                                            <p class="mt-1 text-sm text-gray-400">Kelompok akan muncul setelah Karyawan menetapkan Anda sebagai Muthawif utama atau pendamping.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                    <div class="border-b border-gray-200 px-6 py-5">
                        <h2 class="font-semibold text-gray-800">Jadwal Keberangkatan Terdekat</h2>
                        <p class="mt-1 text-sm text-gray-500">Jadwal dari kelompok yang menjadi tanggung jawab Anda.</p>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @forelse ($upcomingGroups as $group)
                            <div class="flex flex-wrap items-center justify-between gap-3 px-6 py-4">
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $group->name }}</p>
                                    <p class="mt-1 text-sm text-gray-500">{{ $group->schedule?->name ?? '-' }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-semibold text-gray-800">
                                        {{ $group->schedule?->departure_date?->format('d M Y') ?? '-' }}
                                    </p>
                                    <p class="mt-1 text-xs text-gray-400">{{ $group->members->count() }} jamaah aktif</p>
                                </div>
                            </div>
                        @empty
                            <p class="px-6 py-10 text-center text-sm text-gray-500">Belum ada jadwal keberangkatan mendatang pada kelompok Anda.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
