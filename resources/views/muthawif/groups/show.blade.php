<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Detail Kelompok - Muthawif Ansar Travel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50 font-sans antialiased">
    <div class="flex min-h-screen bg-gray-50">
        @include('partials.sidebar')

        <main class="min-w-0 flex-1 p-6 lg:p-8">
            <div class="mx-auto max-w-7xl">
                <div class="mb-8">
                    <a href="{{ route('muthawif.groups.index') }}" class="mb-4 inline-flex text-sm font-medium text-gray-500 transition hover:text-gray-800">
                        ← Kembali ke Kelompok & Jadwal
                    </a>
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">{{ $group->name }}</h1>
                            <p class="mt-1 text-sm text-gray-500">Kode Kelompok: {{ $group->group_code }}</p>
                        </div>
                        <span class="rounded-full bg-yellow-50 px-4 py-2 text-sm font-semibold capitalize text-yellow-700">
                            {{ str_replace('_', ' ', $group->status) }}
                        </span>
                    </div>
                </div>

                <div class="mb-6 grid grid-cols-1 gap-5 sm:grid-cols-3">
                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                        <p class="text-sm text-gray-500">Jumlah Jamaah Aktif</p>
                        <p class="mt-2 text-2xl font-bold text-gray-800">{{ $group->members->count() }}</p>
                    </div>
                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                        <p class="text-sm text-gray-500">Jadwal Keberangkatan</p>
                        <p class="mt-2 text-lg font-bold text-gray-800">{{ $group->schedule?->departure_date?->format('d M Y') ?? '-' }}</p>
                    </div>
                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                        <p class="text-sm text-gray-500">Tanggal Kembali</p>
                        <p class="mt-2 text-lg font-bold text-gray-800">{{ $group->schedule?->return_date?->format('d M Y') ?? '-' }}</p>
                    </div>
                </div>

                <div class="mb-6 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                    <div class="border-b border-gray-200 px-6 py-5">
                        <h2 class="font-semibold text-gray-800">Informasi Perjalanan</h2>
                        <p class="mt-1 text-sm text-gray-500">Informasi jadwal dan persiapan yang dimasukkan oleh Karyawan.</p>
                    </div>
                    <div class="grid grid-cols-1 gap-5 p-6 sm:grid-cols-2 lg:grid-cols-3">
                        <div>
                            <p class="text-xs text-gray-400">Nama Jadwal</p>
                            <p class="mt-1 font-semibold text-gray-800">{{ $group->schedule?->name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Jenis Perjalanan</p>
                            <p class="mt-1 font-semibold capitalize text-gray-800">{{ $group->schedule?->type ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Maskapai</p>
                            <p class="mt-1 font-semibold text-gray-800">{{ $group->schedule?->airline ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Penerbangan Berangkat</p>
                            <p class="mt-1 font-semibold text-gray-800">{{ $group->schedule?->departurePreparation?->departure_flight_number ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Waktu Keberangkatan</p>
                            <p class="mt-1 font-semibold text-gray-800">
                                {{ $group->schedule?->departurePreparation?->departure_at?->format('d M Y, H:i') ?? '-' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Bandara Keberangkatan</p>
                            <p class="mt-1 font-semibold text-gray-800">{{ $group->schedule?->departurePreparation?->departure_airport ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Bandara Kedatangan</p>
                            <p class="mt-1 font-semibold text-gray-800">{{ $group->schedule?->departurePreparation?->arrival_airport ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Penerbangan Pulang</p>
                            <p class="mt-1 font-semibold text-gray-800">{{ $group->schedule?->departurePreparation?->return_flight_number ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Waktu Pulang</p>
                            <p class="mt-1 font-semibold text-gray-800">
                                {{ $group->schedule?->departurePreparation?->return_departure_at?->format('d M Y, H:i') ?? '-' }}
                            </p>
                        </div>
                    </div>
                    @if ($group->schedule?->departurePreparation)
                        <div class="grid grid-cols-1 gap-5 border-t border-gray-100 p-6 sm:grid-cols-2">
                            <div>
                                <h3 class="font-semibold text-gray-800">Hotel Makkah</h3>
                                <p class="mt-2 text-sm text-gray-700">{{ $group->schedule->departurePreparation->makkah_hotel_name ?? 'Belum diinformasikan' }}</p>
                                <p class="mt-1 text-xs text-gray-500">{{ $group->schedule->departurePreparation->makkah_hotel_address ?? '-' }}</p>
                                <p class="mt-1 text-xs text-gray-500">
                                    Check-in: {{ $group->schedule->departurePreparation->makkah_check_in?->format('d M Y') ?? '-' }}
                                    · Check-out: {{ $group->schedule->departurePreparation->makkah_check_out?->format('d M Y') ?? '-' }}
                                </p>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800">Hotel Madinah</h3>
                                <p class="mt-2 text-sm text-gray-700">{{ $group->schedule->departurePreparation->madinah_hotel_name ?? 'Belum diinformasikan' }}</p>
                                <p class="mt-1 text-xs text-gray-500">{{ $group->schedule->departurePreparation->madinah_hotel_address ?? '-' }}</p>
                                <p class="mt-1 text-xs text-gray-500">
                                    Check-in: {{ $group->schedule->departurePreparation->madinah_check_in?->format('d M Y') ?? '-' }}
                                    · Check-out: {{ $group->schedule->departurePreparation->madinah_check_out?->format('d M Y') ?? '-' }}
                                </p>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800">Manasik</h3>
                                <p class="mt-2 text-sm text-gray-700">{{ $group->schedule->departurePreparation->manasik_at?->format('d M Y, H:i') ?? '-' }}</p>
                                <p class="mt-1 text-xs text-gray-500">{{ $group->schedule->departurePreparation->manasik_location ?? '-' }}</p>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800">Briefing</h3>
                                <p class="mt-2 text-sm text-gray-700">{{ $group->schedule->departurePreparation->briefing_at?->format('d M Y, H:i') ?? '-' }}</p>
                                <p class="mt-1 text-xs text-gray-500">{{ $group->schedule->departurePreparation->briefing_location ?? '-' }}</p>
                            </div>
                            <div class="sm:col-span-2">
                                <h3 class="font-semibold text-gray-800">Informasi Transportasi</h3>
                                <p class="mt-2 whitespace-pre-line text-sm text-gray-600">{{ $group->schedule->departurePreparation->transportation_details ?: 'Belum diinformasikan.' }}</p>
                            </div>
                        </div>
                    @else
                        <div class="border-t border-gray-100 px-6 py-5 text-sm text-gray-500">
                            Informasi persiapan perjalanan belum dilengkapi oleh Karyawan.
                        </div>
                    @endif
                </div>

                <div class="mb-6 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                    <div class="border-b border-gray-200 px-6 py-5">
                        <h2 class="font-semibold text-gray-800">Penugasan Muthawif</h2>
                        <p class="mt-1 text-sm text-gray-500">Muthawif utama dan pendamping untuk kelompok ini.</p>
                    </div>
                    <div class="grid grid-cols-1 gap-4 p-6 sm:grid-cols-2">
                        @foreach (['utama' => 'Muthawif Utama', 'pendamping' => 'Muthawif Pendamping'] as $type => $label)
                            @php
                                $assignedUser = $group->muthawifs->first(fn ($user) => $user->pivot->assignment_type === $type);
                            @endphp
                            <div class="rounded-lg border border-gray-200 p-4">
                                <p class="text-xs text-gray-400">{{ $label }}</p>
                                <p class="mt-1 font-semibold text-gray-800">{{ $assignedUser?->name ?? 'Belum ditugaskan' }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                    <div class="border-b border-gray-200 px-6 py-5">
                        <h2 class="font-semibold text-gray-800">Anggota Jamaah</h2>
                        <p class="mt-1 text-sm text-gray-500">Daftar jamaah aktif dalam kelompok yang Anda dampingi.</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[750px] text-left text-sm">
                            <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
                                <tr>
                                    <th class="px-6 py-4">No.</th>
                                    <th class="px-6 py-4">Nama Jamaah</th>
                                    <th class="px-6 py-4">Nomor HP</th>
                                    <th class="px-6 py-4">Email</th>
                                    <th class="px-6 py-4">Status Anggota</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse ($group->members as $index => $member)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-5 text-gray-500">{{ $index + 1 }}</td>
                                        <td class="px-6 py-5 font-semibold text-gray-800">{{ $member->name }}</td>
                                        <td class="px-6 py-5 text-gray-600">{{ $member->phone ?? '-' }}</td>
                                        <td class="px-6 py-5 text-gray-600">{{ $member->email ?? '-' }}</td>
                                        <td class="px-6 py-5">
                                            <span class="rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">Aktif</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-500">Belum ada anggota jamaah aktif pada kelompok ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if ($group->notes)
                    <div class="mt-6 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                        <h2 class="font-semibold text-gray-800">Catatan Kelompok</h2>
                        <p class="mt-2 whitespace-pre-line text-sm text-gray-600">{{ $group->notes }}</p>
                    </div>
                @endif
            </div>
        </main>
    </div>
</body>
</html>
