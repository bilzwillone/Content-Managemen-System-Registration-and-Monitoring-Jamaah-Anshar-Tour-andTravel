
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $page->meta_title ?: $page->title . ' - Ansar Travel' }}</title>

    @if ($page->meta_description)
        <meta name="description" content="{{ $page->meta_description }}">
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-white text-gray-800 antialiased">

    @php
        $visibleSections = $page->sections
            ->where('status', 'published')
            ->where('is_visible', true)
            ->sortBy('sort_order')
            ->values();
    @endphp

    {{-- NAVBAR --}}
    <header class="sticky top-0 z-50 border-b border-gray-100 bg-white">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-5 py-4 lg:px-8">

            <a href="/" class="flex items-center gap-3">
                <img
                    src="{{ asset('images/ansar-travel-logo.png') }}"
                    alt="Logo Ansar Travel"
                    class="h-12 w-12 object-contain"
                >

                <div>
                    <h1 class="text-lg font-extrabold tracking-wide text-gray-900">
                        ANSHAR TRAVEL
                    </h1>
                    <p class="text-xs text-gray-500">
                        Your Trusted Travel Partner
                    </p>
                </div>
            </a>

            <nav class="hidden items-center gap-8 text-sm font-semibold text-gray-700 lg:flex">
                <a href="/" class="text-amber-600">Beranda</a>
                <a href="/paket-umroh" class="transition hover:text-amber-600">Paket Umroh</a>
                <a href="/jadwal" class="transition hover:text-amber-600">Jadwal</a>
                <a href="/tentang-kami" class="transition hover:text-amber-600">Tentang Kami</a>
                <a href="/info" class="transition hover:text-amber-600">Info</a>
                <a href="/kontak" class="transition hover:text-amber-600">Kontak</a>
            </nav>

            <a
                href="/kontak"
                class="hidden rounded-full bg-amber-400 px-6 py-3 text-sm font-bold text-gray-900 transition hover:bg-amber-500 lg:inline-block"
            >
                Konsultasi
            </a>

            <button
                id="menu-button"
                type="button"
                class="rounded-lg border border-gray-200 p-2 text-gray-700 lg:hidden"
                aria-label="Buka menu navigasi"
                aria-expanded="false"
            >
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

        </div>

        {{-- MOBILE MENU --}}
        <nav id="mobile-menu" class="hidden border-t border-gray-100 bg-white px-5 py-4 lg:hidden">
            <div class="flex flex-col gap-4 text-sm font-semibold text-gray-700">
                <a href="/">Beranda</a>
                <a href="/paket-umroh">Paket Umroh</a>
                <a href="/jadwal">Jadwal</a>
                <a href="/tentang-kami">Tentang Kami</a>
                <a href="/info">Info</a>
                <a href="/kontak">Kontak</a>
            </div>
        </nav>
    </header>

    <main>

        {{-- SEMUA SECTION BERDASARKAN URUTAN --}}
        @foreach ($visibleSections as $section)

            {{-- HERO --}}
            @if ($section->section_type === 'hero')

                <section
                    class="relative flex min-h-[650px] items-center overflow-hidden bg-gray-950 bg-cover bg-center"
                    style="background-image: url('{{ asset('images/hero-masjidil-haram.png') }}');"
                >

                    <div class="absolute inset-0 bg-gradient-to-r from-gray-950/95 via-gray-950/75 to-gray-950/30"></div>

                    <div class="relative z-10 mx-auto w-full max-w-7xl px-5 py-28 lg:px-8 lg:py-36">

                        <div class="max-w-2xl">

                            <span class="mb-6 inline-block rounded-full border border-amber-300/50 bg-black/20 px-5 py-2 text-sm font-semibold text-amber-300 backdrop-blur-sm">
                                {{ $section->subtitle ?: 'Ansar Travel' }}
                            </span>

                            <h1 class="text-4xl font-extrabold leading-tight text-white md:text-5xl lg:text-6xl">
                                {{ $section->title }}
                            </h1>

                            @if (data_get($section->content, 'description'))
                                <p class="mt-6 max-w-xl text-base leading-8 text-gray-200 md:text-lg">
                                    {{ data_get($section->content, 'description') }}
                                </p>
                            @endif

                            <div class="mt-9 flex flex-wrap gap-4">

                                <a
                                    href="{{ $section->button_url ?: '/paket-umroh' }}"
                                    class="rounded-full bg-amber-400 px-7 py-4 text-sm font-bold text-gray-950 shadow-lg transition hover:bg-amber-500"
                                >
                                    {{ $section->button_text ?: 'Lihat Paket Umroh' }}
                                </a>

                                <a
                                    href="/kontak"
                                    class="rounded-full border border-white/60 bg-white/10 px-7 py-4 text-sm font-bold text-white backdrop-blur-sm transition hover:bg-white hover:text-gray-950"
                                >
                                    Konsultasi Sekarang
                                </a>

                            </div>
                        </div>
                    </div>
                </section>

            {{-- STATISTICS --}}
            @elseif ($section->section_type === 'statistics')

                @php
                    $statItems = data_get($section->content, 'items', []);
                    $previousSection = $visibleSections->get($loop->index - 1);
                    $overlapHero = $previousSection && $previousSection->section_type === 'hero';
                @endphp

                @if (count($statItems))
                    <section class="relative z-10 mx-auto max-w-6xl px-5 py-10 {{ $overlapHero ? '-mt-10' : '' }}">

                        <div class="flex flex-wrap justify-center gap-y-8 rounded-2xl bg-white px-5 py-8 shadow-xl md:px-8 md:py-9">

                            @foreach ($statItems as $item)
                                <div class="w-1/2 px-2 text-center md:w-1/4">

                                    <h3 class="text-3xl font-extrabold text-amber-500">
                                        {{ $item['value'] ?? '-' }}
                                    </h3>

                                    <p class="mt-2 text-sm font-medium text-gray-500">
                                        {{ $item['label'] ?? 'Statistik' }}
                                    </p>

                                </div>
                            @endforeach

                        </div>
                    </section>
                @endif

            {{-- WHY CHOOSE US --}}
            @elseif ($section->section_type === 'why_choose_us')

                @php
                    $reasonCards = data_get($section->content, 'cards', []);
                @endphp

                @if (count($reasonCards))
                    <section class="bg-gray-50 px-5 py-20 md:py-24">
                        <div class="mx-auto max-w-7xl">

                            <div class="mx-auto mb-14 max-w-3xl text-center">

                                <span class="text-sm font-bold uppercase tracking-widest text-amber-600">
                                    {{ $section->subtitle ?: 'Why Choose Us' }}
                                </span>

                                <h2 class="mt-4 text-3xl font-extrabold leading-tight text-gray-900 md:text-4xl">
                                    {{ $section->title }}
                                </h2>

                                @if (data_get($section->content, 'description'))
                                    <p class="mx-auto mt-5 max-w-2xl leading-8 text-gray-600">
                                        {{ data_get($section->content, 'description') }}
                                    </p>
                                @endif

                            </div>

                            <div class="flex flex-wrap justify-center gap-6">

                                @foreach ($reasonCards as $card)
                                    <div class="w-full rounded-2xl border border-gray-200 bg-white p-7 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-amber-300 hover:shadow-lg sm:w-[calc(50%-12px)] lg:w-[calc(33.333%-16px)]">

                                        <h3 class="text-lg font-bold text-gray-900">
                                            {{ $card['title'] ?? '' }}
                                        </h3>

                                        <p class="mt-3 text-sm leading-7 text-gray-600">
                                            {{ $card['description'] ?? '' }}
                                        </p>

                                    </div>
                                @endforeach

                            </div>

                        </div>
                    </section>
                @endif

            {{-- SERVICES --}}
            @elseif ($section->section_type === 'services')

                @php
                    $serviceCards = data_get($section->content, 'cards', []);
                @endphp

                <section class="bg-white px-5 py-20 md:py-24">
                    <div class="mx-auto max-w-7xl">

                        <div class="mx-auto mb-14 max-w-3xl text-center">

                            <span class="text-sm font-bold uppercase tracking-widest text-amber-600">
                                SERVICES
                            </span>

                            <h2 class="mt-4 text-3xl font-extrabold text-gray-900 md:text-4xl">
                                {{ $section->title }}
                            </h2>

                            @if ($section->subtitle)
                                <p class="mt-4 leading-7 text-gray-500">
                                    {{ $section->subtitle }}
                                </p>
                            @endif

                            @if (data_get($section->content, 'description'))
                                <p class="mt-5 leading-8 text-gray-600">
                                    {{ data_get($section->content, 'description') }}
                                </p>
                            @endif

                        </div>

                        @if (count($serviceCards))
                            <div class="flex flex-wrap justify-center gap-6">

                                @foreach ($serviceCards as $card)
                                    <div class="w-full rounded-2xl border border-gray-200 bg-white p-7 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-amber-300 hover:shadow-lg sm:w-[calc(50%-12px)] lg:w-[calc(33.333%-16px)]">

                                        <h3 class="text-lg font-bold text-gray-900">
                                            {{ $card['title'] ?? '' }}
                                        </h3>

                                        <p class="mt-3 text-sm leading-7 text-gray-600">
                                            {{ $card['description'] ?? '' }}
                                        </p>

                                    </div>
                                @endforeach

                            </div>
                        @endif

                    </div>
                </section>

            {{-- SECTION LAINNYA --}}
            @else

                <section class="bg-white px-5 py-20">
                    <div class="mx-auto max-w-7xl">

                        <div class="mx-auto max-w-3xl text-center">

                            <span class="text-sm font-bold uppercase tracking-widest text-amber-600">
                                {{ ucfirst(str_replace('_', ' ', $section->section_type)) }}
                            </span>

                            <h2 class="mt-3 text-3xl font-extrabold text-gray-900">
                                {{ $section->title }}
                            </h2>

                            @if ($section->subtitle)
                                <p class="mt-4 text-gray-500">
                                    {{ $section->subtitle }}
                                </p>
                            @endif

                            @if (data_get($section->content, 'description'))
                                <p class="mt-5 leading-8 text-gray-600">
                                    {{ data_get($section->content, 'description') }}
                                </p>
                            @endif

                        </div>

                    </div>
                </section>

            @endif

        @endforeach

        
        {{-- PAKET UMROH UNGGULAN --}}
        @if ($packages->isNotEmpty())

            <section class="bg-gray-50 px-5 py-20 md:py-24">

                <div class="mx-auto max-w-7xl">

                    {{-- JUDUL SECTION --}}
                    <div class="mb-12 text-center">

                        <span class="text-sm font-bold uppercase tracking-widest text-amber-600">
                            PILIHAN PERJALANAN
                        </span>

                        <h2 class="mt-4 text-3xl font-extrabold text-gray-900 md:text-4xl">
                            Paket Umroh Pilihan
                        </h2>

                        <p class="mx-auto mt-4 max-w-2xl leading-8 text-gray-600">
                            Temukan paket perjalanan ibadah umroh bersama Ansar Travel
                            dengan pilihan fasilitas dan jadwal keberangkatan yang sesuai
                            dengan kebutuhan Anda.
                        </p>

                    </div>

                    {{-- DAFTAR KARTU PAKET --}}
                    <div class="grid grid-cols-1 gap-7 md:grid-cols-2 lg:grid-cols-3">

                        @foreach ($packages as $package)

                            <div class="group overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl">

                                {{-- FOTO PAKET --}}
                                <div class="relative h-56 overflow-hidden bg-gray-100">

                                    @if ($package->image_path)
                                        <img
                                            src="{{ asset('storage/' . $package->image_path) }}"
                                            alt="{{ $package->name }}"
                                            class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                                        >
                                    @else
                                        <div class="flex h-full items-center justify-center text-gray-400">
                                            <span class="text-sm">Foto paket belum tersedia</span>
                                        </div>
                                    @endif

                                    <span class="absolute left-4 top-4 rounded-full bg-amber-400 px-4 py-2 text-xs font-bold text-gray-900 shadow">
                                        Paket Unggulan
                                    </span>

                                </div>

                                {{-- INFORMASI PAKET --}}
                                <div class="p-6">

                                    <h3 class="text-xl font-bold text-gray-900">
                                        {{ $package->name }}
                                    </h3>

                                    <p class="mt-3 line-clamp-3 text-sm leading-7 text-gray-600">
                                        {{ $package->description ?: 'Informasi perjalanan umroh bersama Ansar Travel.' }}
                                    </p>

                                    {{-- DETAIL PAKET --}}
                                    <div class="mt-5 space-y-3 border-t border-gray-100 pt-5">

                                        @if ($package->duration_days)
                                            <div class="flex items-center gap-3 text-sm text-gray-600">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                     width="18" height="18"
                                                     viewBox="0 0 24 24"
                                                     fill="none" stroke="currentColor"
                                                     stroke-width="1.8"
                                                     stroke-linecap="round"
                                                     stroke-linejoin="round">
                                                    <circle cx="12" cy="12" r="10"/>
                                                    <path d="M12 6v6l4 2"/>
                                                </svg>
                                                {{ $package->duration_days }} Hari Perjalanan
                                            </div>
                                        @endif

                                        @if ($package->airline)
                                            <div class="flex items-center gap-3 text-sm text-gray-600">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                     width="18" height="18"
                                                     viewBox="0 0 24 24"
                                                     fill="none" stroke="currentColor"
                                                     stroke-width="1.8"
                                                     stroke-linecap="round"
                                                     stroke-linejoin="round">
                                                    <path d="M17.8 19.2 16 11l3.5-3.5a2.1 2.1 0 0 0-3-3L13 8l-8.2-1.8a1 1 0 0 0-.9 1.7l5.1 3.5-3.5 3.5 3.1.9 2.7-2.7 3.5 5.1a1 1 0 0 0 1.7-.9Z"/>
                                                </svg>
                                                {{ $package->airline }}
                                            </div>
                                        @endif

                                        @if ($package->hotel)
                                            <div class="flex items-center gap-3 text-sm text-gray-600">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                     width="18" height="18"
                                                     viewBox="0 0 24 24"
                                                     fill="none" stroke="currentColor"
                                                     stroke-width="1.8"
                                                     stroke-linecap="round"
                                                     stroke-linejoin="round">
                                                    <path d="M3 21V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v16"/>
                                                    <path d="M3 11h18M9 21v-5h6v5"/>
                                                    <path d="M7 7h.01M12 7h.01M17 7h.01"/>
                                                </svg>
                                                {{ $package->hotel }}
                                            </div>
                                        @endif

                                        @if ($package->departure_date)
                                            <div class="flex items-center gap-3 text-sm text-gray-600">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                     width="18" height="18"
                                                     viewBox="0 0 24 24"
                                                     fill="none" stroke="currentColor"
                                                     stroke-width="1.8"
                                                     stroke-linecap="round"
                                                     stroke-linejoin="round">
                                                    <rect x="3" y="4" width="18" height="18" rx="2"/>
                                                    <path d="M16 2v4M8 2v4M3 10h18"/>
                                                </svg>
                                                {{ $package->departure_date->translatedFormat('d F Y') }}
                                            </div>
                                        @endif

                                    </div>

                                    {{-- HARGA --}}
                                    <div class="mt-6 border-t border-gray-100 pt-5">

                                        <p class="text-xs font-medium text-gray-500">
                                            Harga mulai dari
                                        </p>

                                        @if ($package->price !== null)
                                            <p class="mt-1 text-2xl font-extrabold text-amber-600">
                                                Rp{{ number_format((float) $package->price, 0, ',', '.') }}
                                            </p>
                                        @else
                                            <p class="mt-1 text-lg font-bold text-gray-700">
                                                Hubungi Kami
                                            </p>
                                        @endif

                                        <a
                                            href="/kontak"
                                            class="mt-5 flex w-full items-center justify-center gap-2 rounded-xl bg-amber-400 px-5 py-3 text-sm font-bold text-gray-900 transition hover:bg-amber-500"
                                        >
                                            Konsultasi Paket

                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 width="17" height="17"
                                                 viewBox="0 0 24 24"
                                                 fill="none" stroke="currentColor"
                                                 stroke-width="2"
                                                 stroke-linecap="round"
                                                 stroke-linejoin="round">
                                                <path d="M5 12h14M12 5l7 7-7 7"/>
                                            </svg>
                                        </a>

                                    </div>

                                </div>

                            </div>

                        @endforeach

                    </div>

                </div>

            </section>

        @endif

    </main>

    {{-- FOOTER --}}
    <footer class="bg-gray-950 text-white">
        <div class="mx-auto grid max-w-7xl gap-12 px-5 py-16 md:grid-cols-3 lg:px-8">

            <div>
                <div class="flex items-center gap-3">
                    <img
                        src="{{ asset('images/ansar-travel-logo.png') }}"
                        alt="Logo Ansar Travel"
                        class="h-12 w-12 object-contain brightness-0 invert"
                    >

                    <h3 class="text-xl font-extrabold">
                        ANSHAR TRAVEL
                    </h3>
                </div>

                <p class="mt-5 max-w-sm text-sm leading-7 text-gray-400">
                    Mitra perjalanan ibadah Anda. Temukan informasi layanan dan perjalanan bersama Ansar Travel.
                </p>
            </div>

            <div>
                <h3 class="text-lg font-bold">Navigasi</h3>

                <div class="mt-5 grid grid-cols-2 gap-4 text-sm text-gray-400">
                    <a href="/" class="hover:text-amber-400">Beranda</a>
                    <a href="/paket-umroh" class="hover:text-amber-400">Paket Umroh</a>
                    <a href="/jadwal" class="hover:text-amber-400">Jadwal</a>
                    <a href="/tentang-kami" class="hover:text-amber-400">Tentang Kami</a>
                    <a href="/info" class="hover:text-amber-400">Info</a>
                    <a href="/kontak" class="hover:text-amber-400">Kontak</a>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-bold">Hubungi Kami</h3>

                <p class="mt-5 text-sm leading-7 text-gray-400">
                    Untuk informasi lebih lanjut mengenai layanan dan perjalanan ibadah, silakan hubungi tim Ansar Travel.
                </p>

                <a
                    href="/kontak"
                    class="mt-5 inline-block rounded-full bg-amber-400 px-6 py-3 text-sm font-bold text-gray-900 hover:bg-amber-500"
                >
                    Hubungi Kami
                </a>
            </div>

        </div>

        <div class="border-t border-white/10 px-5 py-6 text-center text-sm text-gray-500">
            &copy; {{ date('Y') }} Ansar Travel. Seluruh hak cipta dilindungi.
        </div>
    </footer>

    {{-- MOBILE MENU SCRIPT --}}
    <script>
        const menuButton = document.getElementById('menu-button');
        const mobileMenu = document.getElementById('mobile-menu');

        menuButton?.addEventListener('click', function () {
            mobileMenu.classList.toggle('hidden');

            const isExpanded = !mobileMenu.classList.contains('hidden');
            menuButton.setAttribute('aria-expanded', isExpanded);
        });
    </script>

</body>
</html>
