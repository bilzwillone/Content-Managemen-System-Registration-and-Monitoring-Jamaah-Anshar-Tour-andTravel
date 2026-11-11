
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Buat Kelompok Jamaah - Ansar Travel</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gray-50 font-sans antialiased">

    <div class="flex min-h-screen bg-gray-50">

        {{-- Sidebar --}}
        @include('partials.sidebar')

        {{-- Main Content --}}
        <main class="min-w-0 flex-1 p-6 lg:p-8">

            <div class="mx-auto max-w-4xl">

                {{-- Header --}}
                <div class="mb-8">

                    <a
                        href="{{ route('karyawan.groups.index') }}"
                        class="mb-4 inline-flex items-center text-sm font-medium text-gray-500 transition hover:text-gray-800"
                    >
                        ← Kembali ke Kelompok Jamaah
                    </a>

                    <h1 class="text-2xl font-bold text-gray-800">
                        Buat Kelompok Jamaah
                    </h1>

                    <p class="mt-1 text-sm text-gray-500">
                        Buat kelompok baru berdasarkan jadwal keberangkatan yang tersedia.
                    </p>

                </div>

                {{-- Error Notification --}}
                @if ($errors->any())
                    <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700">

                        <p class="font-semibold">
                            Terdapat kesalahan pada formulir:
                        </p>

                        <ul class="mt-2 list-inside list-disc space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>

                    </div>
                @endif

                {{-- Form Card --}}
                <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                    <div class="border-b border-gray-200 px-6 py-5">

                        <h2 class="font-semibold text-gray-800">
                            Informasi Kelompok
                        </h2>

                        <p class="mt-1 text-sm text-gray-500">
                            Lengkapi informasi dasar kelompok jamaah.
                        </p>

                    </div>

                    <form
                        action="{{ route('karyawan.groups.store') }}"
                        method="POST"
                    >

                        @csrf

                        <div class="space-y-6 p-6">

                            {{-- Group Name --}}
                            <div>

                                <label
                                    for="name"
                                    class="mb-2 block text-sm font-semibold text-gray-700"
                                >
                                    Nama Kelompok
                                    <span class="text-red-500">*</span>
                                </label>

                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    value="{{ old('name') }}"
                                    required
                                    maxlength="255"
                                    placeholder="Contoh: Kelompok A"
                                    class="w-full rounded-lg border-gray-300 text-sm focus:border-yellow-400 focus:ring-yellow-400"
                                >

                                <p class="mt-1.5 text-xs text-gray-400">
                                    Masukkan nama kelompok yang mudah dikenali.
                                </p>

                            </div>

                            {{-- Departure Schedule --}}
                            <div>

                                <label
                                    for="schedule_id"
                                    class="mb-2 block text-sm font-semibold text-gray-700"
                                >
                                    Jadwal Keberangkatan
                                    <span class="text-red-500">*</span>
                                </label>

                                <select
                                    id="schedule_id"
                                    name="schedule_id"
                                    required
                                    class="w-full rounded-lg border-gray-300 text-sm focus:border-yellow-400 focus:ring-yellow-400"
                                >

                                    <option value="">
                                        Pilih jadwal keberangkatan
                                    </option>

                                    @foreach ($schedules as $schedule)

                                        <option
                                            value="{{ $schedule->id }}"
                                            @selected(old('schedule_id') == $schedule->id)
                                        >
                                            {{ $schedule->name }}
                                            — {{ $schedule->departure_date?->format('d M Y') ?? '-' }}
                                            — {{ $schedule->available_seats }} Seat Tersedia
                                        </option>

                                    @endforeach

                                </select>

                                @if ($schedules->isEmpty())

                                    <p class="mt-2 text-sm text-red-600">
                                        Belum tersedia jadwal keberangkatan. Silakan tambahkan jadwal melalui menu Admin terlebih dahulu.
                                    </p>

                                @else

                                    <p class="mt-1.5 text-xs text-gray-400">
                                        Pilih jadwal keberangkatan yang akan menjadi dasar kelompok ini.
                                    </p>

                                @endif

                            </div>

                            {{-- Notes --}}
                            <div>

                                <label
                                    for="notes"
                                    class="mb-2 block text-sm font-semibold text-gray-700"
                                >
                                    Catatan Kelompok
                                </label>

                                <textarea
                                    id="notes"
                                    name="notes"
                                    rows="4"
                                    placeholder="Tambahkan catatan mengenai kelompok apabila diperlukan..."
                                    class="w-full rounded-lg border-gray-300 text-sm focus:border-yellow-400 focus:ring-yellow-400"
                                >{{ old('notes') }}</textarea>

                                <p class="mt-1.5 text-xs text-gray-400">
                                    Catatan ini bersifat opsional.
                                </p>

                            </div>

                            {{-- Information --}}
                            <div class="rounded-lg border border-yellow-200 bg-yellow-50 p-4">

                                <p class="text-sm font-semibold text-gray-800">
                                    Informasi Pembuatan Kelompok
                                </p>

                                <ul class="mt-2 list-inside list-disc space-y-1.5 text-xs leading-relaxed text-gray-600">

                                    <li>
                                        Kode kelompok akan dibuat secara otomatis oleh sistem.
                                    </li>

                                    <li>
                                        Status awal kelompok adalah Persiapan.
                                    </li>

                                    <li>
                                        Anggota jamaah dan dua Muthawif dapat ditambahkan setelah kelompok dibuat.
                                    </li>

                                </ul>

                            </div>

                        </div>

                        {{-- Form Actions --}}
                        <div class="flex flex-wrap justify-end gap-3 border-t border-gray-200 bg-gray-50 px-6 py-4">

                            <a
                                href="{{ route('karyawan.groups.index') }}"
                                class="rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-semibold text-gray-600 transition hover:bg-gray-100"
                            >
                                Batal
                            </a>

                            <button
                                type="submit"
                                @disabled($schedules->isEmpty())
                                class="rounded-lg bg-yellow-400 px-5 py-2.5 text-sm font-semibold text-gray-900 transition hover:bg-yellow-500 disabled:cursor-not-allowed disabled:opacity-50"
                            >
                                Simpan Kelompok
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </main>

    </div>

</body>
</html>
