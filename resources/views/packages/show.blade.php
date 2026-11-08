
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $package->name }} - Ansar Travel</title>

    <meta name="description" content="{{ $package->description }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 text-gray-800 antialiased">

    
    {{-- NAVBAR --}}
    <header class="sticky top-0 z-50 border-b border-gray-100 bg-white">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-5 py-4 lg:px-8">

            {{-- LOGO --}}
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

            {{-- NAVIGASI DESKTOP --}}
            <nav class="hidden items-center gap-8 text-sm font-semibold text-gray-700 lg:flex">
                <a href="/" class="hover:text-amber-600">Beranda</a>
                <a href="{{ route('packages.index') }}" class="font-bold text-amber-600">Paket Umroh</a>
                <a href="/jadwal" class="hover:text-amber-600">Jadwal</a>
                <a href="/tentang-kami" class="hover:text-amber-600">Tentang Kami</a>
                <a href="/info" class="hover:text-amber-600">Info</a>
                <a href="/kontak" class="hover:text-amber-600">Kontak</a>
            </nav>

            {{-- KONSULTASI DESKTOP --}}
            <a
                href="/kontak"
                class="hidden rounded-full bg-amber-400 px-6 py-3 text-sm font-bold text-gray-900 hover:bg-amber-500 lg:inline-block"
            >
                Konsultasi
            </a>

            {{-- TOMBOL HAMBURGER MOBILE --}}
            <button
                id="menu-button"
                type="button"
                class="rounded-lg border border-gray-200 p-2 text-gray-700 lg:hidden"
                aria-label="Buka menu navigasi"
                aria-expanded="false"
                aria-controls="mobile-menu"
            >
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16"
                    />
                </svg>
            </button>

        </div>

        {{-- NAVIGASI MOBILE --}}
        <nav
            id="mobile-menu"
            class="hidden border-t border-gray-100 bg-white px-5 py-4 lg:hidden"
        >
            <div class="flex flex-col gap-4 text-sm font-semibold text-gray-700">
                <a href="/">Beranda</a>
                <a href="{{ route('packages.index') }}" class="font-bold text-amber-600">Paket Umroh</a>
                <a href="/jadwal">Jadwal</a>
                <a href="/tentang-kami">Tentang Kami</a>
                <a href="/info">Info</a>
                <a href="/kontak">Kontak</a>

                <a
                    href="/kontak"
                    class="mt-2 rounded-lg bg-amber-400 px-4 py-3 text-center font-bold text-gray-900"
                >
                    Konsultasi
                </a>
            </div>
        </nav>
    </header>

    <main>

        {{-- HERO --}}
        <section class="bg-[#FDD52E] px-5 py-14 md:py-20">
            <div class="mx-auto max-w-7xl">

                <a href="{{ route('packages.index') }}"
                   class="inline-flex items-center gap-2 text-sm font-semibold text-gray-800 hover:text-gray-600">
                    <span>&larr;</span>
                    Kembali ke Paket Umroh
                </a>

                <div class="mt-8">
                    <span class="rounded-full bg-white/70 px-4 py-2 text-xs font-bold uppercase tracking-wider text-gray-800">
                        ANSAR TRAVEL
                    </span>

                    <h1 class="mt-5 text-3xl font-extrabold leading-tight text-gray-900 md:text-5xl">
                        {{ $package->name }}
                    </h1>

                    <div class="mt-5 flex flex-wrap gap-3 text-sm font-semibold text-gray-800">
                        @if ($package->duration_days)
                            <span class="rounded-full bg-white/70 px-4 py-2">
                                {{ $package->duration_days }} Hari Perjalanan
                            </span>
                        @endif

                        @if ($package->airline)
                            <span class="rounded-full bg-white/70 px-4 py-2">
                                {{ $package->airline }}
                            </span>
                        @endif
                    </div>
                </div>

            </div>
        </section>

        {{-- DETAIL PAKET --}}
        <section class="px-5 py-14 md:py-20">
            <div class="mx-auto max-w-7xl">

                <div class="grid grid-cols-1 items-start gap-8 lg:grid-cols-3">

                    {{-- FOTO PAKET --}}
                    <div class="lg:col-span-2">
                        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

                            @if ($package->image_path)
                                <img
                                    src="{{ asset('storage/' . $package->image_path) }}"
                                    alt="{{ $package->name }}"
                                    class="max-h-[550px] w-full object-cover"
                                >
                            @else
                                <div class="flex h-80 items-center justify-center bg-gray-100 text-gray-400">
                                    Foto paket belum tersedia
                                </div>
                            @endif

                        </div>
                        
                        {{-- GALERI FOTO PAKET --}}
                        @if ($package->images->isNotEmpty())
                            <div class="mt-8 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm md:p-8">

                                <div class="mb-6">
                                    <h2 class="text-xl font-extrabold text-gray-900">
                                        Galeri Foto Paket
                                    </h2>

                                    <p class="mt-2 text-sm text-gray-500">
                                        Lihat dokumentasi dan fasilitas perjalanan bersama Ansar Travel.
                                    </p>
                                </div>

                                <div class="grid grid-cols-2 gap-4 sm:grid-cols-3">

                                    @foreach ($package->images as $galleryImage)
                                        <button
                                            type="button"
                                            class="gallery-trigger group relative block w-full overflow-hidden rounded-xl bg-gray-100"
                                            data-image="{{ asset('storage/' . $galleryImage->image_path) }}"
                                            aria-label="Perbesar foto galeri"
                                        >
                                            <img
                                                src="{{ asset('storage/' . $galleryImage->image_path) }}"
                                                alt="Galeri {{ $package->name }}"
                                                loading="lazy"
                                                class="h-36 w-full object-cover transition duration-300 group-hover:scale-105 sm:h-48"
                                            >

                                            <div class="absolute inset-0 flex items-center justify-center bg-black/0 transition group-hover:bg-black/20">
                                                <span class="rounded-full bg-white/90 px-3 py-2 text-xs font-semibold text-gray-800 opacity-0 transition group-hover:opacity-100">
                                                    Lihat Foto
                                                </span>
                                            </div>
                                        </button>
                                    @endforeach

                                </div>

                            </div>
                        @endif

                        {{-- LIGHTBOX FOTO --}}
                        <div
                            id="gallery-lightbox"
                            class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/85 p-4"
                            role="dialog"
                            aria-modal="true"
                            aria-label="Pratinjau foto galeri"
                        >
                            {{-- TOMBOL TUTUP --}}
                            <button
                                type="button"
                                id="close-lightbox"
                                class="absolute right-5 top-5 flex h-11 w-11 items-center justify-center rounded-full bg-white/15 text-3xl text-white transition hover:bg-white/30"
                                aria-label="Tutup foto"
                            >
                                &times;
                            </button>

                            {{-- FOTO BESAR --}}
                            <img
                                id="lightbox-image"
                                src=""
                                alt="Foto galeri paket"
                                class="max-h-[85vh] max-w-full rounded-lg object-contain shadow-2xl"
                            >
                        </div>



                        {{-- DESKRIPSI --}}
                        <div class="mt-8 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm md:p-8">

                            <h2 class="text-xl font-extrabold text-gray-900">
                                Deskripsi Paket
                            </h2>

                            <div class="mt-4 text-sm leading-8 text-gray-600">
                                {{ $package->description ?: 'Informasi perjalanan umroh bersama Ansar Travel.' }}
                            </div>

                        </div>

                        {{-- FASILITAS --}}
                        <div class="mt-8 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm md:p-8">

                            <h2 class="text-xl font-extrabold text-gray-900">
                                Fasilitas Paket
                            </h2>

                            @if (!empty($package->facilities))
                                <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2">

                                    @foreach ($package->facilities as $facility)
                                        @if (filled($facility))
                                            <div class="flex items-start gap-3 text-sm text-gray-700">
                                                <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-amber-100 font-bold text-amber-600">
                                                    ✓
                                                </span>

                                                <span class="leading-6">
                                                    {{ $facility }}
                                                </span>
                                            </div>
                                        @endif
                                    @endforeach

                                </div>
                            @else
                                <p class="mt-4 text-sm text-gray-500">
                                    Informasi fasilitas belum tersedia.
                                </p>
                            @endif

                        </div>

                        {{-- HOTEL DAN PENERBANGAN --}}
                        <div class="mt-8 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm md:p-8">

                            <h2 class="text-xl font-extrabold text-gray-900">
                                Informasi Akomodasi dan Penerbangan
                            </h2>

                            <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2">

                                <div class="rounded-xl bg-gray-50 p-5">
                                    <p class="text-xs font-bold uppercase tracking-wider text-amber-600">
                                        Hotel Makkah
                                    </p>

                                    <p class="mt-2 font-semibold text-gray-800">
                                        {{ $package->hotel_makkah ?: 'Belum diinformasikan' }}
                                    </p>
                                </div>

                                <div class="rounded-xl bg-gray-50 p-5">
                                    <p class="text-xs font-bold uppercase tracking-wider text-amber-600">
                                        Hotel Madinah
                                    </p>

                                    <p class="mt-2 font-semibold text-gray-800">
                                        {{ $package->hotel_madinah ?: 'Belum diinformasikan' }}
                                    </p>
                                </div>

                            </div>

                            <div class="mt-5 border-t border-gray-100 pt-5">

                                <p class="text-xs font-bold uppercase tracking-wider text-amber-600">
                                    Maskapai Penerbangan
                                </p>

                                <p class="mt-2 font-semibold text-gray-800">
                                    {{ $package->airline ?: 'Belum diinformasikan' }}
                                </p>

                                <p class="mt-4 text-xs font-bold uppercase tracking-wider text-amber-600">
                                    Keterangan Penerbangan
                                </p>

                                <p class="mt-2 text-sm leading-7 text-gray-600">
                                    {{ $package->flight_note ?: 'Informasi penerbangan belum tersedia.' }}
                                </p>

                            </div>

                        </div>

                    </div>

                    {{-- SIDEBAR INFORMASI HARGA --}}
                    <aside class="lg:sticky lg:top-28">

                        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

                            <div class="bg-[#FDD52E] p-6">
                                <p class="text-sm font-semibold text-gray-800">
                                    Harga Paket Umroh
                                </p>

                                @if ($package->price !== null)
                                    <h2 class="mt-2 text-3xl font-extrabold text-gray-900">
                                        Rp{{ number_format((float) $package->price, 0, ',', '.') }}
                                    </h2>
                                @else
                                    <h2 class="mt-2 text-2xl font-extrabold text-gray-900">
                                        Hubungi Kami
                                    </h2>
                                @endif
                            </div>

                            <div class="space-y-5 p-6">

                                <div>
                                    <p class="text-xs text-gray-500">
                                        Durasi Perjalanan
                                    </p>
                                    <p class="mt-1 font-bold text-gray-800">
                                        {{ $package->duration_days ? $package->duration_days . ' Hari' : 'Belum ditentukan' }}
                                    </p>
                                </div>

                                <div class="border-t border-gray-100"></div>

                                <div>
                                    <p class="text-xs text-gray-500">
                                        Tanggal Keberangkatan
                                    </p>
                                    <p class="mt-1 font-bold text-gray-800">
                                        {{ $package->departure_date ? $package->departure_date->translatedFormat('d F Y') : 'Hubungi Kami' }}
                                    </p>
                                </div>

                                <div class="border-t border-gray-100"></div>

                                <div>
                                    <p class="text-xs text-gray-500">
                                        Maskapai
                                    </p>
                                    <p class="mt-1 font-bold text-gray-800">
                                        {{ $package->airline ?: 'Belum diinformasikan' }}
                                    </p>
                                </div>

                                 @php
                                    $whatsappMessage = "Assalamu'alaikum, saya ingin bertanya mengenai paket umroh berikut:\n\n"
                                        . "Nama Paket: {$package->name}\n"
                                        . "Durasi: " . ($package->duration_days ? $package->duration_days . ' Hari' : 'Belum ditentukan') . "\n"
                                        . "Harga: " . ($package->price !== null ? 'Rp' . number_format((float) $package->price, 0, ',', '.') : 'Hubungi Kami') . "\n\n"
                                        . "Mohon informasi lebih lanjut mengenai paket tersebut. Terima kasih.";

                                    $whatsappUrl = 'https://wa.me/6289519773633?text=' . urlencode($whatsappMessage);
                                @endphp

                                <a
                                    href="{{ $whatsappUrl }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="flex w-full items-center justify-center gap-2 rounded-xl bg-[#FDD52E] px-5 py-3 text-sm font-bold text-gray-900 transition hover:bg-[#eac323]"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        width="19"
                                        height="19"
                                        viewBox="0 0 24 24"
                                        fill="currentColor">
                                        <path d="M20.52 3.48A11.86 11.86 0 0 0 12.06 0C5.5 0 .16 5.34.16 11.9c0 2.1.55 4.15 1.6 5.96L0 24l6.3-1.65a11.9 11.9 0 0 0 5.76 1.47h.01c6.56 0 11.9-5.34 11.9-11.9a11.84 11.84 0 0 0-3.45-8.44ZM12.07 21.8a9.9 9.9 0 0 1-5.05-1.39l-.36-.21-3.74.98 1-3.65-.24-.37a9.86 9.86 0 0 1-1.52-5.26c0-5.46 4.45-9.9 9.91-9.9a9.83 9.83 0 0 1 7.01 2.9 9.83 9.83 0 0 1 2.9 7.01c0 5.46-4.45 9.89-9.91 9.89Zm5.43-7.41c-.3-.15-1.77-.87-2.05-.97-.28-.1-.48-.15-.68.15-.2.3-.78.97-.95 1.17-.17.2-.35.22-.65.07-.3-.15-1.26-.46-2.4-1.47-.89-.79-1.49-1.77-1.67-2.07-.17-.3-.02-.46.13-.61.13-.13.3-.35.45-.52.15-.18.2-.3.3-.5.1-.2.05-.38-.02-.53-.08-.15-.68-1.63-.93-2.23-.24-.58-.49-.5-.68-.51h-.58c-.2 0-.53.08-.8.38-.28.3-1.05 1.02-1.05 2.49s1.08 2.89 1.23 3.09c.15.2 2.12 3.24 5.14 4.54.72.31 1.28.5 1.72.64.72.23 1.38.2 1.9.12.58-.09 1.77-.72 2.02-1.42.25-.7.25-1.3.18-1.42-.08-.13-.28-.2-.58-.35Z"/>
                                    </svg>

                                    Tanya via WhatsApp
                                </a>

                                <p class="text-center text-xs leading-6 text-gray-500">
                                    Hubungi tim Ansar Travel untuk mendapatkan informasi lebih lanjut mengenai paket ini.
                                </p>

                            </div>

                        </div>

                    </aside>

                </div>

            </div>
        </section>

    </main>

    {{-- FOOTER --}}
    <footer class="bg-gray-950 text-white">
        <div class="mx-auto grid max-w-7xl gap-10 px-5 py-14 md:grid-cols-3 lg:px-8">

            <div>
                <h3 class="text-xl font-extrabold">ANSHAR TRAVEL</h3>
                <p class="mt-4 text-sm leading-7 text-gray-400">
                    Mitra perjalanan ibadah Anda. Temukan informasi paket perjalanan
                    bersama Ansar Travel.
                </p>
            </div>

            <div>
                <h3 class="font-bold">Navigasi</h3>
                <div class="mt-4 grid grid-cols-2 gap-4 text-sm text-gray-400">
                    <a href="/" class="hover:text-amber-400">Beranda</a>
                    <a href="{{ route('packages.index') }}" class="hover:text-amber-400">Paket Umroh</a>
                    <a href="/jadwal" class="hover:text-amber-400">Jadwal</a>
                    <a href="/tentang-kami" class="hover:text-amber-400">Tentang Kami</a>
                    <a href="/info" class="hover:text-amber-400">Info</a>
                    <a href="/kontak" class="hover:text-amber-400">Kontak</a>
                </div>
            </div>

            <div>
                <h3 class="font-bold">Hubungi Kami</h3>
                <p class="mt-4 text-sm leading-7 text-gray-400">
                    Untuk informasi lebih lanjut mengenai paket perjalanan ibadah,
                    silakan hubungi tim Ansar Travel.
                </p>

                <a href="/kontak"
                   class="mt-5 inline-block rounded-full bg-amber-400 px-6 py-3 text-sm font-bold text-gray-900 hover:bg-amber-500">
                    Hubungi Kami
                </a>
            </div>

        </div>

        <div class="border-t border-white/10 px-5 py-6 text-center text-sm text-gray-500">
            &copy; {{ date('Y') }} Ansar Travel. Seluruh hak cipta dilindungi.
        </div>
    </footer>

    <script>
        const menuButton = document.getElementById('menu-button');
        const mobileMenu = document.getElementById('mobile-menu');

        menuButton?.addEventListener('click', function () {
            mobileMenu.classList.toggle('hidden');

            const isExpanded = !mobileMenu.classList.contains('hidden');
            menuButton.setAttribute('aria-expanded', isExpanded);
        });
    </script>
    
    <script>
        const galleryTriggers = document.querySelectorAll('.gallery-trigger');
        const lightbox = document.getElementById('gallery-lightbox');
        const lightboxImage = document.getElementById('lightbox-image');
        const closeLightbox = document.getElementById('close-lightbox');

        function openLightbox(imageUrl) {
            lightboxImage.src = imageUrl;

            lightbox.classList.remove('hidden');
            lightbox.classList.add('flex');

            document.body.style.overflow = 'hidden';

            closeLightbox.focus();
        }

        function hideLightbox() {
            lightbox.classList.add('hidden');
            lightbox.classList.remove('flex');

            lightboxImage.src = '';

            document.body.style.overflow = '';
        }

        galleryTriggers.forEach((button) => {
            button.addEventListener('click', function () {
                openLightbox(this.dataset.image);
            });
        });

        closeLightbox.addEventListener('click', hideLightbox);

        lightbox.addEventListener('click', function (event) {
            if (event.target === lightbox) {
                hideLightbox();
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && !lightbox.classList.contains('hidden')) {
                hideLightbox();
            }
        });
    </script>
</body>
</html>
