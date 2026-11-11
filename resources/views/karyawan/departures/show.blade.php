<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Detail Persiapan Keberangkatan - Ansar Travel</title>

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
                <div class="mb-8">

                    <a
                        href="{{ route('karyawan.departures.index') }}"
                        class="mb-4 inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-800"
                    >
                        ← Kembali ke Persiapan Keberangkatan
                    </a>

                    <div class="flex flex-wrap items-start justify-between gap-4">

                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">
                                Detail Persiapan Keberangkatan
                            </h1>

                            <p class="mt-1 text-sm text-gray-500">
                                {{ $preparation->schedule?->name ?? 'Jadwal tidak ditemukan' }}
                            </p>
                        </div>

                        <span class="rounded-full bg-yellow-50 px-4 py-2 text-sm font-semibold capitalize text-yellow-700">
                            {{ str_replace('_', ' ', $preparation->status) }}
                        </span>

                    </div>

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

                {{-- Schedule Summary --}}
                <div class="mb-6 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                    <div class="border-b border-gray-200 px-6 py-5">
                        <h2 class="font-semibold text-gray-800">
                            Informasi Jadwal Keberangkatan
                        </h2>
                    </div>

                    <div class="grid grid-cols-1 gap-5 p-6 sm:grid-cols-2 lg:grid-cols-4">

                        <div>
                            <p class="text-xs text-gray-400">Nama Jadwal</p>
                            <p class="mt-1 font-semibold text-gray-800">
                                {{ $preparation->schedule?->name ?? '-' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400">Jenis Perjalanan</p>
                            <p class="mt-1 font-semibold capitalize text-gray-800">
                                {{ $preparation->schedule?->type ?? '-' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400">Tanggal Berangkat</p>
                            <p class="mt-1 font-semibold text-gray-800">
                                {{ $preparation->schedule?->departure_date?->format('d M Y') ?? '-' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400">Tanggal Kembali</p>
                            <p class="mt-1 font-semibold text-gray-800">
                                {{ $preparation->schedule?->return_date?->format('d M Y') ?? '-' }}
                            </p>
                        </div>

                    </div>

                </div>

                {{-- Manifest Summary --}}
                <div class="mb-6 grid grid-cols-1 gap-5 sm:grid-cols-3">

                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                        <p class="text-sm text-gray-500">
                            Total Kelompok
                        </p>

                        <h2 class="mt-2 text-2xl font-bold text-gray-800">
                            {{ $preparation->schedule?->groups->count() ?? 0 }}
                        </h2>

                        <p class="mt-1 text-xs text-gray-400">
                            Kelompok pada jadwal ini
                        </p>
                    </div>

                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                        <p class="text-sm text-gray-500">
                            Total Jamaah
                        </p>

                        <h2 class="mt-2 text-2xl font-bold text-gray-800">
                            {{ $preparation->schedule?->groups->sum(fn($group) => $group->members->count()) ?? 0 }}
                        </h2>

                        <p class="mt-1 text-xs text-gray-400">
                            Jamaah yang tergabung dalam kelompok
                        </p>
                    </div>

                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                        <p class="text-sm text-gray-500">
                            Kapasitas Terisi
                        </p>

                        <h2 class="mt-2 text-2xl font-bold text-gray-800">
                            {{ $preparation->schedule?->booked_seats ?? 0 }}
                            /
                            {{ $preparation->schedule?->total_seats ?? 0 }}
                        </h2>

                        <p class="mt-1 text-xs text-gray-400">
                            Seat pada jadwal keberangkatan
                        </p>
                    </div>

                </div>

                {{-- Preparation Form --}}
                <form
                    action="{{ route('karyawan.departures.update', $preparation) }}"
                    method="POST"
                >

                    @csrf
                    @method('PUT')

                    {{-- Flight Information --}}
                    <div class="mb-6 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                        <div class="border-b border-gray-200 px-6 py-5">
                            <h2 class="font-semibold text-gray-800">
                                Informasi Penerbangan
                            </h2>

                            <p class="mt-1 text-sm text-gray-500">
                                Informasi penerbangan keberangkatan dan kepulangan jamaah.
                            </p>
                        </div>

                        <div class="space-y-6 p-6">

                            <div>
                                <h3 class="mb-4 text-sm font-semibold text-gray-700">
                                    Penerbangan Keberangkatan
                                </h3>

                                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                                    <div>
                                        <label class="mb-2 block text-sm font-medium text-gray-700">
                                            Nomor Penerbangan
                                        </label>

                                        <input
                                            type="text"
                                            name="departure_flight_number"
                                            value="{{ old('departure_flight_number', $preparation->departure_flight_number) }}"
                                            placeholder="Contoh: GA 820"
                                            class="w-full rounded-lg border-gray-300 text-sm focus:border-yellow-400 focus:ring-yellow-400"
                                        >
                                    </div>

                                    <div>
                                        <label class="mb-2 block text-sm font-medium text-gray-700">
                                            Bandara Keberangkatan
                                        </label>

                                        <input
                                            type="text"
                                            name="departure_airport"
                                            value="{{ old('departure_airport', $preparation->departure_airport) }}"
                                            placeholder="Contoh: Bandara Soekarno-Hatta"
                                            class="w-full rounded-lg border-gray-300 text-sm focus:border-yellow-400 focus:ring-yellow-400"
                                        >
                                    </div>

                                    <div>
                                        <label class="mb-2 block text-sm font-medium text-gray-700">
                                            Bandara Kedatangan
                                        </label>

                                        <input
                                            type="text"
                                            name="arrival_airport"
                                            value="{{ old('arrival_airport', $preparation->arrival_airport) }}"
                                            placeholder="Contoh: King Abdulaziz International Airport"
                                            class="w-full rounded-lg border-gray-300 text-sm focus:border-yellow-400 focus:ring-yellow-400"
                                        >
                                    </div>

                                    <div>
                                        <label class="mb-2 block text-sm font-medium text-gray-700">
                                            Waktu Keberangkatan
                                        </label>

                                        <input
                                            type="datetime-local"
                                            name="departure_at"
                                            value="{{ old('departure_at', $preparation->departure_at?->format('Y-m-d\TH:i')) }}"
                                            class="w-full rounded-lg border-gray-300 text-sm focus:border-yellow-400 focus:ring-yellow-400"
                                        >
                                    </div>

                                    <div>
                                        <label class="mb-2 block text-sm font-medium text-gray-700">
                                            Waktu Kedatangan
                                        </label>

                                        <input
                                            type="datetime-local"
                                            name="arrival_at"
                                            value="{{ old('arrival_at', $preparation->arrival_at?->format('Y-m-d\TH:i')) }}"
                                            class="w-full rounded-lg border-gray-300 text-sm focus:border-yellow-400 focus:ring-yellow-400"
                                        >
                                    </div>

                                </div>

                            </div>

                            <div class="border-t border-gray-100 pt-6">

                                <h3 class="mb-4 text-sm font-semibold text-gray-700">
                                    Penerbangan Kepulangan
                                </h3>

                                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                                    <div>
                                        <label class="mb-2 block text-sm font-medium text-gray-700">
                                            Nomor Penerbangan
                                        </label>

                                        <input
                                            type="text"
                                            name="return_flight_number"
                                            value="{{ old('return_flight_number', $preparation->return_flight_number) }}"
                                            placeholder="Contoh: GA 821"
                                            class="w-full rounded-lg border-gray-300 text-sm focus:border-yellow-400 focus:ring-yellow-400"
                                        >
                                    </div>

                                    <div>
                                        <label class="mb-2 block text-sm font-medium text-gray-700">
                                            Waktu Keberangkatan Pulang
                                        </label>

                                        <input
                                            type="datetime-local"
                                            name="return_departure_at"
                                            value="{{ old('return_departure_at', $preparation->return_departure_at?->format('Y-m-d\TH:i')) }}"
                                            class="w-full rounded-lg border-gray-300 text-sm focus:border-yellow-400 focus:ring-yellow-400"
                                        >
                                    </div>

                                    <div>
                                        <label class="mb-2 block text-sm font-medium text-gray-700">
                                            Waktu Kedatangan Pulang
                                        </label>

                                        <input
                                            type="datetime-local"
                                            name="return_arrival_at"
                                            value="{{ old('return_arrival_at', $preparation->return_arrival_at?->format('Y-m-d\TH:i')) }}"
                                            class="w-full rounded-lg border-gray-300 text-sm focus:border-yellow-400 focus:ring-yellow-400"
                                        >
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    {{-- Makkah Hotel --}}
                    <div class="mb-6 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                        <div class="border-b border-gray-200 px-6 py-5">
                            <h2 class="font-semibold text-gray-800">
                                Hotel Makkah
                            </h2>

                            <p class="mt-1 text-sm text-gray-500">
                                Informasi akomodasi jamaah selama berada di Makkah.
                            </p>
                        </div>

                        <div class="grid grid-cols-1 gap-5 p-6 md:grid-cols-2">

                            <div class="md:col-span-2">
                                <label class="mb-2 block text-sm font-medium text-gray-700">
                                    Nama Hotel
                                </label>

                                <input
                                    type="text"
                                    name="makkah_hotel_name"
                                    value="{{ old('makkah_hotel_name', $preparation->makkah_hotel_name) }}"
                                    placeholder="Masukkan nama hotel di Makkah"
                                    class="w-full rounded-lg border-gray-300 text-sm focus:border-yellow-400 focus:ring-yellow-400"
                                >
                            </div>

                            <div class="md:col-span-2">
                                <label class="mb-2 block text-sm font-medium text-gray-700">
                                    Alamat Hotel
                                </label>

                                <textarea
                                    name="makkah_hotel_address"
                                    rows="3"
                                    class="w-full rounded-lg border-gray-300 text-sm focus:border-yellow-400 focus:ring-yellow-400"
                                >{{ old('makkah_hotel_address', $preparation->makkah_hotel_address) }}</textarea>
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700">
                                    Check-in
                                </label>

                                <input
                                    type="date"
                                    name="makkah_check_in"
                                    value="{{ old('makkah_check_in', $preparation->makkah_check_in?->format('Y-m-d')) }}"
                                    class="w-full rounded-lg border-gray-300 text-sm focus:border-yellow-400 focus:ring-yellow-400"
                                >
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700">
                                    Check-out
                                </label>

                                <input
                                    type="date"
                                    name="makkah_check_out"
                                    value="{{ old('makkah_check_out', $preparation->makkah_check_out?->format('Y-m-d')) }}"
                                    class="w-full rounded-lg border-gray-300 text-sm focus:border-yellow-400 focus:ring-yellow-400"
                                >
                            </div>

                        </div>

                    </div>

                    {{-- Madinah Hotel --}}
                    <div class="mb-6 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                        <div class="border-b border-gray-200 px-6 py-5">
                            <h2 class="font-semibold text-gray-800">
                                Hotel Madinah
                            </h2>

                            <p class="mt-1 text-sm text-gray-500">
                                Informasi akomodasi jamaah selama berada di Madinah.
                            </p>
                        </div>

                        <div class="grid grid-cols-1 gap-5 p-6 md:grid-cols-2">

                            <div class="md:col-span-2">
                                <label class="mb-2 block text-sm font-medium text-gray-700">
                                    Nama Hotel
                                </label>

                                <input
                                    type="text"
                                    name="madinah_hotel_name"
                                    value="{{ old('madinah_hotel_name', $preparation->madinah_hotel_name) }}"
                                    placeholder="Masukkan nama hotel di Madinah"
                                    class="w-full rounded-lg border-gray-300 text-sm focus:border-yellow-400 focus:ring-yellow-400"
                                >
                            </div>

                            <div class="md:col-span-2">
                                <label class="mb-2 block text-sm font-medium text-gray-700">
                                    Alamat Hotel
                                </label>

                                <textarea
                                    name="madinah_hotel_address"
                                    rows="3"
                                    class="w-full rounded-lg border-gray-300 text-sm focus:border-yellow-400 focus:ring-yellow-400"
                                >{{ old('madinah_hotel_address', $preparation->madinah_hotel_address) }}</textarea>
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700">
                                    Check-in
                                </label>

                                <input
                                    type="date"
                                    name="madinah_check_in"
                                    value="{{ old('madinah_check_in', $preparation->madinah_check_in?->format('Y-m-d')) }}"
                                    class="w-full rounded-lg border-gray-300 text-sm focus:border-yellow-400 focus:ring-yellow-400"
                                >
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700">
                                    Check-out
                                </label>

                                <input
                                    type="date"
                                    name="madinah_check_out"
                                    value="{{ old('madinah_check_out', $preparation->madinah_check_out?->format('Y-m-d')) }}"
                                    class="w-full rounded-lg border-gray-300 text-sm focus:border-yellow-400 focus:ring-yellow-400"
                                >
                            </div>

                        </div>

                    </div>

                    {{-- Transportation and Equipment --}}
                    <div class="mb-6 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                        <div class="border-b border-gray-200 px-6 py-5">

                            <h2 class="font-semibold text-gray-800">
                                Transportasi & Perlengkapan
                            </h2>

                            <p class="mt-1 text-sm text-gray-500">
                                Catat kebutuhan transportasi dan perlengkapan perjalanan.
                            </p>

                        </div>

                        <div class="space-y-5 p-6">

                            <div>

                                <label class="mb-2 block text-sm font-medium text-gray-700">
                                    Detail Transportasi
                                </label>

                                <textarea
                                    name="transportation_details"
                                    rows="4"
                                    placeholder="Contoh: Bus dari bandara ke hotel, transportasi antarkota, dan transportasi ziarah..."
                                    class="w-full rounded-lg border-gray-300 text-sm focus:border-yellow-400 focus:ring-yellow-400"
                                >{{ old('transportation_details', $preparation->transportation_details) }}</textarea>

                            </div>

                            <div>

                                <label class="mb-2 block text-sm font-medium text-gray-700">
                                    Catatan Perlengkapan
                                </label>

                                <textarea
                                    name="equipment_notes"
                                    rows="4"
                                    placeholder="Contoh: Koper, seragam, kain ihram, perlengkapan ibadah, dan perlengkapan lainnya..."
                                    class="w-full rounded-lg border-gray-300 text-sm focus:border-yellow-400 focus:ring-yellow-400"
                                >{{ old('equipment_notes', $preparation->equipment_notes) }}</textarea>

                            </div>

                        </div>

                    </div>

                    {{-- Manasik --}}
                    <div class="mb-6 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                        <div class="border-b border-gray-200 px-6 py-5">

                            <h2 class="font-semibold text-gray-800">
                                Jadwal Manasik
                            </h2>

                            <p class="mt-1 text-sm text-gray-500">
                                Atur jadwal pelaksanaan kegiatan manasik jamaah.
                            </p>

                        </div>

                        <div class="grid grid-cols-1 gap-5 p-6 md:grid-cols-2">

                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700">
                                    Waktu Manasik
                                </label>

                                <input
                                    type="datetime-local"
                                    name="manasik_at"
                                    value="{{ old('manasik_at', $preparation->manasik_at?->format('Y-m-d\TH:i')) }}"
                                    class="w-full rounded-lg border-gray-300 text-sm focus:border-yellow-400 focus:ring-yellow-400"
                                >
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700">
                                    Lokasi Manasik
                                </label>

                                <input
                                    type="text"
                                    name="manasik_location"
                                    value="{{ old('manasik_location', $preparation->manasik_location) }}"
                                    placeholder="Masukkan lokasi manasik"
                                    class="w-full rounded-lg border-gray-300 text-sm focus:border-yellow-400 focus:ring-yellow-400"
                                >
                            </div>

                            <div class="md:col-span-2">

                                <label class="mb-2 block text-sm font-medium text-gray-700">
                                    Catatan Manasik
                                </label>

                                <textarea
                                    name="manasik_notes"
                                    rows="3"
                                    class="w-full rounded-lg border-gray-300 text-sm focus:border-yellow-400 focus:ring-yellow-400"
                                >{{ old('manasik_notes', $preparation->manasik_notes) }}</textarea>

                            </div>

                        </div>

                    </div>

                    {{-- Briefing --}}
                    <div class="mb-6 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                        <div class="border-b border-gray-200 px-6 py-5">

                            <h2 class="font-semibold text-gray-800">
                                Jadwal Briefing
                            </h2>

                            <p class="mt-1 text-sm text-gray-500">
                                Atur jadwal pengarahan akhir sebelum keberangkatan.
                            </p>

                        </div>

                        <div class="grid grid-cols-1 gap-5 p-6 md:grid-cols-2">

                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700">
                                    Waktu Briefing
                                </label>

                                <input
                                    type="datetime-local"
                                    name="briefing_at"
                                    value="{{ old('briefing_at', $preparation->briefing_at?->format('Y-m-d\TH:i')) }}"
                                    class="w-full rounded-lg border-gray-300 text-sm focus:border-yellow-400 focus:ring-yellow-400"
                                >
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700">
                                    Lokasi Briefing
                                </label>

                                <input
                                    type="text"
                                    name="briefing_location"
                                    value="{{ old('briefing_location', $preparation->briefing_location) }}"
                                    placeholder="Masukkan lokasi briefing"
                                    class="w-full rounded-lg border-gray-300 text-sm focus:border-yellow-400 focus:ring-yellow-400"
                                >
                            </div>

                            <div class="md:col-span-2">

                                <label class="mb-2 block text-sm font-medium text-gray-700">
                                    Catatan Briefing
                                </label>

                                <textarea
                                    name="briefing_notes"
                                    rows="3"
                                    class="w-full rounded-lg border-gray-300 text-sm focus:border-yellow-400 focus:ring-yellow-400"
                                >{{ old('briefing_notes', $preparation->briefing_notes) }}</textarea>

                            </div>

                        </div>

                    </div>

                    {{-- General Notes and Status --}}
                    <div class="mb-6 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                        <div class="border-b border-gray-200 px-6 py-5">
                            <h2 class="font-semibold text-gray-800">
                                Status & Catatan Persiapan
                            </h2>
                        </div>

                        <div class="grid grid-cols-1 gap-5 p-6 md:grid-cols-2">

                            <div>

                                <label class="mb-2 block text-sm font-semibold text-gray-700">
                                    Status Persiapan
                                </label>

                                <select
                                    name="status"
                                    required
                                    class="w-full rounded-lg border-gray-300 text-sm focus:border-yellow-400 focus:ring-yellow-400"
                                >

                                    <option value="draft" @selected(old('status', $preparation->status) === 'draft')>
                                        Draft
                                    </option>

                                    <option value="proses" @selected(old('status', $preparation->status) === 'proses')>
                                        Dalam Proses
                                    </option>

                                    <option value="siap_berangkat" @selected(old('status', $preparation->status) === 'siap_berangkat')>
                                        Siap Berangkat
                                    </option>

                                    <option value="selesai" @selected(old('status', $preparation->status) === 'selesai')>
                                        Selesai
                                    </option>

                                </select>

                            </div>

                            <div>

                                <label class="mb-2 block text-sm font-semibold text-gray-700">
                                    Catatan Umum
                                </label>

                                <textarea
                                    name="notes"
                                    rows="3"
                                    placeholder="Catatan tambahan persiapan..."
                                    class="w-full rounded-lg border-gray-300 text-sm focus:border-yellow-400 focus:ring-yellow-400"
                                >{{ old('notes', $preparation->notes) }}</textarea>

                            </div>

                        </div>

                        <div class="flex justify-end border-t border-gray-200 bg-gray-50 px-6 py-4">

                            <button
                                type="submit"
                                class="rounded-lg bg-yellow-400 px-6 py-2.5 text-sm font-semibold text-gray-900 transition hover:bg-yellow-500"
                            >
                                Simpan Informasi Persiapan
                            </button>

                        </div>

                    </div>

                </form>

                {{-- Manifest Jamaah --}}
                <div class="mb-6 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                    <div class="border-b border-gray-200 px-6 py-5">

                        <h2 class="font-semibold text-gray-800">
                            Manifest Jamaah
                        </h2>

                        <p class="mt-1 text-sm text-gray-500">
                            Daftar jamaah yang tergabung dalam kelompok pada jadwal ini.
                        </p>

                    </div>

                    <div class="overflow-x-auto">

                        <table class="w-full min-w-[800px] text-left text-sm">

                            <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                                <tr>
                                    <th class="px-6 py-4">No.</th>
                                    <th class="px-6 py-4">Nama Jamaah</th>
                                    <th class="px-6 py-4">Nomor HP</th>
                                    <th class="px-6 py-4">Kelompok</th>
                                    <th class="px-6 py-4">Status Anggota</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-100">

                                @php
                                    $manifestNumber = 1;
                                @endphp

                                @forelse ($preparation->schedule?->groups ?? [] as $group)

                                    @foreach ($group->members as $member)

                                        <tr class="hover:bg-gray-50">

                                            <td class="px-6 py-5 text-gray-500">
                                                {{ $manifestNumber++ }}
                                            </td>

                                            <td class="px-6 py-5 font-semibold text-gray-800">
                                                {{ $member->name }}
                                            </td>

                                            <td class="px-6 py-5 text-gray-600">
                                                {{ $member->phone ?? '-' }}
                                            </td>

                                            <td class="px-6 py-5 text-gray-600">
                                                {{ $group->name }}
                                            </td>

                                            <td class="px-6 py-5">
                                                <span class="rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">
                                                    {{ ucfirst($member->pivot->status) }}
                                                </span>
                                            </td>

                                        </tr>

                                    @endforeach

                                @empty

                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                            Belum ada kelompok pada jadwal ini.
                                        </td>
                                    </tr>

                                @endforelse

                                @if (($preparation->schedule?->groups->sum(fn($group) => $group->members->count()) ?? 0) === 0)
                                    <tr>
                                        <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-400">
                                            Tambahkan jamaah melalui menu Kelompok & Muthawif untuk mengisi manifest.
                                        </td>
                                    </tr>
                                @endif

                            </tbody>

                        </table>

                    </div>

                </div>

                {{-- Room List --}}
                @php
                    // Jamaah aktif dari seluruh kelompok pada jadwal keberangkatan ini.
                    $roomJamaahs = ($preparation->schedule?->groups ?? collect())
                        ->flatMap(fn ($group) => $group->members)
                        ->filter(fn ($jamaah) => ($jamaah->pivot->status ?? null) === 'aktif')
                        ->unique('id')
                        ->values();

                    $roomTypes = [
                        'quad' => 'Quad (4 Orang)',
                        'triple' => 'Triple (3 Orang)',
                        'double' => 'Double (2 Orang)',
                        'single' => 'Single (1 Orang)',
                    ];
                @endphp

                <div class="mb-6 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                    {{-- Header --}}
                    <div class="border-b border-gray-200 px-6 py-5">
                        <h2 class="font-semibold text-gray-800">Room List</h2>
                        <p class="mt-1 text-sm text-gray-500">
                            Atur penempatan kamar jamaah selama berada di Makkah dan Madinah.
                        </p>
                    </div>

                    {{-- Form Tambah Room Assignment --}}
                    <div class="border-b border-gray-100 bg-gray-50 p-6">
                        <h3 class="mb-4 text-sm font-semibold text-gray-800">
                            Tambahkan Pembagian Kamar
                        </h3>

                        @if ($roomJamaahs->isNotEmpty())
                            <form
                                action="{{ route('karyawan.departures.rooms.store', $preparation) }}"
                                method="POST"
                                class="space-y-4"
                            >
                                @csrf

                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                                    <div>
                                        <label class="mb-2 block text-sm font-medium text-gray-700">
                                            Nama Jamaah <span class="text-red-500">*</span>
                                        </label>
                                        <select
                                            name="jamaah_id"
                                            required
                                            class="w-full rounded-lg border-gray-300 text-sm focus:border-yellow-400 focus:ring-yellow-400"
                                        >
                                            <option value="">Pilih Jamaah</option>
                                            @foreach ($roomJamaahs as $jamaah)
                                                <option value="{{ $jamaah->id }}" @selected(old('jamaah_id') == $jamaah->id)>
                                                    {{ $jamaah->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label class="mb-2 block text-sm font-medium text-gray-700">
                                            Lokasi Hotel <span class="text-red-500">*</span>
                                        </label>
                                        <select
                                            name="city"
                                            required
                                            class="w-full rounded-lg border-gray-300 text-sm focus:border-yellow-400 focus:ring-yellow-400"
                                        >
                                            <option value="">Pilih Lokasi</option>
                                            <option value="makkah" @selected(old('city') === 'makkah')>Makkah</option>
                                            <option value="madinah" @selected(old('city') === 'madinah')>Madinah</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="mb-2 block text-sm font-medium text-gray-700">
                                            Nomor Kamar <span class="text-red-500">*</span>
                                        </label>
                                        <input
                                            type="text"
                                            name="room_number"
                                            value="{{ old('room_number') }}"
                                            required
                                            maxlength="50"
                                            placeholder="Contoh: 301"
                                            class="w-full rounded-lg border-gray-300 text-sm focus:border-yellow-400 focus:ring-yellow-400"
                                        >
                                    </div>

                                    <div>
                                        <label class="mb-2 block text-sm font-medium text-gray-700">
                                            Tipe Kamar <span class="text-red-500">*</span>
                                        </label>
                                        <select
                                            name="room_type"
                                            required
                                            class="w-full rounded-lg border-gray-300 text-sm focus:border-yellow-400 focus:ring-yellow-400"
                                        >
                                            <option value="">Pilih Tipe Kamar</option>
                                            @foreach ($roomTypes as $value => $label)
                                                <option value="{{ $value }}" @selected(old('room_type') === $value)>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="md:col-span-2">
                                        <label class="mb-2 block text-sm font-medium text-gray-700">
                                            Catatan (Opsional)
                                        </label>
                                        <input
                                            type="text"
                                            name="notes"
                                            value="{{ old('notes') }}"
                                            placeholder="Catatan tambahan penempatan kamar"
                                            class="w-full rounded-lg border-gray-300 text-sm focus:border-yellow-400 focus:ring-yellow-400"
                                        >
                                    </div>
                                </div>

                                <div class="flex justify-end">
                                    <button
                                        type="submit"
                                        class="rounded-lg bg-yellow-400 px-5 py-2.5 text-sm font-semibold text-gray-900 transition hover:bg-yellow-500"
                                    >
                                        + Tambahkan ke Room List
                                    </button>
                                </div>
                            </form>
                        @else
                            <div class="rounded-lg border border-yellow-200 bg-yellow-50 p-4">
                                <p class="text-sm font-medium text-gray-800">
                                    Belum ada jamaah aktif dalam manifest.
                                </p>
                                <p class="mt-1 text-xs text-gray-600">
                                    Tambahkan jamaah ke kelompok melalui menu Kelompok &amp; Muthawif terlebih dahulu.
                                </p>
                            </div>
                        @endif
                    </div>

                    {{-- Tabel Room List --}}
                    <div class="border-b border-gray-200 px-6 py-5">
                        <h3 class="font-semibold text-gray-800">Daftar Penempatan Kamar</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Informasi kamar setiap jamaah pada masing-masing kota.
                        </p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[1050px] text-left text-sm">
                            <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
                                <tr>
                                    <th class="px-6 py-4">No.</th>
                                    <th class="px-6 py-4">Nama Jamaah</th>
                                    <th class="px-6 py-4">Lokasi</th>
                                    <th class="px-6 py-4">Nomor Kamar</th>
                                    <th class="px-6 py-4">Tipe Kamar</th>
                                    <th class="px-6 py-4">Catatan</th>
                                    <th class="px-6 py-4">Aksi</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-100">
                                @forelse ($preparation->roomAssignments as $index => $room)
                                    <tr class="align-top hover:bg-gray-50">
                                        <td class="px-6 py-5 text-gray-500">
                                            {{ $index + 1 }}
                                        </td>

                                        <td class="px-6 py-5 font-semibold text-gray-800">
                                            {{ $room->jamaah?->name ?? '-' }}
                                        </td>

                                        <td class="px-6 py-5">
                                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $room->city === 'makkah' ? 'bg-green-50 text-green-700' : 'bg-blue-50 text-blue-700' }}">
                                                {{ ucfirst($room->city) }}
                                            </span>
                                        </td>

                                        <td class="px-6 py-5 font-medium text-gray-800">
                                            {{ $room->room_number }}
                                        </td>

                                        <td class="px-6 py-5 text-gray-600">
                                            {{ $roomTypes[$room->room_type] ?? '-' }}
                                        </td>

                                        <td class="px-6 py-5 text-gray-500">
                                            {{ $room->notes ?: '-' }}
                                        </td>

                                        <td class="px-6 py-5">
                                            <div class="w-64 space-y-3">
                                                {{-- Form Edit --}}
                                                <details>
                                                    <summary class="cursor-pointer text-xs font-semibold text-blue-600 hover:text-blue-800">
                                                        Ubah Penempatan
                                                    </summary>

                                                    <form
                                                        action="{{ route('karyawan.departures.rooms.update', [$preparation, $room]) }}"
                                                        method="POST"
                                                        class="mt-3 space-y-2 rounded-lg border border-gray-200 bg-white p-3"
                                                    >
                                                        @csrf
                                                        @method('PUT')

                                                        <label class="block text-xs font-medium text-gray-600">
                                                            Nama Jamaah
                                                        </label>
                                                        <select
                                                            name="jamaah_id"
                                                            required
                                                            class="w-full rounded-lg border-gray-300 text-xs"
                                                        >
                                                            @foreach ($roomJamaahs as $jamaah)
                                                                <option value="{{ $jamaah->id }}" @selected($room->jamaah_id == $jamaah->id)>
                                                                    {{ $jamaah->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>

                                                        <label class="block text-xs font-medium text-gray-600">
                                                            Lokasi Hotel
                                                        </label>
                                                        <select
                                                            name="city"
                                                            required
                                                            class="w-full rounded-lg border-gray-300 text-xs"
                                                        >
                                                            <option value="makkah" @selected($room->city === 'makkah')>Makkah</option>
                                                            <option value="madinah" @selected($room->city === 'madinah')>Madinah</option>
                                                        </select>

                                                        <label class="block text-xs font-medium text-gray-600">
                                                            Nomor Kamar
                                                        </label>
                                                        <input
                                                            type="text"
                                                            name="room_number"
                                                            value="{{ $room->room_number }}"
                                                            required
                                                            maxlength="50"
                                                            class="w-full rounded-lg border-gray-300 text-xs"
                                                        >

                                                        <label class="block text-xs font-medium text-gray-600">
                                                            Tipe Kamar
                                                        </label>
                                                        <select
                                                            name="room_type"
                                                            required
                                                            class="w-full rounded-lg border-gray-300 text-xs"
                                                        >
                                                            @foreach ($roomTypes as $value => $label)
                                                                <option value="{{ $value }}" @selected($room->room_type === $value)>
                                                                    {{ $label }}
                                                                </option>
                                                            @endforeach
                                                        </select>

                                                        <label class="block text-xs font-medium text-gray-600">
                                                            Catatan
                                                        </label>
                                                        <input
                                                            type="text"
                                                            name="notes"
                                                            value="{{ $room->notes }}"
                                                            class="w-full rounded-lg border-gray-300 text-xs"
                                                        >

                                                        <button
                                                            type="submit"
                                                            class="w-full rounded-lg bg-yellow-400 px-3 py-2 text-xs font-semibold text-gray-900 hover:bg-yellow-500"
                                                        >
                                                            Simpan Perubahan
                                                        </button>
                                                    </form>
                                                </details>

                                                {{-- Form Hapus --}}
                                                <form
                                                    action="{{ route('karyawan.departures.rooms.destroy', [$preparation, $room]) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus penempatan kamar jamaah ini?')"
                                                >
                                                    @csrf
                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        class="w-full rounded-lg border border-red-200 bg-white px-3 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-50"
                                                    >
                                                        Hapus Penempatan
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-14 text-center">
                                            <p class="font-semibold text-gray-700">
                                                Belum ada pembagian kamar jamaah.
                                            </p>
                                            <p class="mt-1 text-sm text-gray-400">
                                                Gunakan formulir di atas untuk mengatur penempatan kamar.
                                            </p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="border-t border-gray-200 px-6 py-4">
                        <p class="text-xs text-gray-500">
                            Kapasitas kamar: Quad 4 orang, Triple 3 orang, Double 2 orang, dan Single 1 orang.
                            Satu jamaah dapat memiliki penempatan berbeda di Makkah dan Madinah.
                        </p>
                    </div>
                </div>

                {{-- Departure Checklist --}}
@php
    // Mengambil seluruh jamaah dari kelompok pada jadwal ini.
    $manifestJamaahs = ($preparation->schedule?->groups ?? collect())
        ->flatMap(fn ($group) => $group->members)
        ->unique('id')
        ->values();

    $checklistCategories = [
        'administrasi' => 'Administrasi',
        'perlengkapan' => 'Perlengkapan',
        'manasik' => 'Manasik',
        'briefing' => 'Briefing',
        'final_checking' => 'Final Checking',
    ];

    $checklistStatuses = [
        'belum' => 'Belum',
        'proses' => 'Dalam Proses',
        'selesai' => 'Selesai',
    ];

    $checklistStatusStyles = [
        'belum' => 'bg-gray-100 text-gray-600',
        'proses' => 'bg-orange-50 text-orange-700',
        'selesai' => 'bg-green-50 text-green-700',
    ];
@endphp

<div class="mb-6 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

    {{-- Header --}}
    <div class="border-b border-gray-200 px-6 py-5">

        <h2 class="font-semibold text-gray-800">
            Checklist Kesiapan Jamaah
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            Tambahkan dan pantau item pemeriksaan kesiapan setiap jamaah.
        </p>

    </div>

    {{-- Add Checklist Form --}}
    <div class="border-b border-gray-100 bg-gray-50 p-6">

        <h3 class="mb-4 text-sm font-semibold text-gray-800">
            Tambahkan Item Checklist
        </h3>

        @if ($manifestJamaahs->isNotEmpty())

            <form
                action="{{ route('karyawan.departures.checklists.store', $preparation) }}"
                method="POST"
                class="space-y-4"
            >

                @csrf

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                    {{-- Jamaah --}}
                    <div>

                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Nama Jamaah
                            <span class="text-red-500">*</span>
                        </label>

                        <select
                            name="jamaah_id"
                            required
                            class="w-full rounded-lg border-gray-300 text-sm focus:border-yellow-400 focus:ring-yellow-400"
                        >

                            <option value="">
                                Pilih Jamaah
                            </option>

                            @foreach ($manifestJamaahs as $jamaah)

                                <option
                                    value="{{ $jamaah->id }}"
                                    @selected(old('jamaah_id') == $jamaah->id)
                                >
                                    {{ $jamaah->name }} — {{ $jamaah->phone }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                    {{-- Category --}}
                    <div>

                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Kategori Pemeriksaan
                            <span class="text-red-500">*</span>
                        </label>

                        <select
                            name="category"
                            required
                            class="w-full rounded-lg border-gray-300 text-sm focus:border-yellow-400 focus:ring-yellow-400"
                        >

                            <option value="">
                                Pilih Kategori
                            </option>

                            @foreach ($checklistCategories as $value => $label)

                                <option
                                    value="{{ $value }}"
                                    @selected(old('category') === $value)
                                >
                                    {{ $label }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                    {{-- Item Name --}}
                    <div>

                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Nama Item Pemeriksaan
                            <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="text"
                            name="item_name"
                            value="{{ old('item_name') }}"
                            required
                            maxlength="255"
                            placeholder="Contoh: Koper jamaah"
                            class="w-full rounded-lg border-gray-300 text-sm focus:border-yellow-400 focus:ring-yellow-400"
                        >

                    </div>

                    {{-- Initial Status --}}
                    <div>

                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Status Awal
                        </label>

                        <select
                            name="status"
                            required
                            class="w-full rounded-lg border-gray-300 text-sm focus:border-yellow-400 focus:ring-yellow-400"
                        >

                            <option value="belum" @selected(old('status', 'belum') === 'belum')>
                                Belum
                            </option>

                            <option value="proses" @selected(old('status') === 'proses')>
                                Dalam Proses
                            </option>

                            <option value="selesai" @selected(old('status') === 'selesai')>
                                Selesai
                            </option>

                        </select>

                    </div>

                </div>

                {{-- Notes --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Catatan Pemeriksaan
                    </label>

                    <textarea
                        name="notes"
                        rows="2"
                        placeholder="Catatan tambahan apabila diperlukan..."
                        class="w-full rounded-lg border-gray-300 text-sm focus:border-yellow-400 focus:ring-yellow-400"
                    >{{ old('notes') }}</textarea>

                </div>

                {{-- Required Checkbox --}}
                <div class="flex items-center gap-2">

                    <input
                        type="hidden"
                        name="is_required"
                        value="0"
                    >

                    <input
                        type="checkbox"
                        name="is_required"
                        value="1"
                        id="is_required"
                        checked
                        class="rounded border-gray-300 text-yellow-500 focus:ring-yellow-400"
                    >

                    <label for="is_required" class="text-sm text-gray-600">
                        Item ini wajib dipenuhi sebelum keberangkatan
                    </label>

                </div>

                {{-- Submit --}}
                <div class="flex justify-end">

                    <button
                        type="submit"
                        class="rounded-lg bg-yellow-400 px-5 py-2.5 text-sm font-semibold text-gray-900 transition hover:bg-yellow-500"
                    >
                        + Tambahkan Checklist
                    </button>

                </div>

            </form>

        @else

            <div class="rounded-lg border border-yellow-200 bg-yellow-50 p-4">

                <p class="text-sm font-medium text-gray-800">
                    Belum ada anggota kelompok.
                </p>

                <p class="mt-1 text-xs text-gray-600">
                    Tambahkan jamaah melalui menu Kelompok & Muthawif terlebih dahulu sebelum membuat checklist.
                </p>

            </div>

        @endif

    </div>

    {{-- Checklist Table --}}
    <div class="border-b border-gray-200 px-6 py-5">

        <h3 class="font-semibold text-gray-800">
            Daftar Pemeriksaan Jamaah
        </h3>

        <p class="mt-1 text-sm text-gray-500">
            Perbarui status setiap item setelah pemeriksaan dilakukan.
        </p>

    </div>

    <div class="overflow-x-auto">

        <table class="w-full min-w-[1100px] text-left text-sm">

            <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500">

                <tr>
                    <th class="px-6 py-4">No.</th>
                    <th class="px-6 py-4">Nama Jamaah</th>
                    <th class="px-6 py-4">Kategori</th>
                    <th class="px-6 py-4">Item Pemeriksaan</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Petugas</th>
                    <th class="px-6 py-4">Aksi</th>
                </tr>

            </thead>

            <tbody class="divide-y divide-gray-100">

                @forelse ($preparation->checklists as $index => $checklist)

                    <tr class="align-top hover:bg-gray-50">

                        {{-- Number --}}
                        <td class="px-6 py-5 text-gray-500">
                            {{ $index + 1 }}
                        </td>

                        {{-- Jamaah --}}
                        <td class="px-6 py-5">

                            <p class="font-semibold text-gray-800">
                                {{ $checklist->jamaah?->name ?? '-' }}
                            </p>

                        </td>

                        {{-- Category --}}
                        <td class="px-6 py-5 text-gray-600">
                            {{ $checklistCategories[$checklist->category] ?? ucfirst($checklist->category) }}
                        </td>

                        {{-- Item --}}
                        <td class="px-6 py-5">

                            <p class="font-medium text-gray-800">
                                {{ $checklist->item_name }}
                            </p>

                            <p class="mt-1 text-xs text-gray-400">
                                {{ $checklist->is_required ? 'Wajib dipenuhi' : 'Opsional' }}
                            </p>

                        </td>

                        {{-- Status --}}
                        <td class="px-6 py-5">

                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $checklistStatusStyles[$checklist->status] ?? 'bg-gray-100 text-gray-600' }}">
                                {{ $checklistStatuses[$checklist->status] ?? ucfirst($checklist->status) }}
                            </span>

                            @if ($checklist->checked_at)
                                <p class="mt-1 text-xs text-gray-400">
                                    {{ $checklist->checked_at->format('d M Y, H:i') }}
                                </p>
                            @endif

                        </td>

                        {{-- Checker --}}
                        <td class="px-6 py-5 text-gray-600">

                            {{ $checklist->checker?->name ?? '-' }}

                        </td>

                        {{-- Actions --}}
                        <td class="px-6 py-5">

                            <div class="w-64 space-y-3">

                                {{-- Update Checklist --}}
                                <form
                                    action="{{ route('karyawan.departures.checklists.update', [$preparation, $checklist]) }}"
                                    method="POST"
                                    class="space-y-2"
                                >

                                    @csrf
                                    @method('PUT')

                                    <input
                                        type="hidden"
                                        name="jamaah_id"
                                        value="{{ $checklist->jamaah_id }}"
                                    >

                                    <input
                                        type="hidden"
                                        name="category"
                                        value="{{ $checklist->category }}"
                                    >

                                    <input
                                        type="hidden"
                                        name="item_name"
                                        value="{{ $checklist->item_name }}"
                                    >

                                    <input
                                        type="hidden"
                                        name="is_required"
                                        value="0"
                                    >

                                    <label class="block text-xs font-medium text-gray-600">
                                        Perbarui Status
                                    </label>

                                    <select
                                        name="status"
                                        required
                                        class="w-full rounded-lg border-gray-300 text-xs focus:border-yellow-400 focus:ring-yellow-400"
                                    >

                                        <option value="belum" @selected($checklist->status === 'belum')>
                                            Belum
                                        </option>

                                        <option value="proses" @selected($checklist->status === 'proses')>
                                            Dalam Proses
                                        </option>

                                        <option value="selesai" @selected($checklist->status === 'selesai')>
                                            Selesai
                                        </option>

                                    </select>

                                    <label class="flex items-center gap-2 text-xs text-gray-600">

                                        <input
                                            type="checkbox"
                                            name="is_required"
                                            value="1"
                                            @checked($checklist->is_required)
                                            class="rounded border-gray-300 text-yellow-500 focus:ring-yellow-400"
                                        >

                                        Wajib dipenuhi

                                    </label>

                                    <input
                                        type="text"
                                        name="notes"
                                        value="{{ $checklist->notes }}"
                                        maxlength="1000"
                                        placeholder="Catatan pemeriksaan"
                                        class="w-full rounded-lg border-gray-300 text-xs focus:border-yellow-400 focus:ring-yellow-400"
                                    >

                                    <button
                                        type="submit"
                                        class="w-full rounded-lg bg-gray-900 px-3 py-2 text-xs font-semibold text-white transition hover:bg-gray-700"
                                    >
                                        Simpan Perubahan
                                    </button>

                                </form>

                                {{-- Delete Checklist --}}
                                <form
                                    action="{{ route('karyawan.departures.checklists.destroy', [$preparation, $checklist]) }}"
                                    method="POST"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus item checklist ini?')"
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="w-full rounded-lg border border-red-200 bg-white px-3 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-50"
                                    >
                                        Hapus Checklist
                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="7" class="px-6 py-14 text-center">

                            <p class="font-semibold text-gray-700">
                                Belum ada checklist kesiapan jamaah.
                            </p>

                            <p class="mt-1 text-sm text-gray-400">
                                Tambahkan item pemeriksaan melalui formulir di atas.
                            </p>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    {{-- Footer --}}
    <div class="border-t border-gray-200 px-6 py-4">

        <p class="text-xs text-gray-500">
            Checklist mencatat pemeriksaan setiap jamaah. Item wajib harus diselesaikan sebelum proses final checking dinyatakan lengkap.
        </p>

    </div>

</div>


                <p class="mt-5 text-xs text-gray-500">
                    Manifest diambil dari kelompok jamaah. Room List dan Checklist dikelola melalui bagian masing-masing pada halaman ini.
                </p>

            </div>

        </main>

    </div>

</body>
</html>
