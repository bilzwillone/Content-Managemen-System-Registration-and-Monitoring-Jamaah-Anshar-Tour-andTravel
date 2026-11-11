@php
    $role = auth()->user()->role ?? null;

    $menuGroups = match ($role) {
        'admin' => [
            [
                'title' => 'Menu Utama',
                'items' => [
                    [
                        'label' => 'Dashboard',
                        'route' => 'admin.dashboard',
                        'active' => 'admin.dashboard',
                    ],
                ],
            ],
            [
                'title' => 'Manajemen Website',
                'items' => [
                    [
                        'label' => 'Kelola Halaman',
                        'route' => 'admin.pages.index',
                        'active' => 'admin.pages.*',
                    ],
                    [
                        'label' => 'Paket Umroh',
                        'route' => 'admin.packages.index',
                        'active' => 'admin.packages.*',
                    ],
                    [
                        'label' => 'Jadwal Keberangkatan',
                        'route' => 'admin.schedules.index',
                        'active' => 'admin.schedules.*',
                    ],
                ],
            ],
            [
                'title' => 'Manajemen Sistem',
                'items' => [
                    [
                        'label' => 'Manajemen Pengguna',
                        'route' => 'admin.users.index',
                        'active' => 'admin.users.*',
                    ],
                ],
            ],
        ],

        'karyawan' => [
            [
                'title' => 'Menu Utama',
                'items' => [
                    [
                        'label' => 'Dashboard',
                        'route' => 'karyawan.dashboard',
                        'active' => 'karyawan.dashboard',
                    ],
                ],
            ],
            [
                'title' => 'Manajemen Jamaah',
                'items' => [
                    [
                        'label' => 'Calon Jamaah & CRM',
                        'route' => 'karyawan.prospects.index',
                        'active' => 'karyawan.prospects.*',
                    ],
                    [
                        'label' => 'Data Jamaah',
                        'route' => 'karyawan.jamaahs.index',
                        'active' => 'karyawan.jamaahs.*',
                    ],
                    [
                        'label' => 'Dokumen Jamaah',
                        'route' => 'karyawan.documents.index',
                        'active' => 'karyawan.documents.*',
                    ],
                ],
            ],
            [
                'title' => 'Operasional Travel',
                'items' => [
                    [
                        'label' => 'Pembayaran',
                        'route' => 'karyawan.payments.index',
                        'active' => 'karyawan.payments.index',
                    ],
                    [
                        'label' => 'Riwayat Pembayaran',
                        'route' => 'karyawan.payments.history',
                        'active' => 'karyawan.payments.history',
                    ],
                    [
                        'label' => 'Persiapan Keberangkatan',
                        'route' => null,
                    ],
                    [
                        'label' => 'Kelompok & Muthawif',
                        'route' => 'karyawan.groups.index',
                        'active' => 'karyawan.groups.*',
                    ],
                ],
            ],
        ],

        'muthawif' => [
            [
                'title' => 'Menu Utama',
                'items' => [
                    [
                        'label' => 'Dashboard',
                        'route' => 'muthawif.dashboard',
                        'active' => 'muthawif.dashboard',
                    ],
                ],
            ],
            [
                'title' => 'Aktivitas Perjalanan',
                'items' => [
                    [
                        'label' => 'Kelompok Jamaah',
                        'route' => null,
                    ],
                    [
                        'label' => 'Aktivitas Ibadah',
                        'route' => null,
                    ],
                    [
                        'label' => 'Laporan',
                        'route' => null,
                    ],
                ],
            ],
        ],

        'jamaah' => [
            [
                'title' => 'Menu Utama',
                'items' => [
                    [
                        'label' => 'Dashboard',
                        'route' => 'jamaah.dashboard',
                        'active' => 'jamaah.dashboard',
                    ],
                ],
            ],
            [
                'title' => 'Layanan Jamaah',
                'items' => [
                    [
                        'label' => 'Profil Saya',
                        'route' => null,
                    ],
                    [
                        'label' => 'Dokumen',
                        'route' => null,
                    ],
                    [
                        'label' => 'Pembayaran',
                        'route' => null,
                    ],
                    [
                        'label' => 'Informasi Perjalanan',
                        'route' => null,
                    ],
                ],
            ],
        ],

        default => [],
    };
@endphp

<aside class="sticky top-0 hidden h-screen w-64 shrink-0 self-start flex-col bg-[#171717] text-white md:flex">

    <!-- LOGO -->
    <div class="shrink-0 border-b border-white/10 px-5 py-5">
        <div class="flex items-center gap-3">
            <img
                src="{{ asset('images/ansar-travel-logo.png') }}"
                alt="Logo Ansar Travel"
                class="h-11 w-11 object-contain"
            >

            <div>
                <h1 class="text-sm font-bold text-[#FDD52E]">
                    ANSAR TRAVEL
                </h1>
                <p class="mt-1 text-xs text-gray-400">
                    Management System
                </p>
            </div>
        </div>
    </div>

    <!-- NAVIGASI BERDASARKAN ROLE -->
    <nav class="min-h-0 flex-1 space-y-1 overflow-y-auto px-3 py-5">

        @foreach ($menuGroups as $group)
            <p class="mb-3 {{ !$loop->first ? 'mt-7' : '' }} px-3 text-[11px] font-bold uppercase tracking-wider text-gray-500">
                {{ $group['title'] }}
            </p>

            @foreach ($group['items'] as $item)

                @if ($item['route'])
                    <a
                        href="{{ route($item['route']) }}"
                        class="{{ request()->routeIs($item['active'] ?? '') ? 'bg-[#FDD52E] font-semibold text-gray-900' : 'text-gray-300 hover:bg-white/10 hover:text-white' }} flex items-center gap-3 rounded-lg px-4 py-3 text-sm transition"
                    >
                        <span class="min-w-0 flex-1">
                            {{ $item['label'] }}
                        </span>
                    </a>
                @else
                    <div class="flex items-center justify-between rounded-lg px-4 py-3 text-sm text-gray-400">
                        <span>{{ $item['label'] }}</span>
                        <span class="text-[10px] text-gray-500">Segera</span>
                    </div>
                @endif

            @endforeach
        @endforeach

    </nav>

    <!-- PROFIL DAN LOGOUT -->
    <div class="shrink-0 border-t border-white/10 px-4 py-4">

        <div class="mb-3 flex items-center gap-3 rounded-lg border border-white/10 bg-white/5 p-3">

            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[#FDD52E] font-bold text-gray-900">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>

            <div class="min-w-0">
                <p class="truncate text-sm font-semibold text-white">
                    {{ auth()->user()->name }}
                </p>

                <p class="text-xs capitalize text-gray-400">
                    {{ auth()->user()->role }}
                </p>
            </div>

        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button
                type="submit"
                class="flex w-full items-center gap-3 rounded-lg px-4 py-3 text-sm font-semibold text-gray-300 transition hover:bg-red-500/10 hover:text-red-400"
            >
                Logout
            </button>
        </form>

    </div>
    

</aside>
