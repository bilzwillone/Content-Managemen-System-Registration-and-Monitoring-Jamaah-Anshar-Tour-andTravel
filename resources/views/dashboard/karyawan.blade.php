<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Karyawan - Ansar Travel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen overflow-x-hidden bg-gray-50 text-gray-800">
    @php
        $totalProspects = \App\Models\Prospect::count();
        $totalFollowUps = \App\Models\ProspectFollowUp::count();
        $upcomingSchedules = \App\Models\Schedule::where('status', 'published')
            ->where('is_visible', true)
            ->whereDate('departure_date', '>=', today())
            ->count();
    @endphp

    <div class="flex min-h-screen">
        @include('partials.sidebar')

        <main class="min-w-0 flex-1 p-5 md:p-8">
            <div class="mx-auto max-w-7xl space-y-8">
                <section class="rounded-2xl bg-[#171717] p-7 text-white shadow-sm">
                    <p class="text-sm font-semibold text-[#FDD52E]">RUANG KERJA KARYAWAN</p>
                    <h1 class="mt-3 text-2xl font-bold md:text-3xl">
                        Selamat Datang, {{ auth()->user()->name }}
                    </h1>
                    <p class="mt-2 text-sm leading-6 text-gray-300">
                        Pantau aktivitas calon jamaah dan jadwal keberangkatan melalui dashboard operasional.
                    </p>
                </section>

                <section>
                    <div class="mb-4">
                        <h2 class="text-lg font-bold text-gray-900">Ringkasan Operasional</h2>
                        <p class="mt-1 text-sm text-gray-500">Informasi singkat aktivitas kerja saat ini.</p>
                    </div>

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-3">
                        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-gray-500">Total Calon Jamaah</p>
                                <span class="rounded-xl bg-blue-50 px-3 py-2 text-lg text-blue-600">♙</span>
                            </div>
                            <p class="mt-5 text-3xl font-bold text-gray-900">{{ number_format($totalProspects) }}</p>
                            <p class="mt-2 text-xs text-gray-400">Seluruh data prospek</p>
                        </div>

                        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-gray-500">Aktivitas Follow-up</p>
                                <span class="rounded-xl bg-amber-50 px-3 py-2 text-lg text-amber-600">↗</span>
                            </div>
                            <p class="mt-5 text-3xl font-bold text-gray-900">{{ number_format($totalFollowUps) }}</p>
                            <p class="mt-2 text-xs text-gray-400">Riwayat tindak lanjut prospek</p>
                        </div>

                        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-gray-500">Jadwal Keberangkatan</p>
                                <span class="rounded-xl bg-purple-50 px-3 py-2 text-lg text-purple-600">▦</span>
                            </div>
                            <p class="mt-5 text-3xl font-bold text-gray-900">{{ number_format($upcomingSchedules) }}</p>
                            <p class="mt-2 text-xs text-gray-400">Jadwal terbit yang belum berangkat</p>
                        </div>
                    </div>
                </section>

                <section>
                    <div class="mb-4">
                        <h2 class="text-lg font-bold text-gray-900">Akses Cepat</h2>
                        <p class="mt-1 text-sm text-gray-500">Pilih menu untuk melanjutkan pekerjaan.</p>
                    </div>

                    <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
                        <a href="{{ route('karyawan.prospects.index') }}"
                           class="group flex items-center justify-between rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-[#FDD52E] hover:shadow-md">
                            <div class="flex items-center gap-4">
                                <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-xl text-blue-600">♙</span>
                                <div>
                                    <h3 class="font-bold text-gray-900">Daftar Calon Jamaah</h3>
                                    <p class="mt-1 text-sm text-gray-500">Lihat dan kelola seluruh data prospek.</p>
                                </div>
                            </div>
                            <span class="text-xl text-gray-400 transition group-hover:translate-x-1 group-hover:text-gray-900">→</span>
                        </a>

                        <a href="{{ route('karyawan.prospects.create') }}"
                           class="group flex items-center justify-between rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-[#FDD52E] hover:shadow-md">
                            <div class="flex items-center gap-4">
                                <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#FDD52E]/20 text-xl text-gray-900">＋</span>
                                <div>
                                    <h3 class="font-bold text-gray-900">Tambah Calon Jamaah</h3>
                                    <p class="mt-1 text-sm text-gray-500">Catat calon jamaah baru ke dalam sistem.</p>
                                </div>
                            </div>
                            <span class="text-xl text-gray-400 transition group-hover:translate-x-1 group-hover:text-gray-900">→</span>
                        </a>
                    </div>
                </section>

                <footer class="border-t border-gray-200 pt-5 text-center text-xs text-gray-400">
                    &copy; {{ date('Y') }} Ansar Travel.
                </footer>
            </div>
        </main>
    </div>
</body>
</html>
