
@extends('admin.layouts.app')

@section('title', 'Manajemen Pengguna')

@section('content')

<style>
    .user-summary-row {
        display: flex;
        flex-wrap: nowrap;
        gap: 14px;
    }

    .user-summary-card {
        flex: 1 1 0;
        min-width: 0;
    }

    .user-filter-form {
        display: flex;
        flex-wrap: nowrap;
        align-items: center;
        gap: 10px;
        width: 100%;
        min-width: 760px;
    }

    .user-filter-search {
        flex: 1 1 auto;
        min-width: 200px;
    }

    .user-filter-role {
        flex: 0 0 165px;
    }

    .user-filter-button {
        flex: 0 0 85px;
    }

    @media (max-width: 1100px) {
        .user-summary-row {
            overflow-x: auto;
            padding-bottom: 5px;
        }

        .user-summary-card {
            flex: 0 0 210px;
        }
    }
</style>

<div class="space-y-6">

    <!-- HEADER -->
    <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">

        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                Manajemen Pengguna
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Kelola akun dan hak akses pengguna Ansar Travel.
            </p>
        </div>

        <div class="flex items-center gap-3">

            <span class="inline-flex items-center gap-2 whitespace-nowrap rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-600">
                <span class="h-2 w-2 rounded-full bg-green-500"></span>
                4 Role Pengguna
            </span>

            <a href="{{ route('admin.users.create') }}"
               class="inline-flex items-center gap-2 whitespace-nowrap rounded-lg bg-[#FDD52E] px-4 py-2.5 text-sm font-bold text-gray-900 transition hover:bg-amber-400">
                <span class="text-lg">+</span>
                Tambah Pengguna
            </a>

        </div>

    </div>


    <!-- RINGKASAN -->
    <div class="user-summary-row">

        <div class="user-summary-card rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-gray-500">Total Pengguna</p>
            <h3 class="mt-3 text-3xl font-bold text-gray-900">{{ $totalUsers }}</h3>
            <p class="mt-2 text-xs text-gray-400">Seluruh akun terdaftar</p>
        </div>

        <div class="user-summary-card rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between gap-2">
                <p class="text-sm font-medium text-gray-500">Admin</p>
                <span class="rounded-full bg-purple-100 px-2.5 py-1 text-xs font-semibold text-purple-700">Admin</span>
            </div>
            <h3 class="mt-3 text-3xl font-bold text-gray-900">{{ $roleCounts->get('admin', 0) }}</h3>
            <p class="mt-2 text-xs text-gray-400">Pengelola sistem</p>
        </div>

        <div class="user-summary-card rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between gap-2">
                <p class="text-sm font-medium text-gray-500">Karyawan</p>
                <span class="rounded-full bg-blue-100 px-2.5 py-1 text-xs font-semibold text-blue-700">Karyawan</span>
            </div>
            <h3 class="mt-3 text-3xl font-bold text-gray-900">{{ $roleCounts->get('karyawan', 0) }}</h3>
            <p class="mt-2 text-xs text-gray-400">Pengelola operasional</p>
        </div>

        <div class="user-summary-card rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between gap-2">
                <p class="text-sm font-medium text-gray-500">Muthawif</p>
                <span class="rounded-full bg-green-100 px-2.5 py-1 text-xs font-semibold text-green-700">Muthawif</span>
            </div>
            <h3 class="mt-3 text-3xl font-bold text-gray-900">{{ $roleCounts->get('muthawif', 0) }}</h3>
            <p class="mt-2 text-xs text-gray-400">Pendamping jamaah</p>
        </div>

        <div class="user-summary-card rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between gap-2">
                <p class="text-sm font-medium text-gray-500">Jamaah</p>
                <span class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-700">Jamaah</span>
            </div>
            <h3 class="mt-3 text-3xl font-bold text-gray-900">{{ $roleCounts->get('jamaah', 0) }}</h3>
            <p class="mt-2 text-xs text-gray-400">Pengguna layanan travel</p>
        </div>

    </div>


    <!-- PESAN BERHASIL -->
    @if (session('success'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <!-- PESAN GAGAL -->
    @if (session('error'))
        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
            {{ session('error') }}
        </div>
    @endif


    <!-- DAFTAR PENGGUNA -->
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

        <div class="flex items-center justify-between gap-3 border-b border-gray-200 px-6 py-5">

            <div>
                <h2 class="text-lg font-bold text-gray-900">
                    Daftar Pengguna
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Informasi akun dan hak akses seluruh pengguna.
                </p>
            </div>

            <span class="whitespace-nowrap rounded-full bg-gray-100 px-3 py-1.5 text-xs font-semibold text-gray-600">
                {{ $users->total() }} Akun
            </span>

        </div>


        <!-- PENCARIAN -->
        <div class="border-b border-gray-200 p-5">

            <div class="overflow-x-auto">

                <form action="{{ route('admin.users.index') }}"
                      method="GET"
                      class="user-filter-form">

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari nama atau email pengguna..."
                        class="user-filter-search rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none focus:border-amber-400 focus:ring-2 focus:ring-amber-100"
                    >

                    <select
                        name="role"
                        class="user-filter-role rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm outline-none focus:border-amber-400 focus:ring-2 focus:ring-amber-100">

                        <option value="">Semua Role</option>
                        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="karyawan" {{ request('role') === 'karyawan' ? 'selected' : '' }}>Karyawan</option>
                        <option value="muthawif" {{ request('role') === 'muthawif' ? 'selected' : '' }}>Muthawif</option>
                        <option value="jamaah" {{ request('role') === 'jamaah' ? 'selected' : '' }}>Jamaah</option>

                    </select>

                    <button
                        type="submit"
                        class="user-filter-button whitespace-nowrap rounded-lg bg-[#FDD52E] px-4 py-3 text-sm font-semibold text-gray-900 hover:bg-amber-400">
                        Cari
                    </button>

                    <a
                        href="{{ route('admin.users.index') }}"
                        class="user-filter-button whitespace-nowrap rounded-lg border border-gray-300 px-4 py-3 text-center text-sm font-medium text-gray-600 hover:bg-gray-50">
                        Reset
                    </a>

                </form>

            </div>

        </div>


        <!-- TABEL -->
        <div class="overflow-x-auto">

            <table class="w-full min-w-[1050px] text-left text-sm">

                <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-6 py-4 font-semibold">No.</th>
                        <th class="px-6 py-4 font-semibold">Nama Pengguna</th>
                        <th class="px-6 py-4 font-semibold">Email</th>
                        <th class="px-6 py-4 font-semibold">Role</th>
                        <th class="px-6 py-4 font-semibold">Tanggal Bergabung</th>
                        <th class="px-6 py-4 text-center font-semibold">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">

                    @forelse ($users as $index => $user)

                        @php
                            $roleClass = match ($user->role) {
                                'admin' => 'bg-purple-100 text-purple-700',
                                'karyawan' => 'bg-blue-100 text-blue-700',
                                'muthawif' => 'bg-green-100 text-green-700',
                                'jamaah' => 'bg-amber-100 text-amber-700',
                                default => 'bg-gray-100 text-gray-600',
                            };

                            $roleLabel = match ($user->role) {
                                'admin' => 'Admin',
                                'karyawan' => 'Karyawan',
                                'muthawif' => 'Muthawif',
                                'jamaah' => 'Jamaah',
                                default => ucfirst($user->role),
                            };
                        @endphp

                        <tr class="transition hover:bg-gray-50">

                            <td class="px-6 py-4 text-gray-500">
                                {{ $users->firstItem() + $index }}
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">

                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[#FDD52E] font-bold text-gray-900">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>

                                    <div>
                                        <p class="font-semibold text-gray-800">
                                            {{ $user->name }}
                                        </p>

                                        <p class="mt-1 text-xs text-gray-400">
                                            ID Pengguna: #{{ $user->id }}
                                        </p>
                                    </div>

                                </div>
                            </td>

                            <td class="px-6 py-4 text-gray-600">
                                {{ $user->email }}
                            </td>

                            <td class="px-6 py-4">
                                <span class="inline-flex rounded-full px-3 py-1.5 text-xs font-semibold {{ $roleClass }}">
                                    {{ $roleLabel }}
                                </span>
                            </td>

                            <td class="whitespace-nowrap px-6 py-4 text-gray-500">
                                {{ $user->created_at?->format('d M Y') ?? '-' }}
                            </td>

                            <td class="px-6 py-4">

                                <div class="flex items-center justify-center gap-2">

                                    <!-- EDIT -->
                                    <a
                                        href="{{ route('admin.users.edit', $user) }}"
                                        class="inline-flex items-center gap-1.5 rounded-lg border border-amber-300 bg-amber-50 px-3 py-2 text-xs font-semibold text-amber-800 transition hover:bg-[#FDD52E]">

                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             width="15" height="15"
                                             viewBox="0 0 24 24"
                                             fill="none" stroke="currentColor"
                                             stroke-width="2" stroke-linecap="round"
                                             stroke-linejoin="round">
                                            <path d="m16 4 4 4"/>
                                            <path d="M19 7 9 17l-4 1 1-4L16 4a2.1 2.1 0 0 1 3 3Z"/>
                                        </svg>

                                        Edit
                                    </a>


                                    <!-- HAPUS -->
                                    @if ($user->id !== auth()->id())

                                        <form
                                            action="{{ route('admin.users.destroy', $user) }}"
                                            method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun {{ addslashes($user->name) }}? Tindakan ini tidak dapat dibatalkan.');">

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="inline-flex items-center gap-1.5 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-xs font-semibold text-red-700 transition hover:bg-red-100">

                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                     width="15" height="15"
                                                     viewBox="0 0 24 24"
                                                     fill="none" stroke="currentColor"
                                                     stroke-width="2" stroke-linecap="round"
                                                     stroke-linejoin="round">
                                                    <path d="M3 6h18"/>
                                                    <path d="M8 6V4h8v2"/>
                                                    <path d="m19 6-1 14H6L5 6"/>
                                                    <path d="M10 11v5M14 11v5"/>
                                                </svg>

                                                Hapus
                                            </button>

                                        </form>

                                    @endif

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">

                                <h3 class="font-semibold text-gray-800">
                                    Pengguna Tidak Ditemukan
                                </h3>

                                <p class="mt-2 text-sm text-gray-500">
                                    Belum ada akun yang sesuai dengan pencarian atau filter.
                                </p>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        <!-- PAGINATION -->
        <div class="flex flex-col justify-between gap-3 border-t border-gray-200 px-6 py-4 sm:flex-row sm:items-center">

            <p class="text-sm text-gray-500">
                Menampilkan
                {{ $users->firstItem() ?? 0 }}
                sampai
                {{ $users->lastItem() ?? 0 }}
                dari {{ $users->total() }} pengguna.
            </p>

            <div>
                {{ $users->links() }}
            </div>

        </div>

    </div>

</div>

@endsection
