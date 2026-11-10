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
        $visibleSections = $page->sections
            ->where('status', 'published')
            ->where('is_visible', true)
            ->sortBy('sort_order')
            ->values();

        $hasPackagesPage = $navbarPages->contains('slug', 'paket-umroh');
    @endphp

    @include('partials.public-navbar')

    <main class="flex-1">

        @foreach ($visibleSections as $section)

            @if ($section->section_type === 'hero')

                <section
                    class="relative flex min-h-[600px] items-center overflow-hidden bg-gray-950 bg-cover bg-center md:min-h-[650px]"
                    style="background-image: url('{{ $section->image_path ? asset('storage/' . $section->image_path) : asset('images/hero-masjidil-haram.png') }}');"
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

            @elseif ($section->section_type === 'statistics')

                @php
                    $statItems = data_get($section->content, 'items', []);
                    $previousSection = $loop->index > 0
                        ? $visibleSections->get($loop->index - 1)
                        : null;
                    $overlapHero = $previousSection
                        && $previousSection->section_type === 'hero';
                @endphp

                @if (is_array($statItems) && count($statItems))
                    <section
                        class="relative z-10 px-5 pb-12"
                        style="{{ $overlapHero ? 'margin-top: -64px;' : '' }}"
                    >
                        <div class="mx-auto grid max-w-6xl grid-cols-2 overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-xl md:grid-cols-4">
                            @foreach ($statItems as $item)
                                <div class="flex flex-col items-center justify-center px-3 py-8 text-center transition duration-300 hover:bg-amber-50/50 md:py-10 {{ $loop->last ? '' : 'md:border-r md:border-gray-100' }} {{ $loop->iteration <= 2 ? 'border-b border-gray-100 md:border-b-0' : '' }}">
                                    <h3 class="text-3xl font-black leading-none tracking-tight text-amber-500 md:text-4xl">
                                        {{ $item['value'] ?? '-' }}
                                    </h3>
                                    <p class="mt-3 text-xs font-semibold leading-relaxed text-gray-500 md:text-sm">
                                        {{ $item['label'] ?? 'Statistik' }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif

            @elseif (in_array($section->section_type, ['about', 'about_us']))

                <section class="bg-white px-5 py-20 md:py-24">
                    <div class="mx-auto grid max-w-7xl grid-cols-1 items-center gap-10 md:grid-cols-2">
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

                        <div class="max-w-2xl {{ !$section->image_path ? 'md:col-span-2 md:mx-auto md:text-center' : '' }}">
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
                    $reasonCards = data_get($reasonContent, 'cards', []);
                    if (!is_array($reasonCards)) {
                        $reasonCards = [];
                    }
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
                                    <article class="h-full rounded-2xl border border-gray-200 bg-white p-7 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-amber-300 hover:shadow-lg">
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
                                <p class="font-semibold text-gray-700">Belum ada data keunggulan.</p>
                                <p class="mt-2 text-sm text-gray-500">
                                    Silakan tambahkan card melalui pengelolaan section.
                                </p>
                            </div>
                        @endif
                    </div>
                </section>

            @elseif ($section->section_type === 'services')

                @php
                    $serviceCards = data_get($section->content, 'cards', []);
                @endphp

                <section class="bg-white px-5 py-20 md:py-24">
                    <div class="mx-auto max-w-7xl">
                        <div class="mx-auto mb-14 max-w-3xl text-center">
                            <span class="text-sm font-bold uppercase tracking-widest text-amber-600">
                                {{ $section->subtitle ?: 'Layanan Ansar Travel' }}
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

                        @if (is_array($serviceCards) && count($serviceCards))
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                                @foreach ($serviceCards as $card)
                                    <article class="h-full rounded-2xl border border-gray-200 bg-white p-7 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-amber-300 hover:shadow-lg">
                                        <h3 class="text-lg font-bold text-gray-900">
                                            {{ $card['title'] ?? '' }}
                                        </h3>
                                        <p class="mt-3 text-sm leading-7 text-gray-600">
                                            {{ $card['description'] ?? '' }}
                                        </p>
                                    </article>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </section>

            @elseif ($section->section_type === 'packages')

                <section class="bg-gray-50 px-5 py-20 md:py-24">
                    <div class="mx-auto max-w-7xl">
                        <div class="mb-12 text-center">
                            <span class="text-sm font-bold uppercase tracking-widest text-amber-600">
                                {{ $section->subtitle ?: 'PILIHAN PERJALANAN' }}
                            </span>

                            <h2 class="mt-4 text-3xl font-extrabold text-gray-900 md:text-4xl">
                                {{ $section->title ?: 'Paket Umroh Pilihan' }}
                            </h2>

                            @if (data_get($section->content, 'description'))
                                <p class="mx-auto mt-4 max-w-2xl leading-8 text-gray-600">
                                    {{ data_get($section->content, 'description') }}
                                </p>
                            @endif
                        </div>

                        @if ($packages->isNotEmpty())
                            <div class="grid grid-cols-1 gap-7 md:grid-cols-2 lg:grid-cols-3">
                                @foreach ($packages as $package)
                                    <div class="group overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl">
                                        <div class="relative h-56 overflow-hidden bg-gray-100">
                                            @if ($package->image_path)
                                                <img
                                                    src="{{ asset('storage/' . $package->image_path) }}"
                                                    alt="{{ $package->name }}"
                                                    class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                                                    loading="lazy"
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

                                        <div class="p-6">
                                            <h3 class="text-xl font-bold text-gray-900">
                                                {{ $package->name }}
                                            </h3>

                                            <p class="mt-3 line-clamp-3 text-sm leading-7 text-gray-600">
                                                {{ $package->description ?: 'Informasi perjalanan umroh bersama Ansar Travel.' }}
                                            </p>

                                            <div class="mt-5 space-y-3 border-t border-gray-100 pt-5">
                                                @if ($package->duration_days)
                                                    <div class="flex items-center gap-3 text-sm text-gray-600">
                                                        <span>◷</span>
                                                        {{ $package->duration_days }} Hari Perjalanan
                                                    </div>
                                                @endif

                                                @if ($package->airline)
                                                    <div class="flex items-center gap-3 text-sm text-gray-600">
                                                        <span>✈</span>
                                                        {{ $package->airline }}
                                                    </div>
                                                @endif

                                                @if ($package->hotel)
                                                    <div class="flex items-center gap-3 text-sm text-gray-600">
                                                        <span>⌂</span>
                                                        {{ $package->hotel }}
                                                    </div>
                                                @endif

                                                @if ($package->departure_date)
                                                    <div class="flex items-center gap-3 text-sm text-gray-600">
                                                        <span>▦</span>
                                                        {{ $package->departure_date->translatedFormat('d F Y') }}
                                                    </div>
                                                @endif
                                            </div>

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
                                                    href="{{ route('packages.show', $package->slug) }}"
                                                    class="mt-5 flex w-full items-center justify-center gap-2 rounded-xl bg-amber-400 px-5 py-3 text-sm font-bold text-gray-900 transition hover:bg-amber-500"
                                                >
                                                    Lihat Detail Paket <span>→</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-12 text-center">
                                <a
                                    href="{{ route('packages.index') }}"
                                    class="inline-block rounded-full border border-gray-300 bg-white px-7 py-3 text-sm font-bold text-gray-800 transition hover:border-amber-400 hover:bg-amber-50"
                                >
                                    Lihat Semua Paket Umroh
                                </a>
                            </div>
                        @else
                            <div class="rounded-2xl border border-gray-200 bg-white px-6 py-14 text-center">
                                <h3 class="text-lg font-bold text-gray-800">Belum Ada Paket Unggulan</h3>
                                <p class="mt-2 text-sm text-gray-500">
                                    Paket umroh unggulan akan ditampilkan di sini.
                                </p>
                            </div>
                        @endif
                    </div>
                </section>

            @elseif ($section->section_type === 'testimonials')

                @php
                    $testimonialItems = data_get($section->content, 'items', []);
                @endphp

                <section class="bg-white px-5 py-20 md:py-24">
                    <div class="mx-auto max-w-7xl">
                        <div class="mx-auto mb-14 max-w-3xl text-center">
                            <span class="text-sm font-bold uppercase tracking-widest text-amber-600">
                                {{ $section->subtitle ?: 'Pengalaman Jamaah' }}
                            </span>

                            <h2 class="mt-4 text-3xl font-extrabold leading-tight text-gray-900 md:text-4xl">
                                {{ $section->title ?: 'Testimoni Ansar Travel' }}
                            </h2>

                            @if (data_get($section->content, 'description'))
                                <p class="mx-auto mt-5 max-w-2xl leading-8 text-gray-600">
                                    {{ data_get($section->content, 'description') }}
                                </p>
                            @endif
                        </div>

                        @if (is_array($testimonialItems) && count($testimonialItems))
                            <div class="grid grid-cols-1 gap-7 md:grid-cols-2 lg:grid-cols-3">
                                @foreach ($testimonialItems as $testimonial)
                                    @php
                                        $rating = (int) ($testimonial['rating'] ?? 0);
                                        $name = $testimonial['name'] ?? 'Jamaah Ansar Travel';
                                    @endphp

                                    <article class="flex h-full flex-col rounded-2xl border border-gray-200 bg-white p-6 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-amber-300 hover:shadow-lg">
                                        <div class="flex items-center gap-4">
                                            @if (!empty($testimonial['photo_path']))
                                                <img
                                                    src="{{ asset('storage/' . $testimonial['photo_path']) }}"
                                                    alt="{{ $name }}"
                                                    class="h-14 w-14 shrink-0 rounded-full border border-gray-200 object-cover"
                                                    loading="lazy"
                                                >
                                            @else
                                                <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-amber-100 text-xl font-bold text-amber-700">
                                                    {{ strtoupper(substr($name, 0, 1)) }}
                                                </div>
                                            @endif

                                            <div class="min-w-0">
                                                <h3 class="truncate font-bold text-gray-900">
                                                    {{ $name }}
                                                </h3>

                                                @if (!empty($testimonial['origin']))
                                                    <p class="mt-1 text-sm text-gray-500">
                                                        {{ $testimonial['origin'] }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="mt-5 flex gap-1 text-xl" aria-label="Rating {{ $rating }} dari 5">
                                            @for ($star = 1; $star <= 5; $star++)
                                                <span class="{{ $star <= $rating ? 'text-amber-400' : 'text-gray-300' }}">★</span>
                                            @endfor
                                        </div>

                                        <blockquote class="mt-4 flex-1 text-sm leading-7 text-gray-600">
                                            <span class="mb-2 block text-3xl font-bold leading-none text-amber-400">&ldquo;</span>
                                            {{ $testimonial['message'] ?? '' }}
                                        </blockquote>
                                    </article>
                                @endforeach
                            </div>
                        @else
                            <div class="rounded-2xl border border-gray-200 bg-gray-50 px-6 py-12 text-center">
                                <p class="font-semibold text-gray-700">Belum ada testimoni jamaah.</p>
                                <p class="mt-2 text-sm text-gray-500">
                                    Testimoni jamaah akan ditampilkan di sini.
                                </p>
                            </div>
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

                <section class="relative isolate overflow-hidden bg-[#08111F] px-5 py-20 text-white md:py-28">
                    <div class="pointer-events-none absolute -right-32 -top-32 h-96 w-96 rounded-full bg-amber-400/10 blur-3xl"></div>
                    <div class="pointer-events-none absolute -bottom-40 -left-32 h-96 w-96 rounded-full bg-blue-500/10 blur-3xl"></div>

                    <div class="relative mx-auto grid max-w-7xl gap-12 lg:grid-cols-[0.9fr_1.1fr] lg:gap-16">
                        <div class="flex flex-col justify-center">
                            <div class="mb-5 inline-flex w-fit items-center gap-2 rounded-full border border-amber-300/30 bg-amber-300/10 px-4 py-2">
                                <span class="h-2 w-2 rounded-full bg-amber-400"></span>
                                <span class="text-[11px] font-bold uppercase tracking-[0.22em] text-amber-300">{{ $section->subtitle ?: 'Hubungi Ansar Travel' }}</span>
                            </div>

                            <h2 class="text-3xl font-extrabold leading-tight tracking-tight text-white md:text-4xl lg:text-5xl">
                                {{ $section->title ?: 'Kami Siap Membantu Perjalanan Ibadah Anda' }}
                            </h2>

                            <p class="mt-5 max-w-xl text-sm leading-8 text-slate-300 md:text-base">
                                {{ data_get($section->content, 'description') ?: 'Hubungi tim Ansar Travel untuk mendapatkan informasi paket, jadwal keberangkatan, dan layanan perjalanan umroh.' }}
                            </p>

                            @if ($whatsappDigits)
                                <a href="https://wa.me/{{ $whatsappDigits }}?text={{ $whatsappMessage }}" target="_blank" rel="noopener noreferrer" class="mt-8 inline-flex w-fit items-center gap-3 rounded-full bg-amber-400 px-7 py-4 text-sm font-extrabold text-slate-950 shadow-lg shadow-amber-400/10 transition hover:-translate-y-0.5 hover:bg-amber-300">
                                    <span class="text-lg">◉</span>
                                    Hubungi via WhatsApp
                                    <span aria-hidden="true">&#8599;</span>
                                </a>
                            @endif

                            <div class="mt-8 flex flex-wrap gap-3 text-xs font-semibold text-slate-300">
                                @if (!empty($contactData['instagram']))
                                    <a href="{{ $contactData['instagram'] }}" target="_blank" rel="noopener noreferrer" class="rounded-full border border-white/15 px-4 py-2 transition hover:border-amber-300 hover:text-amber-300">Instagram</a>
                                @endif
                                @if (!empty($contactData['facebook']))
                                    <a href="{{ $contactData['facebook'] }}" target="_blank" rel="noopener noreferrer" class="rounded-full border border-white/15 px-4 py-2 transition hover:border-amber-300 hover:text-amber-300">Facebook</a>
                                @endif
                                @if (!empty($contactData['website']))
                                    <a href="{{ $contactData['website'] }}" target="_blank" rel="noopener noreferrer" class="rounded-full border border-white/15 px-4 py-2 transition hover:border-amber-300 hover:text-amber-300">Website</a>
                                @endif
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            @if (!empty($contactData['whatsapp']))
                                <a href="https://wa.me/{{ $whatsappDigits }}?text={{ $whatsappMessage }}" target="_blank" rel="noopener noreferrer" class="group rounded-2xl border border-white/10 bg-white/[0.06] p-6 backdrop-blur transition hover:-translate-y-1 hover:border-amber-300/50 hover:bg-white/[0.09]">
                                    <span class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-400/10 text-2xl text-emerald-300">◉</span>
                                    <span class="block text-xs font-bold uppercase tracking-widest text-slate-400">WhatsApp</span>
                                    <span class="mt-2 block break-all text-lg font-bold text-white">{{ $contactData['whatsapp'] }}</span>
                                    <span class="mt-3 block text-xs text-amber-300">Kirim pesan &#8599;</span>
                                </a>
                            @endif

                            @if (!empty($contactData['phone']))
                                <a href="tel:{{ $phoneDigits }}" class="group rounded-2xl border border-white/10 bg-white/[0.06] p-6 backdrop-blur transition hover:-translate-y-1 hover:border-amber-300/50 hover:bg-white/[0.09]">
                                    <span class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-amber-400/10 text-2xl text-amber-300">&#9742;</span>
                                    <span class="block text-xs font-bold uppercase tracking-widest text-slate-400">Telepon</span>
                                    <span class="mt-2 block break-all text-lg font-bold text-white">{{ $contactData['phone'] }}</span>
                                    <span class="mt-3 block text-xs text-amber-300">Hubungi sekarang &#8599;</span>
                                </a>
                            @endif

                            @if (!empty($contactData['email']))
                                <a href="mailto:{{ $contactData['email'] }}" class="group rounded-2xl border border-white/10 bg-white/[0.06] p-6 backdrop-blur transition hover:-translate-y-1 hover:border-amber-300/50 hover:bg-white/[0.09]">
                                    <span class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-blue-400/10 text-2xl text-blue-300">&#9993;</span>
                                    <span class="block text-xs font-bold uppercase tracking-widest text-slate-400">Email</span>
                                    <span class="mt-2 block break-all text-lg font-bold text-white">{{ $contactData['email'] }}</span>
                                    <span class="mt-3 block text-xs text-amber-300">Kirim email &#8599;</span>
                                </a>
                            @endif

                            @if (!empty($contactData['office_hours']))
                                <div class="rounded-2xl border border-white/10 bg-white/[0.06] p-6 backdrop-blur">
                                    <span class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-purple-400/10 text-2xl text-purple-300">&#9719;</span>
                                    <span class="block text-xs font-bold uppercase tracking-widest text-slate-400">Jam Operasional</span>
                                    <span class="mt-2 block text-base font-bold leading-7 text-white">{{ $contactData['office_hours'] }}</span>
                                </div>
                            @endif

                            @if (!empty($contactData['address']))
                                <div class="rounded-2xl border border-white/10 bg-white/[0.06] p-6 backdrop-blur sm:col-span-2">
                                    <div class="flex items-start gap-4">
                                        <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-rose-400/10 text-2xl text-rose-300">&#9873;</span>
                                        <div class="min-w-0">
                                            <span class="block text-xs font-bold uppercase tracking-widest text-slate-400">Alamat Kantor</span>
                                            <p class="mt-2 text-sm leading-7 text-white">{{ $contactData['address'] }}</p>
                                            @if (!empty($contactData['maps_url']))
                                                <a href="{{ $contactData['maps_url'] }}" target="_blank" rel="noopener noreferrer" class="mt-3 inline-flex text-xs font-bold text-amber-300 transition hover:text-amber-200">Lihat lokasi di Google Maps &#8599;</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @elseif (!empty($contactData['maps_url']))
                                <a href="{{ $contactData['maps_url'] }}" target="_blank" rel="noopener noreferrer" class="rounded-2xl border border-white/10 bg-white/[0.06] p-6 text-sm font-bold text-amber-300 transition hover:border-amber-300/50 sm:col-span-2">Lihat lokasi Ansar Travel di Google Maps &#8599;</a>
                            @endif

                            @if (empty(array_filter($contactData)))
                                <div class="rounded-2xl border border-white/10 bg-white/[0.06] p-6 text-sm leading-7 text-slate-300 sm:col-span-2">
                                    Informasi kontak belum dilengkapi. Silakan lengkapi melalui menu Kelola Section.
                                </div>
                            @endif
                        </div>
                    </div>
                </section>


            @else

                <section class="bg-white px-5 py-20">
                    <div class="mx-auto max-w-7xl">
                        <div class="mx-auto max-w-3xl text-center">
                            <span class="text-sm font-bold uppercase tracking-widest text-amber-600">
                                {{ $section->subtitle ?: ucfirst(str_replace('_', ' ', $section->section_type)) }}
                            </span>

                            <h2 class="mt-3 text-3xl font-extrabold text-gray-900">
                                {{ $section->title }}
                            </h2>

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

    <footer class="bg-gray-950 text-white">
        <div class="mx-auto grid max-w-7xl gap-12 px-5 py-16 md:grid-cols-3 lg:px-8">

            <div>
                <div class="flex items-center gap-3">
                    <img
                        src="{{ asset('images/ansar-travel-logo.png') }}"
                        alt="Logo Ansar Travel"
                        class="h-12 w-12 object-contain brightness-0 invert"
                    >
                    <h3 class="text-xl font-extrabold">ANSHAR TRAVEL</h3>
                </div>

                <p class="mt-5 max-w-sm text-sm leading-7 text-gray-400">
                    Mitra perjalanan ibadah Anda. Temukan informasi layanan
                    dan perjalanan bersama Ansar Travel.
                </p>
            </div>

            <div>
                <h3 class="text-lg font-bold">Navigasi</h3>

                <div class="mt-5 grid grid-cols-2 gap-4 text-sm text-gray-400">
                    @foreach ($navbarPages as $navPage)
                        @if ($navPage->slug === 'layanan' && $hasPackagesPage)
                            @continue
                        @endif

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

                        <a href="{{ $footerUrl }}" class="transition hover:text-amber-400">
                            {{ $footerTitle }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div>
                <h3 class="text-lg font-bold">Hubungi Kami</h3>

                <p class="mt-5 text-sm leading-7 text-gray-400">
                    Untuk informasi lebih lanjut mengenai layanan dan
                    perjalanan ibadah, silakan hubungi tim Ansar Travel.
                </p>

                <a
                    href="/kontak"
                    class="mt-5 inline-block rounded-full bg-amber-400 px-6 py-3 text-sm font-bold text-gray-900 transition hover:bg-amber-500"
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
