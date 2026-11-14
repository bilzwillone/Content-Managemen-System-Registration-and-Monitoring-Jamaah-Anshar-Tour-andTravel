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

    <main class="min-w-0 flex-1 p-5 lg:p-8">
        <div class="mx-auto max-w-7xl space-y-7">
            <header class="rounded-2xl bg-[#171717] p-7 text-white shadow-sm">
                <p class="text-sm font-semibold text-[#FDD52E]">MUTHAWIF AREA</p>
                <h1 class="mt-3 text-2xl font-bold md:text-3xl">Dashboard Muthawif</h1>
                <p class="mt-2 text-sm leading-6 text-gray-300">
                    Ringkasan kelompok dan jadwal perjalanan yang menjadi tanggung jawab Anda.
                </p>
            </header>

            @if (session('success'))
                <div class="rounded-xl border border-green-200 bg-green-50 px-5 py-4 text-sm text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <section class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-3">
                <div class="rounded-2xl border border-gray-200 border-t-4 border-t-[#FFB22C] bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-gray-500">Kelompok yang Ditugaskan</p>
                    <p class="mt-4 text-3xl font-bold text-gray-900">{{ $totalGroups }}</p>
                    <p class="mt-2 text-xs text-gray-400">Sebagai Muthawif utama atau pendamping</p>
                </div>
                <div class="rounded-2xl border border-gray-200 border-t-4 border-t-[#FFB22C] bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-gray-500">Total Jamaah Aktif</p>
                    <p class="mt-4 text-3xl font-bold text-gray-900">{{ $totalJamaah }}</p>
                    <p class="mt-2 text-xs text-gray-400">Anggota aktif dari kelompok tugas Anda</p>
                </div>
                <div class="rounded-2xl border border-gray-200 border-t-4 border-t-[#FFB22C] bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-gray-500">Jadwal Akan Berangkat</p>
                    <p class="mt-4 text-3xl font-bold text-gray-900">{{ $upcomingGroups->count() }}</p>
                    <p class="mt-2 text-xs text-gray-400">Hari ini atau setelahnya</p>
                </div>
            </section>

            <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-100 px-6 py-5">
                    <div>
                        <h2 class="font-bold text-gray-900">Kelompok Tanggung Jawab Saya</h2>
                        <p class="mt-1 text-sm text-gray-500">Kelompok yang ditetapkan oleh Karyawan kepada akun Anda.</p>
                    </div>
                    <a href="{{ route('muthawif.groups.index') }}"
                       class="rounded-lg bg-[#FDD52E] px-4 py-2.5 text-sm font-bold text-gray-900 transition hover:bg-yellow-500">
                        Lihat Semua Kelompok
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[780px] text-left text-sm">
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
                                <tr class="transition hover:bg-gray-50">
                                    <td class="px-6 py-5">
                                        <p class="font-semibold text-gray-900">{{ $group->name }}</p>
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
            </section>

            <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-100 px-6 py-5">
                    <h2 class="font-bold text-gray-900">Jadwal Keberangkatan Terdekat</h2>
                    <p class="mt-1 text-sm text-gray-500">Jadwal dari kelompok yang menjadi tanggung jawab Anda.</p>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse ($upcomingGroups as $group)
                        <div class="flex flex-wrap items-center justify-between gap-3 px-6 py-4">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $group->name }}</p>
                                <p class="mt-1 text-sm text-gray-500">{{ $group->schedule?->name ?? '-' }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-semibold text-gray-900">{{ $group->schedule?->departure_date?->format('d M Y') ?? '-' }}</p>
                                <p class="mt-1 text-xs text-gray-400">{{ $group->members->count() }} jamaah aktif</p>
                            </div>
                        </div>
                    @empty
                        <p class="px-6 py-10 text-center text-sm text-gray-500">Belum ada jadwal keberangkatan mendatang pada kelompok Anda.</p>
                    @endforelse
                </div>
            </section>
        </div>
    </main>
</div>
</body>
</html>
