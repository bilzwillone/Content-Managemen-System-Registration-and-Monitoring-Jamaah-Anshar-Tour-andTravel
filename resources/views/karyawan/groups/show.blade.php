
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Detail Kelompok - Ansar Travel</title>

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
                        href="{{ route('karyawan.groups.index') }}"
                        class="mb-4 inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-800"
                    >
                        ← Kembali ke Kelompok Jamaah
                    </a>

                    <div class="flex flex-wrap items-start justify-between gap-4">

                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">
                                {{ $group->name }}
                            </h1>

                            <p class="mt-1 text-sm text-gray-500">
                                Kode Kelompok: {{ $group->group_code }}
                            </p>
                        </div>

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

                            $primaryAssignment = $group->muthawifAssignments
                                ->firstWhere('assignment_type', 'utama');

                            $assistantAssignment = $group->muthawifAssignments
                                ->firstWhere('assignment_type', 'pendamping');
                        @endphp

                        <span class="inline-flex rounded-full px-4 py-2 text-sm font-semibold {{ $statusStyles[$group->status] ?? 'bg-gray-100 text-gray-600' }}">
                            {{ $statusLabels[$group->status] ?? ucfirst($group->status) }}
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
                            Terdapat kesalahan:
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
                            Anggota Kelompok
                        </p>

                        <h2 class="mt-2 text-2xl font-bold text-gray-800">
                            {{ $group->members->count() }}
                        </h2>

                        <p class="mt-1 text-xs text-gray-400">
                            Jamaah terdaftar dalam kelompok
                        </p>
                    </div>

                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                        <p class="text-sm text-gray-500">
                            Muthawif Ditugaskan
                        </p>

                        <h2 class="mt-2 text-2xl font-bold text-gray-800">
                            {{ $group->muthawifAssignments->count() }} / 2
                        </h2>

                        <p class="mt-1 text-xs text-gray-400">
                            Pembimbing utama dan pendamping
                        </p>
                    </div>

                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                        <p class="text-sm text-gray-500">
                            Jadwal Keberangkatan
                        </p>

                        <h2 class="mt-2 text-xl font-bold text-gray-800">
                            {{ $group->schedule?->departure_date?->format('d M Y') ?? '-' }}
                        </h2>

                        <p class="mt-1 text-xs text-gray-400">
                            Tanggal keberangkatan
                        </p>
                    </div>

                </div>

                {{-- Schedule Information --}}
                <div class="mb-6 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                    <div class="border-b border-gray-200 px-6 py-5">
                        <h2 class="font-semibold text-gray-800">
                            Informasi Jadwal Perjalanan
                        </h2>
                    </div>

                    <div class="grid grid-cols-1 gap-5 p-6 sm:grid-cols-2 lg:grid-cols-4">

                        <div>
                            <p class="text-xs text-gray-400">
                                Nama Jadwal
                            </p>

                            <p class="mt-1 font-medium text-gray-800">
                                {{ $group->schedule?->name ?? '-' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400">
                                Jenis Perjalanan
                            </p>

                            <p class="mt-1 font-medium text-gray-800">
                                {{ ucfirst($group->schedule?->type ?? '-') }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400">
                                Tanggal Berangkat
                            </p>

                            <p class="mt-1 font-medium text-gray-800">
                                {{ $group->schedule?->departure_date?->format('d M Y') ?? '-' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400">
                                Tanggal Kembali
                            </p>

                            <p class="mt-1 font-medium text-gray-800">
                                {{ $group->schedule?->return_date?->format('d M Y') ?? '-' }}
                            </p>
                        </div>

                    </div>

                </div>

                {{-- Group Settings --}}
                <div class="mb-6 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                    <div class="border-b border-gray-200 px-6 py-5">

                        <h2 class="font-semibold text-gray-800">
                            Pengaturan Kelompok
                        </h2>

                        <p class="mt-1 text-sm text-gray-500">
                            Perbarui informasi dan status kelompok jamaah.
                        </p>

                    </div>

                    <form
                        action="{{ route('karyawan.groups.update', $group) }}"
                        method="POST"
                        class="space-y-5 p-6"
                    >

                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                            <div>

                                <label class="mb-2 block text-sm font-semibold text-gray-700">
                                    Nama Kelompok
                                </label>

                                <input
                                    type="text"
                                    name="name"
                                    value="{{ old('name', $group->name) }}"
                                    required
                                    maxlength="255"
                                    class="w-full rounded-lg border-gray-300 text-sm focus:border-yellow-400 focus:ring-yellow-400"
                                >

                            </div>

                            <div>

                                <label class="mb-2 block text-sm font-semibold text-gray-700">
                                    Status Kelompok
                                </label>

                                <select
                                    name="status"
                                    required
                                    class="w-full rounded-lg border-gray-300 text-sm focus:border-yellow-400 focus:ring-yellow-400"
                                >

                                    <option value="persiapan" @selected(old('status', $group->status) === 'persiapan')>
                                        Persiapan
                                    </option>

                                    <option value="siap_berangkat" @selected(old('status', $group->status) === 'siap_berangkat')>
                                        Siap Berangkat
                                    </option>

                                    <option value="berangkat" @selected(old('status', $group->status) === 'berangkat')>
                                        Berangkat
                                    </option>

                                    <option value="selesai" @selected(old('status', $group->status) === 'selesai')>
                                        Selesai
                                    </option>

                                    <option value="dibatalkan" @selected(old('status', $group->status) === 'dibatalkan')>
                                        Dibatalkan
                                    </option>

                                </select>

                            </div>

                        </div>

                        <div>

                            <label class="mb-2 block text-sm font-semibold text-gray-700">
                                Catatan Kelompok
                            </label>

                            <textarea
                                name="notes"
                                rows="3"
                                class="w-full rounded-lg border-gray-300 text-sm focus:border-yellow-400 focus:ring-yellow-400"
                            >{{ old('notes', $group->notes) }}</textarea>

                        </div>

                        <div class="flex justify-end">

                            <button
                                type="submit"
                                class="rounded-lg bg-yellow-400 px-5 py-2.5 text-sm font-semibold text-gray-900 transition hover:bg-yellow-500"
                            >
                                Simpan Perubahan
                            </button>

                        </div>

                    </form>

                </div>

                {{-- Members Section --}}
                <div class="mb-6 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                    <div class="border-b border-gray-200 px-6 py-5">

                        <h2 class="font-semibold text-gray-800">
                            Anggota Jamaah
                        </h2>

                        <p class="mt-1 text-sm text-gray-500">
                            Kelola jamaah yang tergabung dalam kelompok ini.
                        </p>

                    </div>

                    {{-- Add Member Form --}}
                    <div class="border-b border-gray-100 bg-gray-50 p-6">

                        <form
                            action="{{ route('karyawan.groups.members.store', $group) }}"
                            method="POST"
                            class="grid grid-cols-1 items-end gap-4 md:grid-cols-3"
                        >

                            @csrf

                            <div class="md:col-span-1">

                                <label class="mb-2 block text-sm font-semibold text-gray-700">
                                    Pilih Jamaah
                                </label>

                                <select
                                    name="jamaah_id"
                                    required
                                    class="w-full rounded-lg border-gray-300 text-sm focus:border-yellow-400 focus:ring-yellow-400"
                                >

                                    <option value="">
                                        Pilih jamaah
                                    </option>

                                    @foreach ($availableJamaahs as $jamaah)

                                        <option value="{{ $jamaah->id }}">
                                            {{ $jamaah->name }} — {{ $jamaah->phone }}
                                        </option>

                                    @endforeach

                                </select>

                            </div>

                            <div>

                                <label class="mb-2 block text-sm font-semibold text-gray-700">
                                    Catatan (Opsional)
                                </label>

                                <input
                                    type="text"
                                    name="notes"
                                    placeholder="Catatan anggota"
                                    class="w-full rounded-lg border-gray-300 text-sm focus:border-yellow-400 focus:ring-yellow-400"
                                >

                            </div>

                            <div>

                                <button
                                    type="submit"
                                    @disabled($availableJamaahs->isEmpty())
                                    class="w-full rounded-lg bg-yellow-400 px-4 py-2.5 text-sm font-semibold text-gray-900 transition hover:bg-yellow-500 disabled:cursor-not-allowed disabled:opacity-50"
                                >
                                    + Tambahkan Jamaah
                                </button>

                            </div>

                        </form>

                        @if ($availableJamaahs->isEmpty())
                            <p class="mt-3 text-xs text-gray-500">
                                Tidak ada jamaah yang tersedia untuk ditambahkan pada jadwal keberangkatan ini.
                            </p>
                        @endif

                    </div>

                    {{-- Members Table --}}
                    <div class="overflow-x-auto">

                        <table class="w-full min-w-[750px] text-left text-sm">

                            <thead class="bg-gray-50 text-xs uppercase text-gray-500">

                                <tr>
                                    <th class="px-6 py-4">No.</th>
                                    <th class="px-6 py-4">Nama Jamaah</th>
                                    <th class="px-6 py-4">Nomor HP</th>
                                    <th class="px-6 py-4">Status Anggota</th>
                                    <th class="px-6 py-4">Catatan</th>
                                    <th class="px-6 py-4">Aksi</th>
                                </tr>

                            </thead>

                            <tbody class="divide-y divide-gray-100">

                                @forelse ($group->memberRecords as $index => $member)

                                    <tr class="hover:bg-gray-50">

                                        <td class="px-6 py-5 text-gray-500">
                                            {{ $index + 1 }}
                                        </td>

                                        <td class="px-6 py-5 font-semibold text-gray-800">
                                            {{ $member->jamaah?->name ?? 'Jamaah tidak ditemukan' }}
                                        </td>

                                        <td class="px-6 py-5 text-gray-600">
                                            {{ $member->jamaah?->phone ?? '-' }}
                                        </td>

                                        <td class="px-6 py-5">

                                            <span class="rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">
                                                {{ ucfirst($member->status) }}
                                            </span>

                                        </td>

                                        <td class="px-6 py-5 text-gray-500">
                                            {{ $member->notes ?: '-' }}
                                        </td>

                                        <td class="px-6 py-5">

                                            <form
                                                action="{{ route('karyawan.groups.members.destroy', [$group, $member]) }}"
                                                method="POST"
                                                onsubmit="return confirm('Keluarkan jamaah ini dari kelompok?')"
                                            >

                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="text-xs font-semibold text-red-600 transition hover:text-red-800"
                                                >
                                                    Keluarkan
                                                </button>

                                            </form>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center">

                                            <p class="font-medium text-gray-600">
                                                Belum ada anggota jamaah.
                                            </p>

                                            <p class="mt-1 text-sm text-gray-400">
                                                Tambahkan jamaah menggunakan formulir di atas.
                                            </p>

                                        </td>
                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

                {{-- Muthawif Assignment --}}
                <div class="mb-6 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                    <div class="border-b border-gray-200 px-6 py-5">

                        <h2 class="font-semibold text-gray-800">
                            Penugasan Muthawif
                        </h2>

                        <p class="mt-1 text-sm text-gray-500">
                            Setiap kelompok memiliki satu Muthawif utama dan satu Muthawif pendamping.
                        </p>

                    </div>

                    <form
                        action="{{ route('karyawan.groups.muthawifs.update', $group) }}"
                        method="POST"
                        class="space-y-5 p-6"
                    >

                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                            {{-- Primary Muthawif --}}
                            <div>

                                <label class="mb-2 block text-sm font-semibold text-gray-700">
                                    Muthawif Utama
                                </label>

                                <select
                                    name="muthawif_ids[]"
                                    required
                                    class="w-full rounded-lg border-gray-300 text-sm focus:border-yellow-400 focus:ring-yellow-400"
                                >

                                    <option value="">
                                        Pilih Muthawif Utama
                                    </option>

                                    @foreach ($availableMuthawifs as $muthawif)

                                        <option
                                            value="{{ $muthawif->id }}"
                                            @selected($primaryAssignment?->user_id == $muthawif->id)
                                        >
                                            {{ $muthawif->name }}
                                        </option>

                                    @endforeach

                                </select>

                            </div>

                            {{-- Assistant Muthawif --}}
                            <div>

                                <label class="mb-2 block text-sm font-semibold text-gray-700">
                                    Muthawif Pendamping
                                </label>

                                <select
                                    name="muthawif_ids[]"
                                    required
                                    class="w-full rounded-lg border-gray-300 text-sm focus:border-yellow-400 focus:ring-yellow-400"
                                >

                                    <option value="">
                                        Pilih Muthawif Pendamping
                                    </option>

                                    @foreach ($availableMuthawifs as $muthawif)

                                        <option
                                            value="{{ $muthawif->id }}"
                                            @selected($assistantAssignment?->user_id == $muthawif->id)
                                        >
                                            {{ $muthawif->name }}
                                        </option>

                                    @endforeach

                                </select>

                            </div>

                        </div>

                        <div class="rounded-lg border border-yellow-200 bg-yellow-50 p-4">

                            <p class="text-sm font-semibold text-gray-800">
                                Informasi Penugasan
                            </p>

                            <p class="mt-1 text-xs leading-relaxed text-gray-600">
                                Pilih dua akun dengan role Muthawif yang berbeda.
                                Sistem akan menyimpan pembimbing pertama sebagai Muthawif utama
                                dan pembimbing kedua sebagai Muthawif pendamping.
                            </p>

                        </div>

                        <div class="flex justify-end">

                            <button
                                type="submit"
                                @disabled($availableMuthawifs->count() < 2)
                                class="rounded-lg bg-yellow-400 px-5 py-2.5 text-sm font-semibold text-gray-900 transition hover:bg-yellow-500 disabled:cursor-not-allowed disabled:opacity-50"
                            >
                                Simpan Penugasan Muthawif
                            </button>

                        </div>

                        @if ($availableMuthawifs->count() < 2)
                            <p class="text-xs text-red-600">
                                Minimal harus tersedia dua akun dengan role Muthawif untuk membuat penugasan.
                            </p>
                        @endif

                    </form>

                </div>

                {{-- Footer --}}
                <p class="mb-4 text-xs text-gray-500">
                    Pengelolaan kelompok ini dilakukan oleh Karyawan Ansar Travel.
                </p>

            </div>

        </main>

    </div>

</body>
</html>
 