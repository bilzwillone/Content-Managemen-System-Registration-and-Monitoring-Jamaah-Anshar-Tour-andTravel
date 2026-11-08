
{{-- NAVBAR PUBLIK ANSAR TRAVEL --}}

@php
    $hasPackagesPage = $navbarPages->contains('slug', 'paket-umroh');
@endphp

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

        {{-- NAVBAR DESKTOP --}}
        <nav class="hidden items-center gap-8 text-sm font-semibold text-gray-700 lg:flex">

            @foreach ($navbarPages as $navPage)

                @if ($navPage->slug === 'layanan' && $hasPackagesPage)
                    @continue
                @endif

                @php
                    $menuTitle = $navPage->slug === 'layanan'
                        ? 'Paket Umroh'
                        : $navPage->title;

                    if ($navPage->slug === 'beranda') {
                        $menuUrl = url('/');
                        $isActive = request()->is('/');
                    } elseif (in_array($navPage->slug, ['layanan', 'paket-umroh'])) {
                        $menuUrl = route('packages.index');
                        $isActive = request()->is('paket-umroh') ||
                                    request()->is('paket-umroh/*');
                    } else {
                        $menuUrl = url('/' . $navPage->slug);
                        $isActive = request()->is($navPage->slug) ||
                                    request()->is($navPage->slug . '/*');
                    }
                @endphp

                <a
                    href="{{ $menuUrl }}"
                    class="{{ $isActive ? 'font-bold text-amber-600' : 'transition hover:text-amber-600' }}"
                >
                    {{ $menuTitle }}
                </a>

            @endforeach

        </nav>

        {{-- TOMBOL KONSULTASI DESKTOP --}}
        <a
            href="/kontak"
            class="hidden rounded-full bg-amber-400 px-6 py-3 text-sm font-bold text-gray-900 transition hover:bg-amber-500 lg:inline-block"
        >
            Konsultasi
        </a>

        {{-- HAMBURGER MOBILE --}}
        <button
            id="menu-button"
            type="button"
            class="rounded-lg border border-gray-200 p-2 text-gray-700 lg:hidden"
            aria-label="Buka menu navigasi"
            aria-expanded="false"
            aria-controls="mobile-menu"
        >
            <svg
                class="h-6 w-6"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16"
                />
            </svg>
        </button>

    </div>

    {{-- NAVBAR MOBILE --}}
    <nav
        id="mobile-menu"
        class="hidden border-t border-gray-100 bg-white px-5 py-4 lg:hidden"
    >
        <div class="flex flex-col gap-4 text-sm font-semibold text-gray-700">

            @foreach ($navbarPages as $navPage)

                @if ($navPage->slug === 'layanan' && $hasPackagesPage)
                    @continue
                @endif

                @php
                    $menuTitle = $navPage->slug === 'layanan'
                        ? 'Paket Umroh'
                        : $navPage->title;

                    if ($navPage->slug === 'beranda') {
                        $menuUrl = url('/');
                        $isActive = request()->is('/');
                    } elseif (in_array($navPage->slug, ['layanan', 'paket-umroh'])) {
                        $menuUrl = route('packages.index');
                        $isActive = request()->is('paket-umroh') ||
                                    request()->is('paket-umroh/*');
                    } else {
                        $menuUrl = url('/' . $navPage->slug);
                        $isActive = request()->is($navPage->slug) ||
                                    request()->is($navPage->slug . '/*');
                    }
                @endphp

                <a
                    href="{{ $menuUrl }}"
                    class="{{ $isActive ? 'font-bold text-amber-600' : 'transition hover:text-amber-600' }}"
                >
                    {{ $menuTitle }}
                </a>

            @endforeach

            <a
                href="/kontak"
                class="mt-2 rounded-lg bg-amber-400 px-4 py-3 text-center font-bold text-gray-900 hover:bg-amber-500"
            >
                Konsultasi
            </a>

        </div>
    </nav>

</header>

{{-- JAVASCRIPT NAVBAR --}}
<script>
    (() => {
        const menuButton = document.getElementById('menu-button');
        const mobileMenu = document.getElementById('mobile-menu');

        if (!menuButton || !mobileMenu) {
            return;
        }

        menuButton.addEventListener('click', function () {
            const isHidden = mobileMenu.classList.toggle('hidden');

            menuButton.setAttribute(
                'aria-expanded',
                String(!isHidden)
            );
        });

        mobileMenu.querySelectorAll('a').forEach((link) => {
            link.addEventListener('click', function () {
                mobileMenu.classList.add('hidden');
                menuButton.setAttribute('aria-expanded', 'false');
            });
        });
    })();
</script>
