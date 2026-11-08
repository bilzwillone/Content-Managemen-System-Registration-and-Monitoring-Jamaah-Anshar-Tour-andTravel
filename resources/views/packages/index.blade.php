<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Paket Umroh - Ansar Travel</title>

    <meta name="description" content="Temukan berbagai pilihan paket perjalanan umroh bersama Ansar Travel.">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-white text-gray-800 antialiased">

    {{-- NAVBAR --}}
    @include('partials.public-navbar')

    <main>

        {{-- HERO HALAMAN PAKET --}}
        <section class="bg-[#FDD52E] px-5 py-20 text-center md:py-28">
            <div class="mx-auto max-w-4xl">

                <span class="text-sm font-bold uppercase tracking-widest text-gray-800">
                    ANSAR TRAVEL
                </span>

                <h1 class="mt-5 text-3xl font-extrabold leading-tight text-gray-900 md:text-5xl">
                    Pilihan Paket Perjalanan Umroh
                </h1>

                <p class="mx-auto mt-6 max-w-2xl text-base leading-8 text-gray-800">
                    Temukan paket perjalanan ibadah umroh bersama Ansar Travel
                    dengan pilihan fasilitas, harga, dan jadwal keberangkatan
                    yang sesuai dengan kebutuhan Anda.
                </p>

            </div>
        </section>

        {{-- DAFTAR PAKET --}}
        <section class="bg-gray-50 px-5 py-16 md:py-20">
            <div class="mx-auto max-w-7xl">

                {{-- JUDUL --}}
                <div class="mb-10">
                    <span class="text-sm font-bold uppercase tracking-widest text-amber-600">
                        DAFTAR PERJALANAN
                    </span>

                    <h2 class="mt-3 text-2xl font-extrabold text-gray-900 md:text-3xl">
                        Paket Umroh Tersedia
                    </h2>

                    <p class="mt-3 text-sm leading-7 text-gray-600">
                        Berikut pilihan paket umroh yang tersedia di Ansar Travel.
                    </p>
                </div>

                @if ($packages->isNotEmpty())

                    {{-- GRID PAKET --}}
                    <div class="grid grid-cols-1 gap-7 md:grid-cols-2 lg:grid-cols-3">

                        @foreach ($packages as $package)

                            <article class="group overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl">

                                {{-- FOTO --}}
                                <div class="relative h-56 overflow-hidden bg-gray-100">

                                    @if ($package->image_path)
                                        <img
                                            src="{{ asset('storage/' . $package->image_path) }}"
                                            alt="{{ $package->name }}"
                                            class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                                        >
                                    @else
                                        <div class="flex h-full items-center justify-center text-sm text-gray-400">
                                            Foto paket belum tersedia
                                        </div>
                                    @endif

                                    @if ($package->is_featured)
                                        <span class="absolute left-4 top-4 rounded-full bg-amber-400 px-4 py-2 text-xs font-bold text-gray-900 shadow">
                                            Paket Unggulan
                                        </span>
                                    @endif

                                </div>

                                {{-- INFORMASI --}}
                                <div class="p-6">

                                    <h3 class="text-xl font-bold text-gray-900">
                                        {{ $package->name }}
                                    </h3>

                                    <p class="mt-3 line-clamp-3 min-h-[72px] text-sm leading-7 text-gray-600">
                                        {{ $package->description ?: 'Informasi perjalanan umroh bersama Ansar Travel.' }}
                                    </p>

                                    {{-- DETAIL --}}
                                    <div class="mt-5 space-y-3 border-t border-gray-100 pt-5">

                                        @if ($package->duration_days)
                                            <div class="flex items-center gap-3 text-sm text-gray-600">
                                                <span class="font-semibold text-amber-600">Durasi</span>
                                                {{ $package->duration_days }} Hari
                                            </div>
                                        @endif

                                        @if ($package->airline)
                                            <div class="flex items-center gap-3 text-sm text-gray-600">
                                                <span class="font-semibold text-amber-600">Maskapai</span>
                                                {{ $package->airline }}
                                            </div>
                                        @endif

                                        @if ($package->hotel)
                                            <div class="flex items-start gap-3 text-sm text-gray-600">
                                                <span class="shrink-0 font-semibold text-amber-600">Hotel</span>
                                                <span>{{ $package->hotel }}</span>
                                            </div>
                                        @endif

                                        @if ($package->departure_date)
                                            <div class="flex items-center gap-3 text-sm text-gray-600">
                                                <span class="shrink-0 font-semibold text-amber-600">Berangkat</span>
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
                                            href="{{ route('packages.show', $package->slug) }}"
                                            class="mt-5 flex w-full items-center justify-center gap-2 rounded-xl bg-amber-400 px-5 py-3 text-sm font-bold text-gray-900 transition hover:bg-amber-500"
                                        >
                                            Lihat Detail Paket

                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                width="17" height="17"
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="2"
                                                stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path d="M5 12h14M12 5l7 7-7 7"/>
                                            </svg>
                                        </a>

                                    </div>

                                </div>

                            </article>

                        @endforeach

                    </div>

                    {{-- PAGINATION --}}
                    <div class="mt-12">
                        {{ $packages->links() }}
                    </div>

                @else

                    {{-- JIKA BELUM ADA PAKET --}}
                    <div class="rounded-2xl border border-gray-200 bg-white px-6 py-20 text-center">

                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-amber-50 text-amber-500">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 width="30" height="30"
                                 viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor"
                                 stroke-width="1.6"
                                 stroke-linecap="round"
                                 stroke-linejoin="round">
                                <path d="M16 10a4 4 0 0 1-8 0"/>
                                <path d="M3 7h18l-1 13H4L3 7Z"/>
                                <path d="M8 7a4 4 0 0 1 8 0"/>
                            </svg>
                        </div>

                        <h3 class="mt-5 text-xl font-bold text-gray-900">
                            Belum Ada Paket Umroh
                        </h3>

                        <p class="mx-auto mt-2 max-w-md text-sm leading-7 text-gray-500">
                            Saat ini belum ada paket perjalanan yang tersedia.
                            Silakan hubungi tim Ansar Travel untuk informasi lebih lanjut.
                        </p>

                        <a
                            href="/kontak"
                            class="mt-6 inline-block rounded-full bg-amber-400 px-7 py-3 text-sm font-bold text-gray-900 transition hover:bg-amber-500"
                        >
                            Hubungi Kami
                        </a>

                    </div>

                @endif

            </div>
        </section>

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
                    Mitra perjalanan ibadah Anda. Temukan informasi paket perjalanan
                    bersama Ansar Travel.
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
                    Untuk informasi lebih lanjut mengenai paket perjalanan ibadah,
                    silakan hubungi tim Ansar Travel.
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
