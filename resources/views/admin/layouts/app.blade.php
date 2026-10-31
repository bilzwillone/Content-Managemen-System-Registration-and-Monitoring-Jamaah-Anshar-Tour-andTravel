
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Dashboard Admin') - Ansar Travel</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-[#F7F7F7]">

    <div class="flex min-h-screen">

        {{-- Sidebar --}}
        <aside class="w-64 bg-[#000000] text-white">
            <div class="border-b border-slate-700 px-6 py-6">
                <h1 class="text-xl font-bold">ANSAR TRAVEL</h1>
                <p class="mt-1 text-sm text-slate-400">Admin CMS</p>
            </div>

            <nav class="space-y-2 px-4 py-6">
                <a href="{{ route('admin.dashboard') }}"
                   class="block rounded-lg bg-[#FFB22C] px-4 py-3 text-sm font-semibold text-black">
                    Dashboard
                </a>

                <p class="px-4 pt-5 text-xs font-semibold uppercase tracking-wider text-slate-500">
                    Manajemen Website
                </p>

                <a href="{{ route('admin.pages.index') }}"
                class="block rounded-lg px-4 py-3 text-sm hover:bg-[#854836]">
                    Kelola Halaman
                </a>

                <a href="#" class="block rounded-lg px-4 py-3 text-sm hover:bg-slate-800">
                    Paket Umroh
                </a>

                <a href="#" class="block rounded-lg px-4 py-3 text-sm hover:bg-slate-800">
                    Testimoni
                </a>

                <a href="#" class="block rounded-lg px-4 py-3 text-sm hover:bg-slate-800">
                    Galeri
                </a>

                <a href="#" class="block rounded-lg px-4 py-3 text-sm hover:bg-slate-800">
                    Pengaturan Website
                </a>
            </nav>
        </aside>

        {{-- Main Content --}}
        <div class="flex min-w-0 flex-1 flex-col">

            {{-- Header --}}
            <header class="flex items-center justify-between border-b bg-white px-8 py-5">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">
                        @yield('page-heading', 'Dashboard')
                    </h2>
                    <p class="text-sm text-gray-500">
                        Sistem Manajemen Konten Ansar Travel
                    </p>
                </div>

                <div class="flex items-center gap-4">
                    <span class="text-sm font-medium text-gray-700">
                        {{ auth()->user()->name }}
                    </span>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">
                            Logout
                        </button>
                    </form>
                </div>
            </header>

            {{-- Page Content --}}
            <main class="flex-1 bg-[#F7F7F7] p-8">
                @yield('content')
            </main>

        </div>
    </div>

</body>
</html>
