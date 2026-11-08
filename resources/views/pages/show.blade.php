
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $page->meta_title ?: $page->title . ' - Ansar Travel' }}</title>

    @if ($page->meta_description)
        <meta name="description" content="{{ $page->meta_description }}">
    @elseif ($page->description)
        <meta name="description" content="{{ $page->description }}">
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen flex flex-col bg-white text-gray-800 antialiased">

    @php
        $visibleSections = $page->sections->sortBy('sort_order')->values();

        $hasHero = $visibleSections->contains('section_type', 'hero');
    @endphp

    {{-- NAVBAR --}}
    @include('partials.public-navbar')

    <main class="flex-1">

        {{-- BANNER DEFAULT JIKA TIDAK ADA SECTION HERO --}}
        @unless ($hasHero)
            <section class="bg-[#FDD52E] px-5 py-16 text-center md:py-20">
                <div class="mx-auto max-w-4xl">

                    <span class="text-sm font-bold uppercase tracking-widest text-gray-800">
                        ANSAR TRAVEL
                    </span>

                    <h1 class="mt-5 text-3xl font-extrabold text-gray-900 md:text-5xl">
                        {{ $page->title }}
                    </h1>

                    @if ($page->description)
                        <p class="mx-auto mt-5 max-w-2xl leading-8 text-gray-800">
                            {{ $page->description }}
                        </p>
                    @endif

                </div>
            </section>
        @endunless


        {{-- MENAMPILKAN SECTION BERDASARKAN URUTAN --}}
        @foreach ($visibleSections as $section)

            {{-- HERO --}}
            @if ($section->section_type === 'hero')

                <section
                    class="relative flex min-h-[450px] items-center overflow-hidden bg-gray-950 bg-cover bg-center md:min-h-[600px]"
                    style="background-image: url('{{ $section->image_path ? asset('storage/' . $section->image_path) : asset('images/hero-masjidil-haram.png') }}');"
                >

                    <div class="absolute inset-0 bg-gradient-to-r from-gray-950/95 via-gray-950/75 to-gray-950/30"></div>

                    <div class="relative z-10 mx-auto w-full max-w-7xl px-5 py-24 lg:px-8">

                        <div class="max-w-2xl">

                            <span class="mb-5 inline-block rounded-full border border-amber-300/50 bg-black/20 px-5 py-2 text-sm font-semibold text-amber-300">
                                {{ $section->subtitle ?: 'Ansar Travel' }}
                            </span>

                            <h2 class="text-4xl font-extrabold leading-tight text-white md:text-5xl">
                                {{ $section->title }}
                            </h2>

                            @if (data_get($section->content, 'description'))
                                <p class="mt-6 max-w-xl text-base leading-8 text-gray-200">
                                    {{ data_get($section->content, 'description') }}
                                </p>
                            @endif

                            @if ($section->button_text)
                                <a
                                    href="{{ $section->button_url ?: '/kontak' }}"
                                    class="mt-8 inline-block rounded-full bg-amber-400 px-7 py-4 text-sm font-bold text-gray-950 transition hover:bg-amber-500"
                                >
                                    {{ $section->button_text }}
                                </a>
                            @endif

                        </div>

                    </div>

                </section>


            {{-- STATISTIK --}}
            @elseif ($section->section_type === 'statistics')

                @php
                    $statItems = data_get($section->content, 'items', []);
                @endphp

                @if (count($statItems))
                    <section class="bg-white px-5 py-12">

                        <div class="mx-auto max-w-6xl">

                            <div class="flex flex-wrap justify-center gap-y-8 rounded-2xl bg-white px-5 py-8 shadow-xl">

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

                        </div>

                    </section>
                @endif


            {{-- TENTANG KAMI --}}
            @elseif (in_array($section->section_type, ['about', 'about_us']))

                <section class="bg-white px-5 py-16 md:py-20">

                    <div class="mx-auto grid max-w-7xl grid-cols-1 items-center gap-10 md:grid-cols-2">

                        {{-- GAMBAR --}}
                        @if ($section->image_path)
                            <div class="overflow-hidden rounded-2xl">
                                <img
                                    src="{{ asset('storage/' . $section->image_path) }}"
                                    alt="{{ $section->title }}"
                                    class="max-h-[450px] w-full object-cover"
                                    loading="lazy"
                                >
                            </div>
                        @endif

                        {{-- KONTEN --}}
                        <div class="max-w-2xl">

                            <span class="text-sm font-bold uppercase tracking-widest text-amber-600">
                                {{ $section->subtitle ?: 'Tentang Ansar Travel' }}
                            </span>

                            <h2 class="mt-4 text-3xl font-extrabold leading-tight text-gray-900 md:text-4xl">
                                {{ $section->title }}
                            </h2>

                            @if (data_get($section->content, 'description'))
                                <p class="mt-5 leading-8 text-gray-600">
                                    {{ data_get($section->content, 'description') }}
                                </p>
                            @endif

                        </div>

                    </div>

                </section>


            {{-- MENGAPA MEMILIH ANSAR TRAVEL --}}
            @elseif ($section->section_type === 'why_choose_us')

                @php
                    $reasonCards = data_get($section->content, 'cards', []);
                @endphp

                <section class="bg-gray-50 px-5 py-20">

                    <div class="mx-auto max-w-7xl">

                        <div class="mx-auto mb-12 max-w-3xl text-center">

                            <span class="text-sm font-bold uppercase tracking-widest text-amber-600">
                                {{ $section->subtitle ?: 'Mengapa Memilih Kami' }}
                            </span>

                            <h2 class="mt-4 text-3xl font-extrabold text-gray-900 md:text-4xl">
                                {{ $section->title }}
                            </h2>

                            @if (data_get($section->content, 'description'))
                                <p class="mt-5 leading-8 text-gray-600">
                                    {{ data_get($section->content, 'description') }}
                                </p>
                            @endif

                        </div>

                        @if (count($reasonCards))
                            <div class="flex flex-wrap justify-center gap-6">

                                @foreach ($reasonCards as $card)
                                    <div class="w-full rounded-2xl border border-gray-200 bg-white p-7 shadow-sm sm:w-[calc(50%-12px)] lg:w-[calc(33.333%-16px)]">

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


            {{-- LAYANAN --}}
            @elseif ($section->section_type === 'services')

                @php
                    $serviceCards = data_get($section->content, 'cards', []);
                @endphp

                <section class="bg-white px-5 py-20">

                    <div class="mx-auto max-w-7xl">

                        <div class="mx-auto mb-12 max-w-3xl text-center">

                            <span class="text-sm font-bold uppercase tracking-widest text-amber-600">
                                {{ $section->subtitle ?: 'Layanan Kami' }}
                            </span>

                            <h2 class="mt-4 text-3xl font-extrabold text-gray-900 md:text-4xl">
                                {{ $section->title }}
                            </h2>

                            @if (data_get($section->content, 'description'))
                                <p class="mt-5 leading-8 text-gray-600">
                                    {{ data_get($section->content, 'description') }}
                                </p>
                            @endif

                        </div>

                        @if (count($serviceCards))
                            <div class="flex flex-wrap justify-center gap-6">

                                @foreach ($serviceCards as $card)
                                    <div class="w-full rounded-2xl border border-gray-200 bg-white p-7 shadow-sm sm:w-[calc(50%-12px)] lg:w-[calc(33.333%-16px)]">

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


            {{-- PAKET UMROH --}}
            @elseif ($section->section_type === 'packages')

                <section class="bg-gray-50 px-5 py-20">

                    <div class="mx-auto max-w-7xl">

                        <div class="mb-12 text-center">

                            <span class="text-sm font-bold uppercase tracking-widest text-amber-600">
                                {{ $section->subtitle ?: 'Pilihan Perjalanan' }}
                            </span>

                            <h2 class="mt-4 text-3xl font-extrabold text-gray-900 md:text-4xl">
                                {{ $section->title ?: 'Paket Umroh Pilihan' }}
                            </h2>

                            @if (data_get($section->content, 'description'))
                                <p class="mx-auto mt-5 max-w-2xl leading-8 text-gray-600">
                                    {{ data_get($section->content, 'description') }}
                                </p>
                            @endif

                        </div>

                        @if ($packages->isNotEmpty())

                            <div class="grid grid-cols-1 gap-7 md:grid-cols-2 lg:grid-cols-3">

                                @foreach ($packages as $package)

                                    <article class="group overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-xl">

                                        @if ($package->image_path)
                                            <img
                                                src="{{ asset('storage/' . $package->image_path) }}"
                                                alt="{{ $package->name }}"
                                                class="h-56 w-full object-cover transition group-hover:scale-105"
                                                loading="lazy"
                                            >
                                        @else
                                            <div class="flex h-56 items-center justify-center bg-gray-100 text-sm text-gray-400">
                                                Foto paket belum tersedia
                                            </div>
                                        @endif

                                        <div class="p-6">

                                            <h3 class="text-xl font-bold text-gray-900">
                                                {{ $package->name }}
                                            </h3>

                                            <p class="mt-3 line-clamp-3 text-sm leading-7 text-gray-600">
                                                {{ $package->description ?: 'Informasi perjalanan umroh bersama Ansar Travel.' }}
                                            </p>

                                            <div class="mt-5 border-t border-gray-100 pt-5">

                                                <p class="text-xs text-gray-500">
                                                    Harga mulai dari
                                                </p>

                                                <p class="mt-1 text-2xl font-extrabold text-amber-600">
                                                    {{ $package->price !== null ? 'Rp' . number_format((float) $package->price, 0, ',', '.') : 'Hubungi Kami' }}
                                                </p>

                                                <a
                                                    href="{{ route('packages.show', $package->slug) }}"
                                                    class="mt-5 flex w-full justify-center rounded-xl bg-amber-400 px-5 py-3 text-sm font-bold text-gray-900 hover:bg-amber-500"
                                                >
                                                    Lihat Detail Paket
                                                </a>

                                            </div>

                                        </div>

                                    </article>

                                @endforeach

                            </div>

                        @else

                            <p class="py-12 text-center text-gray-500">
                                Belum ada paket umroh yang tersedia.
                            </p>

                        @endif

                    </div>

                </section>


            {{-- SECTION LAINNYA --}}
            @else

                <section class="bg-white px-5 py-16 md:py-20">

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

                            @if ($section->image_path)
                                <img
                                    src="{{ asset('storage/' . $section->image_path) }}"
                                    alt="{{ $section->title }}"
                                    class="mx-auto mt-8 max-h-96 rounded-2xl object-cover"
                                    loading="lazy"
                                >
                            @endif

                        </div>

                    </div>

                </section>

            @endif

        @endforeach

    </main>


    {{-- FOOTER --}}
    <footer class="bg-gray-950 text-white">

        <div class="mx-auto grid max-w-7xl gap-10 px-5 py-14 md:grid-cols-3 lg:px-8">

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

                <p class="mt-4 text-sm leading-7 text-gray-400">
                    Mitra perjalanan ibadah Anda. Temukan informasi layanan
                    dan perjalanan bersama Ansar Travel.
                </p>
            </div>

            <div>
                <h3 class="font-bold">Navigasi</h3>

                <div class="mt-4 grid grid-cols-2 gap-4 text-sm text-gray-400">
                    @foreach ($navbarPages as $navPage)
                        @php
                            if ($navPage->slug === 'beranda') {
                                $footerUrl = url('/');
                            } elseif (in_array($navPage->slug, ['layanan', 'paket-umroh'])) {
                                $footerUrl = route('packages.index');
                            } else {
                                $footerUrl = url('/' . $navPage->slug);
                            }

                            $footerTitle = $navPage->slug === 'layanan'
                                ? 'Paket Umroh'
                                : $navPage->title;
                        @endphp

                        <a href="{{ $footerUrl }}" class="hover:text-amber-400">
                            {{ $footerTitle }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div>
                <h3 class="font-bold">Hubungi Kami</h3>

                <p class="mt-4 text-sm leading-7 text-gray-400">
                    Untuk informasi lebih lanjut mengenai layanan
                    dan perjalanan ibadah, silakan hubungi tim Ansar Travel.
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

</body>
</html>
