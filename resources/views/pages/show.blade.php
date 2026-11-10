
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

                @elseif ($section->section_type === 'why_choose_us')

                    @php
                        $reasonContent = $section->content ?? [];

                        if (is_string($reasonContent)) {
                            $reasonContent = json_decode($reasonContent, true) ?? [];
                        }

                        $reasonCards = $reasonContent['cards']
                            ?? $reasonContent['items']
                            ?? [];

                        $reasonCards = is_array($reasonCards)
                            ? array_values($reasonCards)
                            : [];
                    @endphp

                    <section class="bg-gray-50 px-5 py-20 md:py-24">

                        <div class="mx-auto max-w-7xl">

                            <div class="mx-auto mb-14 max-w-3xl text-center">

                                <span class="text-sm font-bold uppercase tracking-widest text-amber-600">
                                    {{ $section->subtitle ?: 'Alasan Jamaah Memilih Ansar Travel' }}
                                </span>

                                <h2 class="mt-4 text-3xl font-extrabold leading-tight text-gray-900 md:text-4xl">
                                    {{ $section->title ?: 'Mengapa Memilih Ansar Travel?' }}
                                </h2>

                                @if (data_get($reasonContent, 'description'))
                                    <p class="mx-auto mt-5 max-w-2xl leading-8 text-gray-600">
                                        {{ data_get($reasonContent, 'description') }}
                                    </p>
                                @endif

                            </div>

                            @if (count($reasonCards))

                                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">

                                    @foreach ($reasonCards as $card)

                                        <article class="rounded-2xl border border-gray-200 bg-white p-7 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-amber-300 hover:shadow-lg">

                                            @if (!empty($card['icon']))
                                                <div class="mb-5 flex h-14 w-14 items-center justify-center rounded-xl bg-amber-50 text-3xl">
                                                    {{ $card['icon'] }}
                                                </div>
                                            @endif

                                            <h3 class="text-lg font-bold text-gray-900">
                                                {{ $card['title'] ?? 'Keunggulan Ansar Travel' }}
                                            </h3>

                                            <p class="mt-3 text-sm leading-7 text-gray-600">
                                                {{ $card['description'] ?? '' }}
                                            </p>

                                        </article>

                                    @endforeach

                                </div>

                            @else

                                <div class="rounded-xl border border-gray-200 bg-white px-6 py-10 text-center">
                                    <p class="font-semibold text-gray-700">
                                        Belum ada data keunggulan.
                                    </p>
                                    <p class="mt-2 text-sm text-gray-500">
                                        Silakan tambahkan card melalui pengelolaan section.
                                    </p>
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


            @elseif ($section->section_type === 'gallery')

                @php
                    $galleryItems = collect(data_get($section->content, 'items', []))
                        ->filter(fn ($photo) => is_array($photo) && !empty($photo['image_path']))
                        ->sortBy('sort_order')
                        ->values();

                    $galleryCarouselId = 'gallery-carousel-' . $section->id;
                    $galleryTotal = $galleryItems->count();
                @endphp

                <section class="relative isolate overflow-hidden bg-[#08111F] px-5 py-20 text-white md:py-28">
                    <div class="pointer-events-none absolute -right-32 -top-32 h-96 w-96 rounded-full bg-amber-400/10 blur-3xl"></div>
                    <div class="pointer-events-none absolute -bottom-40 -left-32 h-96 w-96 rounded-full bg-blue-500/10 blur-3xl"></div>

                    <div class="relative mx-auto max-w-7xl">
                        <div class="mb-10 flex flex-col gap-7 lg:mb-12 lg:flex-row lg:items-end lg:justify-between">
                            <div class="max-w-3xl">
                                <div class="mb-5 inline-flex items-center gap-2 rounded-full border border-amber-300/30 bg-amber-300/10 px-4 py-2">
                                    <span class="h-2 w-2 rounded-full bg-amber-400"></span>
                                    <span class="text-[11px] font-bold uppercase tracking-[0.22em] text-amber-300">
                                        {{ $section->subtitle ?: 'Dokumentasi Ansar Travel' }}
                                    </span>
                                </div>

                                <h2 class="text-3xl font-extrabold leading-tight tracking-tight text-white md:text-4xl lg:text-5xl">
                                    {{ $section->title ?: 'Momen Perjalanan Bersama Ansar Travel' }}
                                </h2>

                                @if (data_get($section->content, 'description'))
                                    <p class="mt-5 max-w-2xl text-sm leading-8 text-slate-300 md:text-base">
                                        {{ data_get($section->content, 'description') }}
                                    </p>
                                @else
                                    <p class="mt-5 max-w-2xl text-sm leading-8 text-slate-300 md:text-base">
                                        Rekam jejak perjalanan, kebersamaan, dan pengalaman jamaah dalam setiap langkah bersama Ansar Travel.
                                    </p>
                                @endif
                            </div>

                            @if ($galleryItems->isNotEmpty())
                                <div class="flex shrink-0 items-center justify-between gap-5 lg:pb-1">
                                    <div class="text-left">
                                        <p class="text-3xl font-extrabold leading-none text-white md:text-4xl">
                                            {{ str_pad((string) $galleryTotal, 2, '0', STR_PAD_LEFT) }}
                                        </p>
                                        <p class="mt-2 text-xs font-medium tracking-wide text-slate-400">FOTO DOKUMENTASI</p>
                                    </div>
                                    <div class="flex gap-2">
                                        <button
                                            type="button"
                                            data-carousel-prev="{{ $galleryCarouselId }}"
                                            class="flex h-12 w-12 items-center justify-center rounded-full border border-white/15 bg-white/5 text-xl text-white shadow-lg backdrop-blur transition hover:border-amber-300 hover:bg-amber-400 hover:text-slate-950 focus:outline-none focus:ring-2 focus:ring-amber-300"
                                            aria-label="Foto sebelumnya"
                                        >
                                            &#8592;
                                        </button>
                                        <button
                                            type="button"
                                            data-carousel-next="{{ $galleryCarouselId }}"
                                            class="flex h-12 w-12 items-center justify-center rounded-full border border-white/15 bg-white/5 text-xl text-white shadow-lg backdrop-blur transition hover:border-amber-300 hover:bg-amber-400 hover:text-slate-950 focus:outline-none focus:ring-2 focus:ring-amber-300"
                                            aria-label="Foto berikutnya"
                                        >
                                            &#8594;
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>

                        @if ($galleryItems->isNotEmpty())
                            <div class="relative">
                                <div
                                    id="{{ $galleryCarouselId }}"
                                    data-gallery-carousel
                                    class="gallery-carousel flex snap-x snap-mandatory gap-5 overflow-x-auto scroll-smooth pb-5"
                                    style="scrollbar-width:none;"
                                >
                                    @foreach ($galleryItems as $photo)
                                        @php
                                            $photoAlt = $photo['alt'] ?? $photo['caption'] ?? 'Foto galeri Ansar Travel';
                                            $photoCaption = $photo['caption'] ?? '';
                                        @endphp

                                        <figure class="group relative w-[88%] shrink-0 snap-start overflow-hidden rounded-[1.6rem] border border-white/10 bg-slate-900 shadow-2xl shadow-black/20 sm:w-[62%] lg:w-[calc(33.333%-0.85rem)]">
                                            <button
                                                type="button"
                                                class="relative block aspect-[4/3] w-full cursor-zoom-in overflow-hidden text-left"
                                                data-gallery-trigger
                                                data-gallery-group="section-{{ $section->id }}"
                                                data-gallery-src="{{ asset('storage/' . $photo['image_path']) }}"
                                                data-gallery-alt="{{ $photoAlt }}"
                                                data-gallery-caption="{{ $photoCaption }}"
                                                aria-label="Lihat foto {{ $photoAlt }}"
                                            >
                                                <img
                                                    src="{{ asset('storage/' . $photo['image_path']) }}"
                                                    alt="{{ $photoAlt }}"
                                                    class="h-full w-full object-cover transition duration-700 ease-out group-hover:scale-110"
                                                    loading="lazy"
                                                >
                                                <div class="absolute inset-0 bg-gradient-to-t from-[#020617]/90 via-[#020617]/10 to-transparent"></div>
                                                <div class="absolute left-5 top-5 flex items-center gap-2">
                                                    <span class="rounded-full border border-white/20 bg-black/30 px-3 py-1.5 text-[10px] font-bold uppercase tracking-[0.18em] text-white backdrop-blur-md">
                                                        Ansar Moment
                                                    </span>
                                                </div>
                                                <div class="absolute right-5 top-5 flex h-10 w-10 items-center justify-center rounded-full border border-white/20 bg-black/30 text-white opacity-0 backdrop-blur-md transition duration-300 group-hover:opacity-100">
                                                    <span class="text-lg">↗</span>
                                                </div>
                                                <figcaption class="absolute inset-x-0 bottom-0 p-5 md:p-6">
                                                    <div class="mb-2 text-xs font-semibold tracking-[0.16em] text-amber-300">
                                                        MOMEN {{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}
                                                    </div>
                                                    <p class="line-clamp-2 text-base font-bold leading-6 text-white md:text-lg">
                                                        {{ $photoCaption ?: 'Perjalanan dan kebersamaan jamaah Ansar Travel' }}
                                                    </p>
                                                    <p class="mt-2 text-xs text-slate-300">Klik untuk melihat foto lebih besar</p>
                                                </figcaption>
                                            </button>
                                        </figure>
                                    @endforeach
                                </div>

                                <div class="mt-3 flex items-center gap-4">
                                    <div class="h-1.5 flex-1 overflow-hidden rounded-full bg-white/10">
                                        <div data-gallery-progress="{{ $galleryCarouselId }}" class="h-full w-0 rounded-full bg-amber-400 transition-all duration-300"></div>
                                    </div>
                                    <span data-gallery-position="{{ $galleryCarouselId }}" class="min-w-[4.5rem] text-right text-xs font-semibold tabular-nums text-slate-400">01 / {{ str_pad((string) $galleryTotal, 2, '0', STR_PAD_LEFT) }}</span>
                                </div>
                            </div>
                        @else
                            <div class="rounded-2xl border border-white/10 bg-white/5 px-6 py-12 text-center text-sm text-slate-300">
                                Belum ada foto galeri yang dapat ditampilkan. Periksa kembali foto pada form Edit Section.
                            </div>
                        @endif
                    </div>
                </section>

                        @elseif ($section->section_type === 'contact')

                @php
                    $contactData = data_get($section->content, 'contact', []);
                    $whatsappDigits = preg_replace('/\\D+/', '', (string) ($contactData['whatsapp'] ?? ''));
                    $phoneDigits = preg_replace('/[^0-9+]/', '', (string) ($contactData['phone'] ?? ''));
                    $whatsappMessage = rawurlencode('Assalamu alaikum, saya ingin mendapatkan informasi mengenai layanan Ansar Travel.');
                @endphp

                <section class="relative isolate overflow-hidden bg-[#08111F] px-5 py-20 text-white md:py-24">
                    <div class="pointer-events-none absolute -right-32 -top-32 h-96 w-96 rounded-full bg-amber-400/10 blur-3xl"></div>
                    <div class="pointer-events-none absolute -bottom-40 -left-32 h-96 w-96 rounded-full bg-blue-500/10 blur-3xl"></div>

                    <div class="relative mx-auto grid max-w-7xl gap-10 lg:grid-cols-[0.85fr_1.15fr] lg:items-center lg:gap-16">
                        <div class="flex flex-col justify-center">
                            <div class="mb-5 inline-flex w-fit items-center gap-2 rounded-full border border-amber-300/30 bg-amber-300/10 px-4 py-2">
                                <span class="h-2 w-2 rounded-full bg-amber-400"></span>
                                <span class="text-[11px] font-bold uppercase tracking-[0.2em] text-amber-300">{{ $section->subtitle ?: 'Hubungi Ansar Travel' }}</span>
                            </div>

                            <h2 class="text-3xl font-extrabold leading-tight tracking-tight text-white md:text-4xl lg:text-5xl">
                                {{ $section->title ?: 'Kami Siap Membantu Perjalanan Ibadah Anda' }}
                            </h2>

                            <p class="mt-5 max-w-xl text-sm leading-8 text-slate-300 md:text-base">
                                {{ data_get($section->content, 'description') ?: 'Hubungi tim Ansar Travel untuk mendapatkan informasi paket, jadwal keberangkatan, dan layanan perjalanan umroh.' }}
                            </p>

                            @if ($whatsappDigits)
                                <a href="https://wa.me/{{ $whatsappDigits }}?text={{ $whatsappMessage }}" target="_blank" rel="noopener noreferrer" class="mt-8 inline-flex w-fit items-center gap-3 rounded-full bg-amber-400 px-7 py-4 text-sm font-extrabold text-slate-950 shadow-lg shadow-amber-400/10 transition hover:-translate-y-0.5 hover:bg-amber-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M20.52 3.48A11.78 11.78 0 0 0 12.13 0C5.6 0 .29 5.31.29 11.84c0 2.09.55 4.13 1.6 5.93L0 24l6.39-1.68a11.8 11.8 0 0 0 5.74 1.46h.01c6.53 0 11.84-5.31 11.84-11.84 0-3.17-1.23-6.15-3.46-8.46ZM12.14 21.77a9.9 9.9 0 0 1-5.04-1.38l-.36-.21-3.79 1 1.01-3.7-.24-.38a9.88 9.88 0 1 1 8.42 4.67Zm5.42-7.4c-.3-.15-1.78-.88-2.05-.98-.28-.1-.48-.15-.68.15-.2.3-.78.98-.96 1.18-.18.2-.35.22-.65.07-.3-.15-1.27-.47-2.42-1.5-.9-.8-1.5-1.78-1.68-2.08-.18-.3-.02-.46.13-.61.13-.13.3-.35.45-.53.15-.18.2-.3.3-.5.1-.2.05-.38-.02-.53-.08-.15-.68-1.63-.93-2.23-.24-.58-.49-.5-.68-.51h-.58c-.2 0-.53.07-.8.38-.28.3-1.05 1.03-1.05 2.5s1.08 2.9 1.23 3.1c.15.2 2.12 3.24 5.14 4.54.72.31 1.28.5 1.72.64.72.23 1.38.2 1.9.12.58-.09 1.78-.73 2.03-1.43.25-.7.25-1.3.18-1.43-.08-.13-.28-.2-.58-.35Z"/></svg>
                                    Hubungi via WhatsApp
                                    <span aria-hidden="true">&#8599;</span>
                                </a>
                            @endif

                            <div class="mt-8 flex flex-wrap gap-2 text-xs font-semibold text-slate-300">
                                @if (!empty($contactData['instagram']))
                                    <a href="{{ $contactData['instagram'] }}" target="_blank" rel="noopener noreferrer" class="rounded-full border border-white/15 px-4 py-2.5 transition hover:border-amber-300 hover:text-amber-300">Instagram</a>
                                @endif
                                @if (!empty($contactData['facebook']))
                                    <a href="{{ $contactData['facebook'] }}" target="_blank" rel="noopener noreferrer" class="rounded-full border border-white/15 px-4 py-2.5 transition hover:border-amber-300 hover:text-amber-300">Facebook</a>
                                @endif
                                @if (!empty($contactData['website']))
                                    <a href="{{ $contactData['website'] }}" target="_blank" rel="noopener noreferrer" class="rounded-full border border-white/15 px-4 py-2.5 transition hover:border-amber-300 hover:text-amber-300">Website</a>
                                @endif
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                @if (!empty($contactData['whatsapp']))
                                    <a href="https://wa.me/{{ $whatsappDigits }}?text={{ $whatsappMessage }}" target="_blank" rel="noopener noreferrer" class="group flex min-h-[132px] items-start gap-4 rounded-2xl border border-white/10 bg-white/[0.06] p-5 shadow-lg shadow-black/10 backdrop-blur transition duration-300 hover:-translate-y-1 hover:border-emerald-300/40 hover:bg-white/[0.09]">
                                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-400/10 text-emerald-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M20.52 3.48A11.78 11.78 0 0 0 12.13 0C5.6 0 .29 5.31.29 11.84c0 2.09.55 4.13 1.6 5.93L0 24l6.39-1.68a11.8 11.8 0 0 0 5.74 1.46h.01c6.53 0 11.84-5.31 11.84-11.84 0-3.17-1.23-6.15-3.46-8.46ZM12.14 21.77a9.9 9.9 0 0 1-5.04-1.38l-.36-.21-3.79 1 1.01-3.7-.24-.38a9.88 9.88 0 1 1 8.42 4.67Z"/></svg>
                                        </span>
                                        <span class="min-w-0 flex-1">
                                            <span class="block text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">WhatsApp</span>
                                            <span class="mt-2 block break-words text-base font-bold leading-6 text-white">{{ $contactData['whatsapp'] }}</span>
                                            <span class="mt-3 inline-flex items-center gap-1 text-xs font-semibold text-emerald-300">Kirim pesan <span aria-hidden="true">&#8599;</span></span>
                                        </span>
                                    </a>
                                @endif

                                @if (!empty($contactData['email']))
                                    <a href="mailto:{{ $contactData['email'] }}" class="group flex min-h-[132px] items-start gap-4 rounded-2xl border border-white/10 bg-white/[0.06] p-5 shadow-lg shadow-black/10 backdrop-blur transition duration-300 hover:-translate-y-1 hover:border-blue-300/40 hover:bg-white/[0.09]">
                                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-400/10 text-blue-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
                                        </span>
                                        <span class="min-w-0 flex-1">
                                            <span class="block text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">Email</span>
                                            <span class="mt-2 block break-all text-sm font-bold leading-6 text-white">{{ $contactData['email'] }}</span>
                                            <span class="mt-3 inline-flex items-center gap-1 text-xs font-semibold text-blue-300">Kirim email <span aria-hidden="true">&#8599;</span></span>
                                        </span>
                                    </a>
                                @endif

                                @if (!empty($contactData['phone']))
                                    <a href="tel:{{ $phoneDigits }}" class="group flex min-h-[132px] items-start gap-4 rounded-2xl border border-white/10 bg-white/[0.06] p-5 shadow-lg shadow-black/10 backdrop-blur transition duration-300 hover:-translate-y-1 hover:border-amber-300/40 hover:bg-white/[0.09]">
                                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-amber-400/10 text-amber-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72l.45 2.7a2 2 0 0 1-.57 1.72L7.1 10.03a16 16 0 0 0 6 6l1.89-1.89a2 2 0 0 1 1.72-.57l2.7.45A2 2 0 0 1 22 16.92Z"/></svg>
                                        </span>
                                        <span class="min-w-0 flex-1">
                                            <span class="block text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">Telepon</span>
                                            <span class="mt-2 block break-words text-base font-bold leading-6 text-white">{{ $contactData['phone'] }}</span>
                                            <span class="mt-3 inline-flex items-center gap-1 text-xs font-semibold text-amber-300">Hubungi sekarang <span aria-hidden="true">&#8599;</span></span>
                                        </span>
                                    </a>
                                @endif
                            </div>

                            @if (!empty($contactData['office_hours']))
                                <div class="flex items-center gap-4 rounded-2xl border border-white/10 bg-white/[0.06] px-5 py-5 shadow-lg shadow-black/10 backdrop-blur">
                                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-violet-400/10 text-violet-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <span class="block text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">Jam Operasional</span>
                                        <span class="mt-1 block text-sm font-bold leading-6 text-white">{{ $contactData['office_hours'] }}</span>
                                    </div>
                                    <span class="hidden rounded-full border border-violet-300/20 bg-violet-300/10 px-3 py-1.5 text-[10px] font-semibold text-violet-200 sm:inline-flex">Waktu layanan</span>
                                </div>
                            @endif

                            @if (!empty($contactData['address']) || !empty($contactData['maps_url']))
                                <div class="flex items-start gap-4 rounded-2xl border border-white/10 bg-white/[0.06] px-5 py-5 shadow-lg shadow-black/10 backdrop-blur">
                                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-rose-400/10 text-rose-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/></svg>
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <span class="block text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">Alamat Kantor</span>
                                        @if (!empty($contactData['address']))
                                            <p class="mt-1 text-sm leading-7 text-white">{{ $contactData['address'] }}</p>
                                        @endif
                                        @if (!empty($contactData['maps_url']))
                                            <a href="{{ $contactData['maps_url'] }}" target="_blank" rel="noopener noreferrer" class="mt-2 inline-flex items-center gap-2 text-xs font-bold text-amber-300 transition hover:text-amber-200">
                                                Buka lokasi di Google Maps <span aria-hidden="true">&#8599;</span>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            @if (empty(array_filter($contactData)))
                                <div class="rounded-2xl border border-white/10 bg-white/[0.06] p-6 text-sm leading-7 text-slate-300">
                                    Informasi kontak belum dilengkapi. Silakan lengkapi melalui menu Kelola Section.
                                </div>
                            @endif
                        </div>
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


<div id="gallery-lightbox" class="fixed inset-0 z-[100] hidden items-center justify-center bg-[#020617]/95 p-4 backdrop-blur-xl" aria-hidden="true" role="dialog" aria-modal="true" aria-label="Pratinjau foto galeri">
    <button type="button" data-gallery-close class="absolute right-5 top-5 z-20 flex h-12 w-12 items-center justify-center rounded-full border border-white/15 bg-white/10 text-3xl text-white transition hover:bg-white/20" aria-label="Tutup">&times;</button>
    <button type="button" data-gallery-prev class="absolute left-3 top-1/2 z-20 flex h-12 w-12 -translate-y-1/2 items-center justify-center rounded-full border border-white/15 bg-white/10 text-2xl text-white transition hover:bg-amber-400 hover:text-slate-950 md:left-8" aria-label="Foto sebelumnya">&#8592;</button>
    <figure class="flex max-h-full max-w-6xl flex-col items-center justify-center">
        <img id="gallery-lightbox-image" src="" alt="" class="max-h-[78vh] max-w-full rounded-2xl object-contain shadow-2xl">
        <figcaption id="gallery-lightbox-caption" class="mt-5 text-center text-sm font-medium text-white/90"></figcaption>
        <p id="gallery-lightbox-count" class="mt-2 text-xs tracking-widest text-slate-400"></p>
    </figure>
    <button type="button" data-gallery-next class="absolute right-3 top-1/2 z-20 flex h-12 w-12 -translate-y-1/2 items-center justify-center rounded-full border border-white/15 bg-white/10 text-2xl text-white transition hover:bg-amber-400 hover:text-slate-950 md:right-8" aria-label="Foto berikutnya">&#8594;</button>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    function updateGalleryState(carousel) {
        if (!carousel) return;
        const id = carousel.id;
        const maxScroll = Math.max(carousel.scrollWidth - carousel.clientWidth, 1);
        const progress = Math.min(100, (carousel.scrollLeft / maxScroll) * 100);
        const progressBar = document.querySelector(`[data-gallery-progress="${id}"]`);
        if (progressBar) progressBar.style.width = `${progress}%`;

        const cards = Array.from(carousel.children);
        const firstCard = cards.find(card => card.offsetLeft + card.offsetWidth > carousel.scrollLeft + 4);
        const activeIndex = firstCard ? cards.indexOf(firstCard) + 1 : cards.length;
        const counter = document.querySelector(`[data-gallery-position="${id}"]`);
        if (counter) counter.textContent = `${String(activeIndex).padStart(2, '0')} / ${String(cards.length).padStart(2, '0')}`;
    }

    document.querySelectorAll('[data-gallery-carousel]').forEach(carousel => {
        updateGalleryState(carousel);
        carousel.addEventListener('scroll', () => updateGalleryState(carousel), { passive: true });
    });

    document.querySelectorAll('[data-carousel-prev], [data-carousel-next]').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.dataset.carouselPrev || this.dataset.carouselNext;
            const carousel = document.getElementById(id);
            if (!carousel) return;
            const firstCard = carousel.querySelector('figure');
            const gap = 20;
            const distance = firstCard ? firstCard.getBoundingClientRect().width + gap : carousel.clientWidth * 0.85;
            const direction = this.hasAttribute('data-carousel-prev') ? -1 : 1;
            carousel.scrollBy({ left: direction * distance, behavior: 'smooth' });
        });
    });

    const modal = document.getElementById('gallery-lightbox');
    if (!modal) return;

    const modalImage = document.getElementById('gallery-lightbox-image');
    const modalCaption = document.getElementById('gallery-lightbox-caption');
    const modalCount = document.getElementById('gallery-lightbox-count');
    let currentGroup = [];
    let currentIndex = 0;
    let previousFocus = null;

    function renderImage() {
        const item = currentGroup[currentIndex];
        if (!item) return;
        modalImage.src = item.dataset.gallerySrc;
        modalImage.alt = item.dataset.galleryAlt || 'Foto galeri';
        modalCaption.textContent = item.dataset.galleryCaption || item.dataset.galleryAlt || '';
        modalCount.textContent = `${String(currentIndex + 1).padStart(2, '0')} / ${String(currentGroup.length).padStart(2, '0')}`;
    }

    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        modal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('overflow-hidden');
        modalImage.src = '';
        currentGroup = [];
        currentIndex = 0;
        if (previousFocus) previousFocus.focus();
    }

    document.addEventListener('click', function (event) {
        const trigger = event.target.closest('[data-gallery-trigger]');
        if (trigger) {
            const groupName = trigger.dataset.galleryGroup;
            currentGroup = Array.from(document.querySelectorAll('[data-gallery-trigger]'))
                .filter(element => element.dataset.galleryGroup === groupName);
            currentIndex = currentGroup.indexOf(trigger);
            previousFocus = trigger;
            renderImage();
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            modal.setAttribute('aria-hidden', 'false');
            document.body.classList.add('overflow-hidden');
            const closeButton = modal.querySelector('[data-gallery-close]');
            if (closeButton) closeButton.focus();
            return;
        }

        if (event.target.closest('[data-gallery-close]') || event.target === modal) {
            closeModal();
            return;
        }

        if (event.target.closest('[data-gallery-prev]') && currentGroup.length > 1) {
            currentIndex = (currentIndex - 1 + currentGroup.length) % currentGroup.length;
            renderImage();
        }

        if (event.target.closest('[data-gallery-next]') && currentGroup.length > 1) {
            currentIndex = (currentIndex + 1) % currentGroup.length;
            renderImage();
        }
    });

    document.addEventListener('keydown', function (event) {
        if (modal.classList.contains('hidden')) return;
        if (event.key === 'Escape') closeModal();
        if (event.key === 'ArrowLeft' && currentGroup.length > 1) {
            currentIndex = (currentIndex - 1 + currentGroup.length) % currentGroup.length;
            renderImage();
        }
        if (event.key === 'ArrowRight' && currentGroup.length > 1) {
            currentIndex = (currentIndex + 1) % currentGroup.length;
            renderImage();
        }
    });
});
</script>

</body>
</html>
