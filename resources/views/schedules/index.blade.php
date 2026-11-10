
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Keberangkatan - Ansar Travel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 text-gray-800">

    @include('partials.public-navbar')

    <main>

        <!-- Hero Jadwal -->
        <section class="relative overflow-hidden px-5 py-20 text-white sm:py-24" style="background-color: #151515 !important;">
            <div class="absolute -right-20 -top-20 h-72 w-72 rounded-full bg-[#FDD52E]/10 blur-3xl"></div>
            <div class="absolute -bottom-24 -left-16 h-72 w-72 rounded-full bg-[#FDD52E]/10 blur-3xl"></div>

            <div class="relative mx-auto max-w-7xl">
                <div class="max-w-3xl">
                    <span class="inline-block rounded-full border border-[#FDD52E]/40 bg-[#FDD52E]/10 px-4 py-2 text-sm font-semibold text-[#FDD52E]">
                        ANSAR TRAVEL
                    </span>

                    <h1 class="mt-6 text-4xl font-bold leading-tight sm:text-5xl">
                        Jadwal Keberangkatan
                        <span class="text-[#FDD52E]">Umroh & Wisata</span>
                    </h1>

                    <p class="mt-5 max-w-2xl text-base leading-7 text-gray-300 sm:text-lg">
                        Temukan jadwal perjalanan bersama Ansar Travel.
                        Pilih jadwal yang sesuai dengan rencana perjalanan Anda
                        dan dapatkan informasi keberangkatan serta ketersediaan kuota.
                    </p>
                </div>
            </div>
        </section>

        <!-- Daftar Jadwal -->
        <section class="px-5 py-16 sm:py-20">
            <div class="mx-auto max-w-7xl">

                <div class="mb-10 text-center">
                    <span class="text-sm font-bold uppercase tracking-widest text-amber-600">
                        Pilihan Perjalanan
                    </span>

                    <h2 class="mt-3 text-3xl font-bold text-gray-900 sm:text-4xl">
                        Pilih Jadwal Perjalanan
                    </h2>

                    <p class="mx-auto mt-4 max-w-2xl text-gray-500">
                        Lihat daftar jadwal perjalanan yang tersedia dan pilih
                        program yang sesuai dengan kebutuhan Anda.
                    </p>
                </div>

                <!-- Tab -->
                <div class="mb-10 flex justify-center">
                    <div class="inline-flex rounded-xl border border-gray-200 bg-white p-1.5 shadow-sm">

                        <button
                            type="button"
                            data-schedule-tab="umrah"
                            aria-selected="true"
                            class="schedule-tab rounded-lg bg-[#FDD52E] px-6 py-3 text-sm font-bold text-gray-900 transition sm:px-9">
                            Umroh
                        </button>

                        <button
                            type="button"
                            data-schedule-tab="tour"
                            aria-selected="false"
                            class="schedule-tab rounded-lg px-6 py-3 text-sm font-bold text-gray-500 transition hover:text-gray-900 sm:px-9">
                            Tour & Wisata
                        </button>

                    </div>
                </div>

                <!-- Tab Umroh -->
                <div id="schedule-panel-umrah" class="schedule-panel">

                    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">
                                Jadwal Umroh
                            </h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Pilihan program keberangkatan ibadah umroh.
                            </p>
                        </div>

                        <span class="rounded-full bg-amber-100 px-4 py-2 text-sm font-semibold text-amber-800">
                            {{ $umrahSchedules->count() }} Jadwal
                        </span>
                    </div>

                    @forelse ($umrahSchedules as $schedule)

                        @php
                            $quotaPercentage = $schedule->total_seats > 0
                                ? min(100, round(($schedule->booked_seats / $schedule->total_seats) * 100))
                                : 0;

                            $statusClass = match ($schedule->availability_status) {
                                'Penuh' => 'bg-red-100 text-red-700',
                                'Hampir Penuh' => 'bg-amber-100 text-amber-700',
                                'Sudah Berangkat' => 'bg-gray-100 text-gray-600',
                                default => 'bg-green-100 text-green-700',
                            };

                            $progressClass = match ($schedule->availability_status) {
                                'Penuh' => 'bg-red-500',
                                'Hampir Penuh' => 'bg-amber-500',
                                default => 'bg-green-500',
                            };
                        @endphp

                        <article class="mb-6 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg">

                            <div class="grid md:grid-cols-12">

                                <!-- Informasi Utama -->
                                <div class="p-6 md:col-span-8 md:p-8">

                                    <div class="flex flex-wrap items-start justify-between gap-3">

                                        <span class="rounded-full bg-amber-100 px-3 py-1.5 text-xs font-bold uppercase tracking-wide text-amber-800">
                                            Paket Umroh
                                        </span>

                                        <span class="rounded-full px-3 py-1.5 text-xs font-bold {{ $statusClass }}">
                                            {{ $schedule->availability_status }}
                                        </span>

                                    </div>

                                    <h3 class="mt-5 text-2xl font-bold text-gray-900">
                                        {{ $schedule->name }}
                                    </h3>

                                    @if ($schedule->description)
                                        <p class="mt-3 line-clamp-3 text-sm leading-6 text-gray-500">
                                            {{ $schedule->description }}
                                        </p>
                                    @endif

                                    <div class="mt-7 grid gap-5 sm:grid-cols-3">

                                        <div>
                                            <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                                                Keberangkatan
                                            </p>
                                            <p class="mt-2 font-bold text-gray-800">
                                                {{ $schedule->departure_date->locale('id')->translatedFormat('d F Y') }}
                                            </p>
                                        </div>

                                        <div>
                                            <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                                                Kepulangan
                                            </p>
                                            <p class="mt-2 font-bold text-gray-800">
                                                {{ $schedule->return_date->locale('id')->translatedFormat('d F Y') }}
                                            </p>
                                        </div>

                                        <div>
                                            <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                                                Durasi
                                            </p>
                                            <p class="mt-2 font-bold text-gray-800">
                                                {{ $schedule->duration_label }}
                                            </p>
                                        </div>

                                    </div>

                                    @if ($schedule->airline)
                                        <div class="mt-5 border-t border-gray-100 pt-5">
                                            <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                                                Maskapai
                                            </p>
                                            <p class="mt-2 font-semibold text-gray-800">
                                                {{ $schedule->airline }}
                                            </p>
                                        </div>
                                    @endif

                                </div>

                                <!-- Informasi Kuota -->
                                <div class="flex flex-col justify-between border-t border-gray-100 bg-gray-50 p-6 md:col-span-4 md:border-l md:border-t-0 md:p-8">

                                    <div>
                                        <h4 class="text-sm font-bold text-gray-800">
                                            Ketersediaan Kuota
                                        </h4>

                                        <div class="mt-5 flex items-end justify-between">
                                            <div>
                                                <p class="text-3xl font-bold text-gray-900">
                                                    {{ $schedule->available_seats }}
                                                </p>
                                                <p class="mt-1 text-xs text-gray-500">
                                                    Sisa kursi
                                                </p>
                                            </div>

                                            <p class="text-sm font-semibold text-gray-500">
                                                dari {{ $schedule->total_seats }} kursi
                                            </p>
                                        </div>

                                        <div class="mt-4 h-2.5 overflow-hidden rounded-full bg-gray-200">
                                            <div
                                                class="h-full rounded-full {{ $progressClass }}"
                                                style="width: {{ $quotaPercentage }}%">
                                            </div>
                                        </div>

                                        <div class="mt-2 flex justify-between text-xs text-gray-400">
                                            <span>{{ $schedule->booked_seats }} terisi</span>
                                            <span>{{ $quotaPercentage }}%</span>
                                        </div>
                                    </div>

                                    <a
                                        href="https://wa.me/6282170700166?text={{ urlencode('Halo Ansar Travel, saya ingin bertanya mengenai jadwal ' . $schedule->name) }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="mt-8 inline-flex w-full items-center justify-center rounded-xl bg-[#FDD52E] px-5 py-3.5 text-sm font-bold text-gray-900 transition hover:bg-amber-400">
                                        Konsultasi Jadwal
                                    </a>

                                </div>

                            </div>

                        </article>

                    @empty

                        <div class="rounded-2xl border border-dashed border-gray-300 bg-white px-6 py-16 text-center">
                            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-amber-50 text-2xl">
                                —
                            </div>

                            <h3 class="mt-5 text-lg font-bold text-gray-800">
                                Belum Ada Jadwal Umroh
                            </h3>

                            <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-gray-500">
                                Saat ini belum ada jadwal umroh yang dipublikasikan.
                                Silakan kunjungi halaman ini kembali.
                            </p>
                        </div>

                    @endforelse

                </div>

                <!-- Tab Tour dan Wisata -->
                <div id="schedule-panel-tour" class="schedule-panel hidden">

                    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">
                                Jadwal Tour & Wisata
                            </h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Pilihan perjalanan wisata bersama Ansar Travel.
                            </p>
                        </div>

                        <span class="rounded-full bg-amber-100 px-4 py-2 text-sm font-semibold text-amber-800">
                            {{ $tourSchedules->count() }} Jadwal
                        </span>
                    </div>

                    @forelse ($tourSchedules as $schedule)

                        @php
                            $quotaPercentage = $schedule->total_seats > 0
                                ? min(100, round(($schedule->booked_seats / $schedule->total_seats) * 100))
                                : 0;

                            $statusClass = match ($schedule->availability_status) {
                                'Penuh' => 'bg-red-100 text-red-700',
                                'Hampir Penuh' => 'bg-amber-100 text-amber-700',
                                'Sudah Berangkat' => 'bg-gray-100 text-gray-600',
                                default => 'bg-green-100 text-green-700',
                            };

                            $progressClass = match ($schedule->availability_status) {
                                'Penuh' => 'bg-red-500',
                                'Hampir Penuh' => 'bg-amber-500',
                                default => 'bg-green-500',
                            };
                        @endphp

                        <article class="mb-6 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg">

                            <div class="grid md:grid-cols-12">

                                <div class="p-6 md:col-span-8 md:p-8">

                                    <div class="flex flex-wrap items-start justify-between gap-3">

                                        <span class="rounded-full bg-blue-100 px-3 py-1.5 text-xs font-bold uppercase tracking-wide text-blue-800">
                                            Tour & Wisata
                                        </span>

                                        <span class="rounded-full px-3 py-1.5 text-xs font-bold {{ $statusClass }}">
                                            {{ $schedule->availability_status }}
                                        </span>

                                    </div>

                                    <h3 class="mt-5 text-2xl font-bold text-gray-900">
                                        {{ $schedule->name }}
                                    </h3>

                                    @if ($schedule->description)
                                        <p class="mt-3 line-clamp-3 text-sm leading-6 text-gray-500">
                                            {{ $schedule->description }}
                                        </p>
                                    @endif

                                    <div class="mt-7 grid gap-5 sm:grid-cols-3">

                                        <div>
                                            <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                                                Keberangkatan
                                            </p>
                                            <p class="mt-2 font-bold text-gray-800">
                                                {{ $schedule->departure_date->locale('id')->translatedFormat('d F Y') }}
                                            </p>
                                        </div>

                                        <div>
                                            <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                                                Kepulangan
                                            </p>
                                            <p class="mt-2 font-bold text-gray-800">
                                                {{ $schedule->return_date->locale('id')->translatedFormat('d F Y') }}
                                            </p>
                                        </div>

                                        <div>
                                            <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                                                Durasi
                                            </p>
                                            <p class="mt-2 font-bold text-gray-800">
                                                {{ $schedule->duration_label }}
                                            </p>
                                        </div>

                                    </div>

                                    @if ($schedule->airline)
                                        <div class="mt-5 border-t border-gray-100 pt-5">
                                            <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                                                Maskapai
                                            </p>
                                            <p class="mt-2 font-semibold text-gray-800">
                                                {{ $schedule->airline }}
                                            </p>
                                        </div>
                                    @endif

                                </div>

                                <div class="flex flex-col justify-between border-t border-gray-100 bg-gray-50 p-6 md:col-span-4 md:border-l md:border-t-0 md:p-8">

                                    <div>
                                        <h4 class="text-sm font-bold text-gray-800">
                                            Ketersediaan Kuota
                                        </h4>

                                        <div class="mt-5 flex items-end justify-between">
                                            <div>
                                                <p class="text-3xl font-bold text-gray-900">
                                                    {{ $schedule->available_seats }}
                                                </p>
                                                <p class="mt-1 text-xs text-gray-500">
                                                    Sisa kursi
                                                </p>
                                            </div>

                                            <p class="text-sm font-semibold text-gray-500">
                                                dari {{ $schedule->total_seats }} kursi
                                            </p>
                                        </div>

                                        <div class="mt-4 h-2.5 overflow-hidden rounded-full bg-gray-200">
                                            <div
                                                class="h-full rounded-full {{ $progressClass }}"
                                                style="width: {{ $quotaPercentage }}%">
                                            </div>
                                        </div>

                                        <div class="mt-2 flex justify-between text-xs text-gray-400">
                                            <span>{{ $schedule->booked_seats }} terisi</span>
                                            <span>{{ $quotaPercentage }}%</span>
                                        </div>
                                    </div>

                                    <a
                                        href="https://wa.me/6282170700166?text={{ urlencode('Halo Ansar Travel, saya ingin bertanya mengenai jadwal ' . $schedule->name) }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="mt-8 inline-flex w-full items-center justify-center rounded-xl bg-[#FDD52E] px-5 py-3.5 text-sm font-bold text-gray-900 transition hover:bg-amber-400">
                                        Konsultasi Jadwal
                                    </a>

                                </div>

                            </div>

                        </article>

                    @empty

                        <div class="rounded-2xl border border-dashed border-gray-300 bg-white px-6 py-16 text-center">
                            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-blue-50 text-2xl">
                                —
                            </div>

                            <h3 class="mt-5 text-lg font-bold text-gray-800">
                                Belum Ada Jadwal Tour & Wisata
                            </h3>

                            <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-gray-500">
                                Saat ini belum ada jadwal tour dan wisata yang dipublikasikan.
                                Silakan kunjungi halaman ini kembali.
                            </p>
                        </div>

                    @endforelse

                </div>

            </div>
        </section>

        <!-- CTA -->
        <section class="px-5 pb-16">
            <div class="mx-auto max-w-7xl overflow-hidden rounded-2xl bg-[#FDD52E] px-6 py-12 text-center sm:px-12">
                <h2 class="text-2xl font-bold text-gray-900 sm:text-3xl">
                    Masih Memiliki Pertanyaan?
                </h2>

                <p class="mx-auto mt-3 max-w-2xl text-sm leading-6 text-gray-700">
                    Hubungi tim Ansar Travel untuk mendapatkan informasi lebih lanjut
                    mengenai jadwal, program perjalanan, dan ketersediaan kuota.
                </p>

                <a
                    href="https://wa.me/6282170700166?text={{ urlencode('Halo Ansar Travel, saya ingin mendapatkan informasi jadwal perjalanan.') }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="mt-7 inline-flex items-center justify-center rounded-xl bg-[#151515] px-7 py-3.5 text-sm font-bold text-white transition hover:bg-gray-800">
                    Hubungi Ansar Travel
                </a>
            </div>
        </section>

    </main>

    <!-- Footer -->
    <footer class="bg-[#151515] px-5 py-8 text-center text-sm text-gray-400">
        <p>
            &copy; {{ date('Y') }} Ansar Travel. Seluruh hak cipta dilindungi.
        </p>
    </footer>

    <!-- Tab Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tabButtons = document.querySelectorAll('[data-schedule-tab]');
            const panels = document.querySelectorAll('.schedule-panel');

            tabButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    const selectedTab = button.dataset.scheduleTab;

                    tabButtons.forEach(function (tab) {
                        tab.classList.remove('bg-[#FDD52E]', 'text-gray-900');
                        tab.classList.add('text-gray-500');
                        tab.setAttribute('aria-selected', 'false');
                    });

                    button.classList.add('bg-[#FDD52E]', 'text-gray-900');
                    button.classList.remove('text-gray-500');
                    button.setAttribute('aria-selected', 'true');

                    panels.forEach(function (panel) {
                        panel.classList.add('hidden');
                    });

                    document.getElementById('schedule-panel-' + selectedTab)
                        .classList.remove('hidden');
                });
            });
        });
    </script>

</body>
</html>
