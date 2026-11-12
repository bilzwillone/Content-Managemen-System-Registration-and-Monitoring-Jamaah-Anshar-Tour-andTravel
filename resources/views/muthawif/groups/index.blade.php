<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kelompok & Jadwal - Muthawif Ansar Travel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50 font-sans antialiased">
    <div class="flex min-h-screen bg-gray-50">
        @include('partials.sidebar')

        <main class="min-w-0 flex-1 p-6 lg:p-8">
            <div class="mx-auto max-w-7xl">
                <div class="mb-8">
                    <h1 class="text-2xl font-bold text-gray-800">Kelompok & Jadwal</h1>
                    <p class="mt-1 text-sm text-gray-500">Daftar kelompok jamaah dan jadwal perjalanan yang menjadi tanggung jawab Anda.</p>
                </div>

                <div class="mb-6 grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                        <p class="text-sm text-gray-500">Total Kelompok</p>
                        <p class="mt-2 text-2xl font-bold text-gray-800">{{ $groups->total() }}</p>
                    </div>
                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                        <p class="text-sm text-gray-500">Kelompok pada Halaman Ini</p>
                        <p class="mt-2 text-2xl font-bold text-gray-800">{{ $groups->count() }}</p>
                    </div>
                </div>

                <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                    <div class="border-b border-gray-200 px-6 py-5">
                        <h2 class="font-semibold text-gray-800">Daftar Kelompok Jamaah</h2>
                        <p class="mt-1 text-sm text-gray-500">Data hanya menampilkan kelompok yang menugaskan akun Anda.</p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[1000px] text-left text-sm">
                            <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
                                <tr>
                                    <th class="px-6 py-4">No.</th>
                                    <th class="px-6 py-4">Kelompok</th>
                                    <th class="px-6 py-4">Jadwal Perjalanan</th>
                                    <th class="px-6 py-4">Tanggal Berangkat</th>
                                    <th class="px-6 py-4">Jamaah Aktif</th>
                                    <th class="px-6 py-4">Penugasan</th>
                                    <th class="px-6 py-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse ($groups as $index => $group)
                                    @php
                                        $myAssignment = $group->muthawifs->firstWhere('id', auth()->id());
                                    @endphp
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-5 text-gray-500">{{ $groups->firstItem() + $index }}</td>
                                        <td class="px-6 py-5">
                                            <p class="font-semibold text-gray-800">{{ $group->name }}</p>
                                            <p class="mt-1 text-xs text-gray-400">{{ $group->group_code }}</p>
                                        </td>
                                        <td class="px-6 py-5 text-gray-600">{{ $group->schedule?->name ?? '-' }}</td>
                                        <td class="whitespace-nowrap px-6 py-5 text-gray-600">{{ $group->schedule?->departure_date?->format('d M Y') ?? '-' }}</td>
                                        <td class="px-6 py-5 text-gray-700">{{ $group->members->count() }} Jamaah</td>
                                        <td class="px-6 py-5">
                                            <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $myAssignment?->pivot?->assignment_type === 'utama' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-50 text-blue-700' }}">
                                                {{ ucfirst($myAssignment?->pivot?->assignment_type ?? '-') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-5">
                                            <a href="{{ route('muthawif.groups.show', $group) }}"
                                               class="rounded-lg bg-gray-900 px-3 py-2 text-xs font-semibold text-white transition hover:bg-gray-700">
                                                Lihat Detail
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-14 text-center">
                                            <p class="font-semibold text-gray-700">Belum ada kelompok yang ditugaskan.</p>
                                            <p class="mt-1 text-sm text-gray-400">Hubungi Karyawan Ansar Travel apabila kelompok Anda belum tampil.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($groups->hasPages())
                        <div class="border-t border-gray-200 px-6 py-4">{{ $groups->links() }}</div>
                    @endif
                </div>
            </div>
        </main>
    </div>
</body>
</html>
