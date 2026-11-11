
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Kelompok Jamaah - Ansar Travel</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gray-50 font-sans antialiased">

    <div class="flex min-h-screen bg-gray-50">

        {{-- Sidebar --}}
        @include('partials.sidebar')

        {{-- Main Content --}}
        <main class="min-w-0 flex-1 p-6 lg:p-8">

            <div class="mx-auto max-w-7xl">

                {{-- Page Header --}}
                <div class="mb-8 flex flex-wrap items-start justify-between gap-4">

                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">
                            Kelompok & Muthawif
                        </h1>

                        <p class="mt-1 text-sm text-gray-500">
                            Kelola pembagian kelompok jamaah dan penugasan Muthawif.
                        </p>
                    </div>

                    <a
                        href="{{ route('karyawan.groups.create') }}"
                        class="inline-flex items-center rounded-lg bg-yellow-400 px-5 py-2.5 text-sm font-semibold text-gray-900 transition hover:bg-yellow-500"
                    >
                        + Buat Kelompok
                    </a>

                </div>

                {{-- Success Notification --}}
                @if (session('success'))
                    <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-5 py-4 text-sm text-green-700">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Error Notification --}}
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

                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                        <p class="text-sm text-gray-500">
                            Total Kelompok
                        </p>

                        <h2 class="mt-2 text-2xl font-bold text-gray-800">
                            {{ $groups->total() }}
                        </h2>

                        <p class="mt-1 text-xs text-gray-400">
                            Seluruh kelompok yang terdaftar
                        </p>
                    </div>

                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                        <p class="text-sm text-gray-500">
                            Kelompok Persiapan
                        </p>

                        <h2 class="mt-2 text-2xl font-bold text-orange-600">
                            {{ \App\Models\JamaahGroup::where('status', 'persiapan')->count() }}
                        </h2>

                        <p class="mt-1 text-xs text-gray-400">
                            Kelompok yang sedang dipersiapkan
                        </p>
                    </div>

                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                        <p class="text-sm text-gray-500">
                            Kelompok Siap Berangkat
                        </p>

                        <h2 class="mt-2 text-2xl font-bold text-green-600">
                            {{ \App\Models\JamaahGroup::where('status', 'siap_berangkat')->count() }}
                        </h2>

                        <p class="mt-1 text-xs text-gray-400">
                            Kelompok yang siap diberangkatkan
                        </p>
                    </div>

                </div>

                {{-- Group Table --}}
                <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                    <div class="border-b border-gray-200 px-6 py-5">

                        <h2 class="font-semibold text-gray-800">
                            Daftar Kelompok Jamaah
                        </h2>

                        <p class="mt-1 text-sm text-gray-500">
                            Informasi kelompok berdasarkan jadwal keberangkatan.
                        </p>

                    </div>

                    <div class="overflow-x-auto">

                        <table class="w-full min-w-[1050px] text-left text-sm">

                            <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
                                <tr>
                                    <th class="px-6 py-4">No.</th>
                                    <th class="px-6 py-4">Kelompok</th>
                                    <th class="px-6 py-4">Jadwal Keberangkatan</th>
                                    <th class="px-6 py-4">Tanggal Berangkat</th>
                                    <th class="px-6 py-4">Anggota</th>
                                    <th class="px-6 py-4">Muthawif</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4">Aksi</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-100">

                                @forelse ($groups as $index => $group)

                                    @php
                                        $statusStyles = [
                                            'persiapan' => 'bg-orange-50 text-orange-700',
                                            'siap_berangkat' => 'bg-green-50 text-green-700',
                                            'berangkat' => 'bg-blue-50 text-blue-700',
                                            'selesai' => 'bg-gray-100 text-gray-600',
                                            'dibatalkan' => 'bg-red-50 text-red-700',
                                        ];

                                        $statusLabels = [
                                            'persiapan' => 'Persiapan',
                                            'siap_berangkat' => 'Siap Berangkat',
                                            'berangkat' => 'Berangkat',
                                            'selesai' => 'Selesai',
                                            'dibatalkan' => 'Dibatalkan',
                                        ];
                                    @endphp

                                    <tr class="transition hover:bg-gray-50">

                                        {{-- Number --}}
                                        <td class="whitespace-nowrap px-6 py-5 text-gray-500">
                                            {{ $groups->firstItem() + $index }}
                                        </td>

                                        {{-- Group Identity --}}
                                        <td class="px-6 py-5">

                                            <p class="font-semibold text-gray-800">
                                                {{ $group->name }}
                                            </p>

                                            <p class="mt-1 text-xs text-gray-400">
                                                {{ $group->group_code }}
                                            </p>

                                        </td>

                                        {{-- Schedule --}}
                                        <td class="px-6 py-5">

                                            <p class="font-medium text-gray-700">
                                                {{ $group->schedule?->name ?? 'Jadwal tidak ditemukan' }}
                                            </p>

                                            <p class="mt-1 text-xs text-gray-400">
                                                {{ ucfirst($group->schedule?->type ?? '-') }}
                                            </p>

                                        </td>

                                        {{-- Departure Date --}}
                                        <td class="whitespace-nowrap px-6 py-5 text-gray-600">
                                            {{ $group->schedule?->departure_date?->format('d M Y') ?? '-' }}
                                        </td>

                                        {{-- Members --}}
                                        <td class="whitespace-nowrap px-6 py-5">

                                            <span class="font-semibold text-gray-800">
                                                {{ $group->members_count }}
                                            </span>

                                            <span class="text-xs text-gray-400">
                                                Jamaah
                                            </span>

                                        </td>

                                        {{-- Muthawifs --}}
                                        <td class="px-6 py-5">

                                            @forelse ($group->muthawifs as $muthawif)

                                                <p class="mb-1 text-xs text-gray-600">
                                                    {{ $muthawif->name }}
                                                    <span class="text-gray-400">
                                                        ({{ $muthawif->pivot->assignment_type === 'utama' ? 'Utama' : 'Pendamping' }})
                                                    </span>
                                                </p>

                                            @empty

                                                <span class="text-xs text-orange-600">
                                                    Belum ditugaskan
                                                </span>

                                            @endforelse

                                            @if ($group->muthawifs_count < 2)
                                                <p class="mt-1 text-xs text-gray-400">
                                                    {{ $group->muthawifs_count }}/2 Muthawif
                                                </p>
                                            @endif

                                        </td>

                                        {{-- Status --}}
                                        <td class="whitespace-nowrap px-6 py-5">

                                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusStyles[$group->status] ?? 'bg-gray-100 text-gray-600' }}">
                                                {{ $statusLabels[$group->status] ?? ucfirst($group->status) }}
                                            </span>

                                        </td>

                                        {{-- Actions --}}
                                        <td class="whitespace-nowrap px-6 py-5">

                                            <div class="flex items-center gap-2">

                                                {{-- Detail --}}
                                                <a
                                                    href="{{ route('karyawan.groups.show', $group) }}"
                                                    class="inline-flex rounded-lg bg-gray-900 px-3 py-2 text-xs font-semibold text-white transition hover:bg-gray-700"
                                                >
                                                    Detail
                                                </a>

                                                {{-- Delete --}}
                                                @if (!in_array($group->status, ['berangkat', 'selesai'], true))

                                                    <form
                                                        action="{{ route('karyawan.groups.destroy', $group) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus kelompok {{ addslashes($group->name) }}? Seluruh anggota dan penugasan Muthawif dalam kelompok ini juga akan dihapus.')"
                                                    >

                                                        @csrf
                                                        @method('DELETE')

                                                        <button
                                                            type="submit"
                                                            class="inline-flex rounded-lg border border-red-200 bg-white px-3 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-50"
                                                        >
                                                            Hapus
                                                        </button>

                                                    </form>

                                                @else

                                                    <span class="text-xs text-gray-400">
                                                        Tidak dapat dihapus
                                                    </span>

                                                @endif

                                            </div>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>
                                        <td colspan="8" class="px-6 py-16 text-center">

                                            <p class="font-semibold text-gray-700">
                                                Belum ada kelompok jamaah.
                                            </p>

                                            <p class="mt-1 text-sm text-gray-400">
                                                Buat kelompok pertama untuk mulai mengatur anggota dan Muthawif.
                                            </p>

                                        </td>
                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                    {{-- Pagination --}}
                    @if ($groups->hasPages())

                        <div class="border-t border-gray-200 px-6 py-4">
                            {{ $groups->links() }}
                        </div>

                    @endif

                </div>

                <p class="mt-5 text-xs text-gray-500">
                    Setiap kelompok dapat memiliki banyak jamaah dan harus memiliki dua Muthawif, yaitu utama dan pendamping.
                </p>

            </div>

        </main>

    </div>

</body>
</html>
