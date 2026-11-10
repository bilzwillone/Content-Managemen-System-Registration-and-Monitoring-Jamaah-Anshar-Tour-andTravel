
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Dashboard Admin') - Ansar Travel</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gray-50 text-gray-800">

    <div class="flex min-h-screen">

        <!-- SIDEBAR -->
        <aside class="sticky top-0 flex h-screen w-64 shrink-0 flex-col overflow-y-auto bg-[#171717] text-white">

            <!-- Logo dan Nama Sistem -->
            <div class="border-b border-white/10 px-4 py-5">

                <div class="flex items-center gap-3">

                    <img
                        src="{{ asset('images/ansar-travel-logo.png') }}"
                        alt="Logo Ansar Travel"
                        class="h-14 w-14 shrink-0 object-contain"
                    >

                    <div class="min-w-0">
                        <h1 class="text-base font-bold leading-tight text-[#FDD52E]">
                            ANSAR TRAVEL
                        </h1>

                        <p class="mt-1 text-xs font-medium text-gray-400">
                            Management System
                        </p>
                    </div>

                </div>

            </div>

            <!-- Menu Navigasi -->
            <nav class="flex-1 space-y-1 px-3 py-6">

                <p class="mb-3 px-3 text-xs font-bold uppercase tracking-wider text-gray-500">
                    Menu Utama
                </p>

                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-semibold transition duration-200
                   {{ request()->routeIs('admin.dashboard') ? 'bg-[#FDD52E] text-[#171717] shadow-sm' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">

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

                <p class="mb-3 mt-7 px-3 text-xs font-bold uppercase tracking-wider text-gray-500">
                    Manajemen Website
                </p>

                <!-- Kelola Halaman -->
                <a href="{{ route('admin.pages.index') }}"
                   class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium transition duration-200
                   {{ request()->routeIs('admin.pages.*') ? 'bg-[#FDD52E] text-[#171717] shadow-sm' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">

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

                <!-- Paket Umroh -->
                <a href="{{ route('admin.packages.index') }}"
                   class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium transition duration-200
                   {{ request()->routeIs('admin.packages.*') ? 'bg-[#FDD52E] text-[#171717] shadow-sm' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">

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

                <!-- Jadwal Keberangkatan -->
                <a href="{{ route('admin.schedules.index') }}"
                   class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium transition duration-200
                   {{ request()->routeIs('admin.schedules.*') ? 'bg-[#FDD52E] text-[#171717] shadow-sm' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         width="20" height="20" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor"
                         stroke-width="1.8" stroke-linecap="round"
                         stroke-linejoin="round">
                        <rect x="3" y="5" width="18" height="16" rx="2"/>
                        <path d="M16 3v4M8 3v4M3 11h18"/>
                        <path d="m9 16 2 2 4-4"/>
                    </svg>

                    Jadwal Keberangkatan
                </a>


            </nav>

            <!-- Profil dan Logout -->
            <div class="mt-auto border-t border-white/10 px-4 py-5">

                <!-- Profil Pengguna -->
                <div class="mb-4 flex items-center gap-3 rounded-lg border border-white/10 bg-white/5 p-3">

                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[#FDD52E] text-sm font-bold text-gray-900">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>

                    <div class="min-w-0">
                        <p class="truncate text-sm font-semibold text-white">
                            {{ auth()->user()->name }}
                        </p>

                        <p class="text-xs text-gray-400">
                            Administrator
                        </p>
                    </div>

                </div>

                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button type="submit"
                            class="flex w-full items-center gap-3 rounded-lg px-4 py-3 text-sm font-semibold text-gray-300 transition duration-200 hover:bg-red-500/10 hover:text-red-400">

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

        <!-- MAIN CONTENT -->
        <main class="min-w-0 flex-1 bg-gray-50 p-5 md:p-8">

            @yield('content')

        </main>

    </div>

</body>
</html>
