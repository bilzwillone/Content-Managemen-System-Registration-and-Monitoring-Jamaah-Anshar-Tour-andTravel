
@extends('admin.layouts.app')

@section('title', 'Paket Umroh')

@section('content')

<div class="space-y-6">

    {{-- Header Halaman --}}
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Paket Umroh
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Kelola informasi dan daftar paket perjalanan umroh Ansar Travel.
            </p>
        </div>

        <a href="{{ route('admin.packages.create') }}"
           class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#FDD52E] px-5 py-3 text-sm font-semibold text-gray-800 shadow-sm transition hover:bg-[#eac323]">

            <svg xmlns="http://www.w3.org/2000/svg"
                 width="18" height="18"
                 viewBox="0 0 24 24"
                 fill="none" stroke="currentColor"
                 stroke-width="2" stroke-linecap="round"
                 stroke-linejoin="round">
                <path d="M12 5v14M5 12h14"/>
            </svg>

            Tambah Paket
        </a>
    </div>

    {{-- Notifikasi --}}
    @if (session('success'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    {{-- Ringkasan --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">

        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-gray-500">Total Paket</p>
            <h2 class="mt-2 text-2xl font-bold text-gray-800">
                {{ $packages->total() }}
            </h2>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-gray-500">Paket Ditampilkan</p>
            <h2 class="mt-2 text-2xl font-bold text-green-600">
                {{ \App\Models\Package::where('is_visible', true)->count() }}
            </h2>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-gray-500">Paket Unggulan</p>
            <h2 class="mt-2 text-2xl font-bold text-amber-500">
                {{ \App\Models\Package::where('is_featured', true)->count() }}
            </h2>
        </div>

    </div>

    {{-- Tabel Paket --}}
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

        <div class="border-b border-gray-200 px-5 py-4">
            <h2 class="font-semibold text-gray-800">
                Daftar Paket Umroh
            </h2>

            <p class="mt-1 text-xs text-gray-500">
                Berikut seluruh paket umroh yang tersimpan dalam sistem.
            </p>
        </div>

        <div class="overflow-x-auto">

            <table class="w-full min-w-[900px] text-left text-sm">

                <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-5 py-4">Paket</th>
                        <th class="px-5 py-4">Harga</th>
                        <th class="px-5 py-4">Durasi</th>
                        <th class="px-5 py-4">Keberangkatan</th>
                        <th class="px-5 py-4">Status</th>
                        <th class="px-5 py-4 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">

                    @forelse ($packages as $package)

                        <tr class="transition hover:bg-gray-50">

                            {{-- Informasi Paket --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">

                                    @if ($package->image_path)
                                        <img
                                            src="{{ asset('storage/' . $package->image_path) }}"
                                            alt="{{ $package->name }}"
                                            class="h-14 w-16 rounded-lg object-cover"
                                        >
                                    @else
                                        <div class="flex h-14 w-16 items-center justify-center rounded-lg bg-gray-100 text-gray-400">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 width="24" height="24"
                                                 viewBox="0 0 24 24"
                                                 fill="none" stroke="currentColor"
                                                 stroke-width="1.5">
                                                <rect x="3" y="3" width="18" height="18" rx="2"/>
                                                <circle cx="8.5" cy="8.5" r="1.5"/>
                                                <path d="m21 15-5-5L5 21"/>
                                            </svg>
                                        </div>
                                    @endif

                                    <div>
                                        <p class="font-semibold text-gray-800">
                                            {{ $package->name }}
                                        </p>

                                        <p class="mt-1 text-xs text-gray-500">
                                            {{ $package->airline ?: 'Maskapai belum diisi' }}
                                        </p>

                                        @if ($package->is_featured)
                                            <span class="mt-1 inline-block rounded-full bg-amber-100 px-2 py-1 text-[10px] font-semibold text-amber-700">
                                                Unggulan
                                            </span>
                                        @endif
                                    </div>

                                </div>
                            </td>

                            {{-- Harga --}}
                            <td class="px-5 py-4 font-medium text-gray-800">
                                @if ($package->price !== null)
                                    Rp{{ number_format((float) $package->price, 0, ',', '.') }}
                                @else
                                    <span class="text-gray-400">Belum diisi</span>
                                @endif
                            </td>

                            {{-- Durasi --}}
                            <td class="px-5 py-4 text-gray-600">
                                {{ $package->duration_days ? $package->duration_days . ' Hari' : '-' }}
                            </td>

                            {{-- Tanggal Keberangkatan --}}
                            <td class="px-5 py-4 text-gray-600">
                                {{ $package->departure_date ? $package->departure_date->format('d M Y') : '-' }}
                            </td>

                            {{-- Status --}}
                            <td class="px-5 py-4">
                                @if ($package->status === 'published' && $package->is_visible)
                                    <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                        Dipublikasikan
                                    </span>
                                @elseif ($package->status === 'draft')
                                    <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">
                                        Draft
                                    </span>
                                @else
                                    <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                        Disembunyikan
                                    </span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-center gap-2">

                                    <a href="{{ route('admin.packages.edit', $package) }}"
                                       class="rounded-lg bg-blue-50 px-3 py-2 text-xs font-semibold text-blue-600 transition hover:bg-blue-100">
                                        Edit
                                    </a>

                                    <form
                                        action="{{ route('admin.packages.destroy', $package) }}"
                                        method="POST"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus paket ini?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="rounded-lg bg-red-50 px-3 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-100">
                                            Hapus
                                        </button>
                                    </form>

                                </div>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="px-5 py-16 text-center">

                                <div class="flex flex-col items-center">

                                    <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             width="30" height="30"
                                             viewBox="0 0 24 24"
                                             fill="none" stroke="currentColor"
                                             stroke-width="1.5">
                                            <path d="M16 10a4 4 0 0 1-8 0"/>
                                            <path d="M3 7h18l-1 13H4L3 7Z"/>
                                            <path d="M8 7a4 4 0 0 1 8 0"/>
                                        </svg>
                                    </div>

                                    <h3 class="font-semibold text-gray-700">
                                        Belum Ada Paket Umroh
                                    </h3>

                                    <p class="mt-1 text-sm text-gray-500">
                                        Silakan tambahkan paket umroh pertama Anda.
                                    </p>

                                    <a href="{{ route('admin.packages.create') }}"
                                       class="mt-4 rounded-lg bg-[#FDD52E] px-4 py-2 text-sm font-semibold text-gray-800 hover:bg-[#eac323]">
                                        Tambah Paket
                                    </a>

                                </div>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        {{-- Pagination --}}
        @if ($packages->hasPages())
            <div class="border-t border-gray-200 px-5 py-4">
                {{ $packages->links() }}
            </div>
        @endif

    </div>

</div>

@endsection
