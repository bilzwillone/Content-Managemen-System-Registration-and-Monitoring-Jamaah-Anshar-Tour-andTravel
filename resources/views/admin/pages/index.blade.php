
@extends('admin.layouts.app')

@section('title', 'Kelola Halaman')

@section('page-heading', 'Kelola Halaman')

@section('content')

<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">

        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Kelola Halaman Website
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Atur halaman dan susunan konten website Ansar Travel.
            </p>
        </div>

        {{-- TOMBOL TAMBAH HALAMAN --}}
        <a
            href="{{ route('admin.pages.create') }}"
            class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#FDD52E] px-5 py-3 text-sm font-semibold text-gray-900 shadow-sm transition hover:bg-[#eac323]"
        >
            <svg xmlns="http://www.w3.org/2000/svg"
                 width="18" height="18"
                 viewBox="0 0 24 24"
                 fill="none" stroke="currentColor"
                 stroke-width="2"
                 stroke-linecap="round"
                 stroke-linejoin="round">
                <path d="M12 5v14M5 12h14"/>
            </svg>

            Tambah Halaman
        </a>

    </div>


    {{-- NOTIFIKASI --}}
    @if (session('success'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
    <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
        {{ session('error') }}
    </div>
    @endif


    {{-- RINGKASAN --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">

        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-gray-500">
                Total Halaman
            </p>

            <h2 class="mt-2 text-2xl font-bold text-gray-800">
                {{ $pages->count() }}
            </h2>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-gray-500">
                Halaman Published
            </p>

            <h2 class="mt-2 text-2xl font-bold text-green-600">
                {{ $pages->where('is_published', true)->count() }}
            </h2>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-gray-500">
                Tampil di Navbar
            </p>

            <h2 class="mt-2 text-2xl font-bold text-amber-500">
                {{ $pages->where('show_in_navbar', true)->count() }}
            </h2>
        </div>

    </div>


    {{-- TABEL DAFTAR HALAMAN --}}
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

        <div class="border-b border-gray-200 px-6 py-5">

            <h2 class="font-semibold text-gray-800">
                Daftar Halaman Website
            </h2>

            <p class="mt-1 text-xs text-gray-500">
                Kelola halaman, publikasi, navigasi, dan section website.
            </p>

        </div>

        <div class="overflow-x-auto">

            <table class="w-full min-w-[900px] text-left text-sm">

                <thead class="bg-gray-50 text-xs uppercase text-gray-500">

                    <tr>
                        <th class="px-5 py-4">No.</th>
                        <th class="px-5 py-4">Nama Halaman</th>
                        <th class="px-5 py-4">Slug</th>
                        <th class="px-5 py-4 text-center">Section</th>
                        <th class="px-5 py-4 text-center">Navbar</th>
                        <th class="px-5 py-4 text-center">Status</th>
                        <th class="px-5 py-4 text-center">Aksi</th>
                    </tr>

                </thead>

                <tbody class="divide-y divide-gray-100">

                    @forelse ($pages as $page)

                        <tr class="transition hover:bg-gray-50">

                            {{-- NOMOR --}}
                            <td class="px-5 py-4 text-gray-600">
                                {{ $loop->iteration }}
                            </td>

                            {{-- NAMA HALAMAN --}}
                            <td class="px-5 py-4">

                                <p class="font-semibold text-gray-800">
                                    {{ $page->title }}
                                </p>

                                @if ($page->description)
                                    <p class="mt-1 max-w-xs truncate text-xs text-gray-400">
                                        {{ $page->description }}
                                    </p>
                                @endif

                            </td>

                            {{-- SLUG --}}
                            <td class="px-5 py-4 text-gray-500">
                                /{{ $page->slug }}
                            </td>

                            {{-- JUMLAH SECTION --}}
                            <td class="px-5 py-4 text-center text-gray-600">
                                {{ $page->sections_count }} Section
                            </td>

                            {{-- NAVBAR --}}
                            <td class="px-5 py-4 text-center">

                                @if ($page->show_in_navbar)
                                    <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">
                                        Ditampilkan
                                    </span>
                                    <p class="mt-1 text-[10px] text-gray-400">
                                        Urutan {{ $page->nav_order }}
                                    </p>
                                @else
                                    <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-500">
                                        Disembunyikan
                                    </span>
                                @endif

                            </td>

                            {{-- STATUS --}}
                            <td class="px-5 py-4 text-center">

                                @if ($page->is_published)
                                    <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                        Published
                                    </span>
                                @else
                                    <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">
                                        Draft
                                    </span>
                                @endif

                            </td>

                            <td class="px-5 py-4">
                                <div class="flex flex-wrap items-center justify-center gap-2">
                                    <a
                                        href="{{ route('admin.pages.edit', $page->id) }}"
                                        class="rounded-lg bg-blue-50 px-3 py-2 text-xs font-semibold text-blue-600 transition hover:bg-blue-100">
                                        Edit
                                    </a>
                                    <a
                                        href="{{ route('admin.pages.sections', $page->id) }}"
                                        class="rounded-lg bg-[#FDD52E] px-3 py-2 text-xs font-semibold text-gray-900 transition hover:bg-[#eac323]">
                                        Kelola Section
                                    </a>
                                    @if ($page->slug !== 'beranda')

                                        <form
                                            action="{{ route('admin.pages.destroy', $page->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus halaman {{ addslashes($page->title) }} beserta seluruh section dan gambar di dalamnya? Tindakan ini tidak dapat dibatalkan.');">

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-100">
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="7" class="px-5 py-16 text-center">

                                <div class="flex flex-col items-center">

                                    <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             width="30" height="30"
                                             viewBox="0 0 24 24"
                                             fill="none" stroke="currentColor"
                                             stroke-width="1.5">
                                            <rect x="3" y="3" width="18" height="18" rx="2"/>
                                            <path d="M8 12h8M12 8v8"/>
                                        </svg>
                                    </div>

                                    <h3 class="font-semibold text-gray-700">
                                        Belum Ada Halaman
                                    </h3>

                                    <p class="mt-1 text-sm text-gray-500">
                                        Silakan tambahkan halaman website pertama Anda.
                                    </p>

                                    <a
                                        href="{{ route('admin.pages.create') }}"
                                        class="mt-4 rounded-lg bg-[#FDD52E] px-4 py-2 text-sm font-semibold text-gray-900 hover:bg-[#eac323]"
                                    >
                                        Tambah Halaman
                                    </a>

                                </div>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection
