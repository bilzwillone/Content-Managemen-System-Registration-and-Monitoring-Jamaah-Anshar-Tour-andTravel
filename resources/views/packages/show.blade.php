
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
                <a href="/" class="hover:text-amber-600">Beranda</a>
                <a href="{{ route('packages.index') }}" class="font-bold text-amber-600">Paket Umroh</a>
                <a href="/jadwal" class="hover:text-amber-600">Jadwal</a>
                <a href="/tentang-kami" class="hover:text-amber-600">Tentang Kami</a>
                <a href="/info" class="hover:text-amber-600">Info</a>
                <a href="/kontak" class="hover:text-amber-600">Kontak</a>
            </nav>

            <a href="/kontak"
               class="hidden rounded-full bg-amber-400 px-6 py-3 text-sm font-bold text-gray-900 hover:bg-amber-500 lg:inline-block">
                Konsultasi
            </a>

        </div>
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

                                <a href="/kontak"
                                   class="flex w-full items-center justify-center rounded-xl bg-[#FDD52E] px-5 py-3 text-sm font-bold text-gray-900 transition hover:bg-[#eac323]">
                                    Tanya dan Konsultasi
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

</body>
</html>
