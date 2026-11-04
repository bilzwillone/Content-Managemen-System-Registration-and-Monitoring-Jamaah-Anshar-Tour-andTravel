
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Dashboard Admin') - Ansar Travel</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-white text-gray-800">

    <div class="flex min-h-screen">

        {{-- SIDEBAR --}}
        <aside class="sticky top-0 self-start flex h-screen w-64 shrink-0 flex-col overflow-y-auto bg-[#FDD52E] text-white">

            {{-- Logo dan Nama Sistem --}}
            <div class="border-b border-black/10 px-4 py-5">

                <div class="flex items-center gap-3">

                    <img
                        src="{{ asset('images/ansar-travel-logo.png') }}"
                        alt="Logo Ansar Travel"
                        class="h-14 w-14 shrink-0 object-contain"
                    >

                    <div class="min-w-0">
                        <h1 class="text-base font-bold leading-tight text-white">
                            ANSHAR TRAVEL
                        </h1>

                        <p class="mt-1 text-xs font-medium text-white">
                            Management System
                        </p>
                    </div>

                </div>

            </div>

            {{-- Menu Navigasi --}}
            <nav class="flex-1 space-y-1 px-3 py-6">

                <p class="mb-3 px-3 text-xs font-bold uppercase tracking-wider text-white/80">
                    Menu Utama
                </p>

                {{-- Dashboard --}}
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-semibold text-white transition duration-200
                   {{ request()->routeIs('admin.dashboard') ? 'bg-[#C9A713] shadow-sm' : 'hover:bg-[#D7B51D]' }}">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         width="20" height="20" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor"
                         stroke-width="1.8" stroke-linecap="round"
                         stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="9" rx="1"/>
                        <rect x="14" y="3" width="7" height="5" rx="1"/>
                        <rect x="14" y="12" width="7" height="9" rx="1"/>
                        <rect x="3" y="16" width="7" height="5" rx="1"/>
                    </svg>

                    Dashboard
                </a>

                <p class="mb-3 mt-7 px-3 text-xs font-bold uppercase tracking-wider text-white/80">
                    Manajemen Website
                </p>

                {{-- Kelola Halaman --}}
                <a href="{{ route('admin.pages.index') }}"
                   class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium text-white transition duration-200
                   {{ request()->routeIs('admin.pages.*') ? 'bg-[#C9A713] shadow-sm' : 'hover:bg-[#D7B51D]' }}">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         width="20" height="20" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor"
                         stroke-width="1.8" stroke-linecap="round"
                         stroke-linejoin="round">
                        <rect x="3" y="3" width="18" height="18" rx="2"/>
                        <path d="M3 9h18M9 21V9"/>
                    </svg>

                    Kelola Halaman
                </a>

                {{-- Paket Umroh --}}
                <a href="{{ route('admin.packages.index') }}"
                class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium text-white transition duration-200
                {{ request()->routeIs('admin.packages.*') ? 'bg-[#C9A713] shadow-sm' : 'hover:bg-[#D7B51D]' }}">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         width="20" height="20" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor"
                         stroke-width="1.8" stroke-linecap="round"
                         stroke-linejoin="round">
                        <path d="M16 10a4 4 0 0 1-8 0"/>
                        <path d="M3 7h18l-1 13H4L3 7Z"/>
                        <path d="M8 7a4 4 0 0 1 8 0"/>
                    </svg>

                    Paket Umroh
                </a>

                {{-- Testimoni --}}
                <a href="#"
                   class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium text-white transition duration-200 hover:bg-[#D7B51D]">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         width="20" height="20" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor"
                         stroke-width="1.8" stroke-linecap="round"
                         stroke-linejoin="round">
                        <path d="M21 11.5a8.4 8.4 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.4 8.4 0 0 1-3.8-.9L3 21l1.9-5.7a8.4 8.4 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.4 8.4 0 0 1 3.8-.9h.5a8.5 8.5 0 0 1 8 8v.5Z"/>
                    </svg>

                    Testimoni
                </a>

                {{-- Galeri --}}
                <a href="#"
                   class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium text-white transition duration-200 hover:bg-[#D7B51D]">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         width="20" height="20" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor"
                         stroke-width="1.8" stroke-linecap="round"
                         stroke-linejoin="round">
                        <rect x="3" y="3" width="18" height="18" rx="2"/>
                        <circle cx="8.5" cy="8.5" r="1.5"/>
                        <path d="m21 15-5-5L5 21"/>
                    </svg>

                    Galeri
                </a>

                {{-- Pengaturan Website --}}
                <a href="#"
                   class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium text-white transition duration-200 hover:bg-[#D7B51D]">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         width="20" height="20" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor"
                         stroke-width="1.8" stroke-linecap="round"
                         stroke-linejoin="round">
                        <circle cx="12" cy="12" r="3"/>
                        <path d="m19.4 15 .1.1 1.4 1.1-1.4 2.4-1.7-.6a8 8 0 0 1-1.4.8l-.3 1.8h-2.8l-.3-1.8a8 8 0 0 1-1.4-.8l-1.7.6-1.4-2.4 1.4-1.1a8 8 0 0 1 0-1.6l-1.4-1.1 1.4-2.4 1.7.6a8 8 0 0 1 1.4-.8l.3-1.8h2.8l.3 1.8a8 8 0 0 1 1.4.8l1.7-.6 1.4 2.4-1.4 1.1a8 8 0 0 1 0 1.6Z"/>
                    </svg>

                    Pengaturan Website
                </a>

            </nav>

            {{-- Profil dan Logout --}}
            <div class="mt-auto border-t border-black/10 px-4 py-5">

                {{-- Profil Pengguna --}}
                <div class="mb-4 flex items-center gap-3 rounded-lg bg-[#D7B51D] p-3">

                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-white text-sm font-bold text-gray-800">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>

                    <div class="min-w-0">
                        <p class="truncate text-sm font-semibold text-white">
                            {{ auth()->user()->name }}
                        </p>

                        <p class="text-xs text-white/90">
                            Administrator
                        </p>
                    </div>

                </div>

                {{-- Logout --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button type="submit"
                            class="flex w-full items-center gap-3 rounded-lg px-4 py-3 text-sm font-semibold text-white transition duration-200 hover:bg-[#D7B51D]">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             width="20" height="20" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor"
                             stroke-width="1.8" stroke-linecap="round"
                             stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                            <polyline points="16 17 21 12 16 7"/>
                            <line x1="21" x2="9" y1="12" y2="12"/>
                        </svg>

                        Logout
                    </button>
                </form>

            </div>

        </aside>

        {{-- MAIN CONTENT --}}
        <main class="min-w-0 flex-1 bg-white p-5 md:p-8">

            @yield('content')

        </main>

    </div>

</body>
</html>
