
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

        <main class="min-w-0 flex-1">
            
            <div class="space-y-6 p-5 md:p-8">

                <section>

                    <div class="mb-4">
                        <h2 class="text-base font-bold text-gray-900">
                            Ringkasan Operasional
                        </h2>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">

                        <!-- TOTAL PROSPEK -->
                        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                            <p class="text-sm font-medium text-gray-500">
                                Total Calon Jamaah
                            </p>

                            <div class="mt-3 flex items-center justify-between">
                                <h3 class="text-3xl font-bold text-gray-900">
                                    {{ number_format($totalProspects) }}
                                </h3>

                                <span class="rounded-lg bg-blue-50 p-3 text-blue-600">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         width="21" height="21" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor"
                                         stroke-width="1.8">
                                        <circle cx="9" cy="8" r="4"/>
                                        <path d="M3 21v-2a6 6 0 0 1 12 0v2"/>
                                    </svg>
                                </span>
                            </div>
                        </div>

                        <!-- FOLLOW UP -->
                        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                            <p class="text-sm font-medium text-gray-500">
                                Aktivitas Follow-up
                            </p>

                            <div class="mt-3 flex items-center justify-between">
                                <h3 class="text-3xl font-bold text-gray-900">
                                    {{ number_format($totalFollowUps) }}
                                </h3>

                                <span class="rounded-lg bg-amber-50 p-3 text-amber-600">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         width="21" height="21" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor"
                                         stroke-width="1.8">
                                        <path d="M21 11.5a8.4 8.4 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.4 8.4 0 0 1-3.8-.9L3 21l1.9-5.7a8.4 8.4 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.4 8.4 0 0 1 3.8-.9h.5a8.5 8.5 0 0 1 8 8v.5Z"/>
                                    </svg>
                                </span>
                            </div>
                        </div>

                        <!-- JADWAL -->
                        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                            <p class="text-sm font-medium text-gray-500">
                                Jadwal Keberangkatan
                            </p>

                            <div class="mt-3 flex items-center justify-between">
                                <h3 class="text-3xl font-bold text-gray-900">
                                    {{ number_format($upcomingSchedules) }}
                                </h3>

                                <span class="rounded-lg bg-purple-50 p-3 text-purple-600">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         width="21" height="21" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor"
                                         stroke-width="1.8">
                                        <rect x="3" y="4" width="18" height="17" rx="2"/>
                                        <path d="M16 2v4M8 2v4M3 10h18"/>
                                    </svg>
                                </span>
                            </div>
                        </div>

                    </div>

                </section>


                <!-- AKSES CEPAT -->
                <section>

                    <h2 class="mb-4 text-base font-bold text-gray-900">
                        Akses Cepat
                    </h2>

                    <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">

                        <!-- DAFTAR PROSPEK -->
                        <a
                            href="{{ route('karyawan.prospects.index') }}"
                            class="group flex items-center justify-between rounded-xl border border-gray-200 bg-white p-5 shadow-sm transition hover:border-[#FDD52E] hover:shadow-md"
                        >
                            <div class="flex items-center gap-4">
                                <span class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         width="23" height="23" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor"
                                         stroke-width="1.8">
                                        <circle cx="9" cy="8" r="4"/>
                                        <path d="M3 21v-2a6 6 0 0 1 12 0v2"/>
                                        <path d="M19 8v6M16 11h6"/>
                                    </svg>
                                </span>

                                <div>
                                    <h3 class="text-sm font-bold text-gray-900">
                                        Daftar Calon Jamaah
                                    </h3>
                                    <p class="mt-1 text-xs text-gray-500">
                                        Lihat dan kelola seluruh data prospek.
                                    </p>
                                </div>
                            </div>

                            <span class="text-gray-400 transition group-hover:translate-x-1 group-hover:text-gray-900">
                                →
                            </span>
                        </a>

                        <!-- TAMBAH PROSPEK -->
                        <a
                            href="{{ route('karyawan.prospects.create') }}"
                            class="group flex items-center justify-between rounded-xl border border-gray-200 bg-white p-5 shadow-sm transition hover:border-[#FDD52E] hover:shadow-md"
                        >
                            <div class="flex items-center gap-4">
                                <span class="flex h-12 w-12 items-center justify-center rounded-lg bg-amber-50 text-amber-600">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         width="23" height="23" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor"
                                         stroke-width="1.8">
                                        <circle cx="12" cy="12" r="9"/>
                                        <path d="M12 8v8M8 12h8"/>
                                    </svg>
                                </span>

                                <div>
                                    <h3 class="text-sm font-bold text-gray-900">
                                        Tambah Calon Jamaah
                                    </h3>
                                    <p class="mt-1 text-xs text-gray-500">
                                        Catat calon jamaah baru.
                                    </p>
                                </div>
                            </div>

                            <span class="text-gray-400 transition group-hover:translate-x-1 group-hover:text-gray-900">
                                →
                            </span>
                        </a>

                    </div>

                </section>


                <!-- FOOTER -->
                <footer class="border-t border-gray-200 pt-5 text-center text-xs text-gray-400">
                    &copy; {{ date('Y') }} Ansar Travel.
                </footer>

            </div>

        </main>

    </div>

</body>
</html>
