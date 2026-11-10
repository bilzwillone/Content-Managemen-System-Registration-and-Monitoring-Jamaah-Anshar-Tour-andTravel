
@extends('admin.layouts.app')

@section('title', 'Jadwal Keberangkatan')
@section('page-heading', 'Jadwal Keberangkatan')

@section('content')

    @if (session('success'))
        <div class="mb-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif

    <div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">

        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Jadwal Keberangkatan
            </h1>

            <p class="mt-2 text-sm text-gray-500">
                Kelola jadwal perjalanan umroh dan tour Ansar Travel.
            </p>
        </div>

        <a href="{{ route('admin.schedules.create') }}"
           class="inline-flex items-center justify-center rounded-lg bg-[#FFB22C] px-5 py-3 text-sm font-semibold text-black transition hover:bg-amber-400">
            + Tambah Jadwal
        </a>

    </div>


    <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">

        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-gray-500">Total Jadwal</p>
            <p class="mt-2 text-2xl font-bold text-gray-800">
                {{ $totalSchedules }}
            </p>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-gray-500">Jadwal Dipublikasikan</p>
            <p class="mt-2 text-2xl font-bold text-green-600">
                {{ $publishedSchedules }}
            </p>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-gray-500">Jadwal Mendatang</p>
            <p class="mt-2 text-2xl font-bold text-blue-600">
                {{ $upcomingSchedules }}
            </p>
        </div>

    </div>


    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

        <div class="border-b border-gray-200 px-5 py-5">

            <h2 class="font-semibold text-gray-800">
                Daftar Jadwal Perjalanan
            </h2>

            <p class="mt-1 text-xs text-gray-500">
                Cari, filter, dan kelola seluruh jadwal keberangkatan.
            </p>

            <form action="{{ route('admin.schedules.index') }}"
                  method="GET"
                  class="mt-5 grid grid-cols-1 gap-3 md:grid-cols-4">

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari nama paket atau maskapai..."
                    class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none focus:border-amber-400 focus:ring-2 focus:ring-amber-100"
                >

                <select
                    name="type"
                    class="rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm outline-none focus:border-amber-400"
                >
                    <option value="">Semua Jenis Perjalanan</option>
                    <option value="umrah" {{ request('type') === 'umrah' ? 'selected' : '' }}>
                        Jadwal Umroh
                    </option>
                    <option value="tour" {{ request('type') === 'tour' ? 'selected' : '' }}>
                        Tour & Wisata
                    </option>
                </select>

                <select
                    name="status"
                    class="rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm outline-none focus:border-amber-400"
                >
                    <option value="">Semua Status Publikasi</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>
                        Published
                    </option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>
                        Draft
                    </option>
                </select>

                <div class="flex gap-2">

                    <button
                        type="submit"
                        class="flex-1 rounded-lg bg-gray-800 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-gray-700"
                    >
                        Cari
                    </button>

                    <a
                        href="{{ route('admin.schedules.index') }}"
                        class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-600 transition hover:bg-gray-50"
                    >
                        Reset
                    </a>

                </div>

            </form>

        </div>


        <div class="overflow-x-auto">

            <table class="w-full min-w-[1100px] text-left text-sm">

                <thead class="bg-gray-50 text-xs uppercase text-gray-500">

                    <tr>
                        <th class="px-5 py-4">No.</th>
                        <th class="px-5 py-4">Nama Perjalanan</th>
                        <th class="px-5 py-4">Keberangkatan</th>
                        <th class="px-5 py-4">Kepulangan</th>
                        <th class="px-5 py-4">Durasi</th>
                        <th class="px-5 py-4">Maskapai</th>
                        <th class="px-5 py-4">Kursi Tersedia</th>
                        <th class="px-5 py-4">Status</th>
                        <th class="px-5 py-4 text-center">Aksi</th>
                    </tr>

                </thead>

                <tbody class="divide-y divide-gray-100">

                    @forelse ($schedules as $schedule)

                        @php
                            $availabilityClasses = match ($schedule->availability_status) {
                                'Sudah Berangkat' => 'bg-red-100 text-red-700',
                                'Penuh' => 'bg-red-100 text-red-700',
                                'Hampir Penuh' => 'bg-amber-100 text-amber-700',
                                default => 'bg-green-100 text-green-700',
                            };
                        @endphp

                        <tr class="transition hover:bg-gray-50">

                            <td class="px-5 py-4 text-gray-500">
                                {{ $schedules->firstItem() + $loop->index }}
                            </td>

                            <td class="px-5 py-4">

                                <p class="font-semibold text-gray-800">
                                    {{ $schedule->name }}
                                </p>

                                <span class="mt-1 inline-block rounded-full bg-blue-50 px-2.5 py-1 text-xs text-blue-700">
                                    {{ $schedule->type === 'umrah' ? 'Umroh' : 'Tour & Wisata' }}
                                </span>

                            </td>

                            <td class="px-5 py-4 whitespace-nowrap text-gray-600">
                                {{ $schedule->departure_date->translatedFormat('d M Y') }}
                            </td>

                            <td class="px-5 py-4 whitespace-nowrap text-gray-600">
                                {{ $schedule->return_date->translatedFormat('d M Y') }}
                            </td>

                            <td class="px-5 py-4 whitespace-nowrap text-gray-600">
                                {{ $schedule->duration_label }}
                            </td>

                            <td class="px-5 py-4 text-gray-600">
                                {{ $schedule->airline ?: '-' }}
                            </td>

                            <td class="px-5 py-4 whitespace-nowrap">

                                <p class="font-semibold text-gray-800">
                                    {{ $schedule->available_seats }}
                                    <span class="font-normal text-gray-500">
                                        / {{ $schedule->total_seats }}
                                    </span>
                                </p>

                                <p class="mt-1 text-xs text-gray-400">
                                    Kursi tersedia
                                </p>

                            </td>

                            <td class="px-5 py-4">

                                <div class="space-y-2">

                                    <span class="inline-block rounded-full px-3 py-1 text-xs font-semibold {{ $availabilityClasses }}">
                                        {{ $schedule->availability_status }}
                                    </span>

                                    <div>
                                        @if ($schedule->status === 'published')
                                            <span class="text-xs font-medium text-green-600">
                                                Published
                                            </span>
                                        @else
                                            <span class="text-xs font-medium text-gray-500">
                                                Draft
                                            </span>
                                        @endif

                                        @if (!$schedule->is_visible)
                                            <span class="ml-1 text-xs text-red-500">
                                                (Tersembunyi)
                                            </span>
                                        @endif
                                    </div>

                                </div>

                            </td>

                            <td class="px-5 py-4">

                                <div class="flex items-center justify-center gap-2">

                                    <a
                                        href="{{ route('admin.schedules.edit', $schedule) }}"
                                        class="rounded-lg bg-[#FFB22C] px-3 py-2 text-xs font-semibold text-black transition hover:bg-amber-400"
                                    >
                                        Edit
                                    </a>

                                    <form
                                        action="{{ route('admin.schedules.destroy', $schedule) }}"
                                        method="POST"
                                        onsubmit="return confirm('Apakah kamu yakin ingin menghapus jadwal ini?')"
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="rounded-lg bg-red-50 px-3 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-100"
                                        >
                                            Hapus
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="9" class="px-5 py-16 text-center">

                                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-gray-100">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-7 w-7 text-gray-400"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="1.5"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Z"/>
                                    </svg>
                                </div>

                                <h3 class="mt-4 font-semibold text-gray-800">
                                    Belum Ada Jadwal
                                </h3>

                                <p class="mt-2 text-sm text-gray-500">
                                    Belum ada data jadwal yang sesuai dengan pencarian.
                                </p>

                                <a
                                    href="{{ route('admin.schedules.create') }}"
                                    class="mt-5 inline-flex rounded-lg bg-[#FFB22C] px-5 py-2.5 text-sm font-semibold text-black hover:bg-amber-400"
                                >
                                    + Tambah Jadwal
                                </a>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        <div class="border-t border-gray-200 bg-gray-50 px-5 py-4">

            <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">

                <p class="text-xs text-gray-500">
                    Menampilkan {{ $schedules->firstItem() ?? 0 }}
                    sampai {{ $schedules->lastItem() ?? 0 }}
                    dari {{ $schedules->total() }} jadwal.
                </p>

                <div>
                    {{ $schedules->links() }}
                </div>

            </div>

        </div>

    </div>

@endsection
