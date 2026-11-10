
@extends('admin.layouts.app')

@section('title', 'Kelola Section')

@section('page-heading', 'Kelola Section')

@section('content')

    {{-- NOTIFIKASI --}}
    @if (session('success'))
        <div class="mb-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif

    {{-- ERROR VALIDASI --}}
    @if ($errors->any())
        <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3">
            <p class="mb-2 text-sm font-semibold text-red-700">
                Periksa kembali perubahan yang dilakukan.
            </p>

            <ul class="list-inside list-disc space-y-1 text-sm text-red-600">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {{-- HEADER HALAMAN --}}
    <div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">

        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Kelola Section {{ $page->title }}
            </h1>

            <p class="mt-2 text-sm text-gray-500">
                Atur konten, urutan, publikasi, dan tampilan setiap bagian halaman {{ $page->title }}.
            </p>
        </div>

        <a
            href="{{ route('admin.pages.sections.create', $page->id) }}"
            class="inline-flex items-center justify-center rounded-lg bg-[#FFB22C] px-5 py-3 text-sm font-semibold text-black transition hover:bg-amber-400"
        >
            + Tambah Section
        </a>

    </div>


    {{-- RINGKASAN --}}
    <div class="mb-5 grid grid-cols-1 gap-4 sm:grid-cols-3">

        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
            <p class="text-xs font-medium text-gray-500">
                Total Section
            </p>

            <p class="mt-2 text-2xl font-bold text-gray-800">
                {{ $sections->count() }}
            </p>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
            <p class="text-xs font-medium text-gray-500">
                Section Published
            </p>

            <p class="mt-2 text-2xl font-bold text-green-600">
                {{ $sections->where('status', 'published')->count() }}
            </p>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
            <p class="text-xs font-medium text-gray-500">
                Section Ditampilkan
            </p>

            <p class="mt-2 text-2xl font-bold text-blue-600">
                {{ $sections->where('is_visible', true)->count() }}
            </p>
        </div>

    </div>


    {{-- TABEL SECTION --}}
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

        {{-- HEADER TABEL --}}
        <div class="flex flex-col justify-between gap-2 border-b border-gray-200 px-5 py-4 sm:flex-row sm:items-center">

            <div>
                <h2 class="font-semibold text-gray-800">
                    Daftar Section
                </h2>

                <p class="mt-1 text-xs text-gray-500">
                    Kelola seluruh section pada halaman {{ $page->title }}.
                </p>
            </div>

            <span class="text-xs text-gray-500">
                {{ $sections->count() }} data section
            </span>

        </div>


        <div class="overflow-x-auto">

            <table class="w-full min-w-[1050px] text-left text-sm">

                {{-- HEADER KOLOM --}}
                <thead class="bg-gray-50 text-xs font-semibold uppercase tracking-wide text-gray-500">

                    <tr>
                        <th class="w-16 px-5 py-4">
                            No.
                        </th>

                        <th class="px-5 py-4">
                            Nama Section
                        </th>

                        <th class="w-48 px-5 py-4">
                            Urutan
                        </th>

                        <th class="w-40 px-5 py-4">
                            Status
                        </th>

                        <th class="w-44 px-5 py-4">
                            Visibilitas
                        </th>

                        <th class="w-52 px-5 py-4 text-center">
                            Aksi
                        </th>
                    </tr>

                </thead>


                {{-- ISI TABEL --}}
                <tbody class="divide-y divide-gray-100">

                    @forelse ($sections as $section)

                        <tr class="transition hover:bg-gray-50">

                            {{-- NOMOR --}}
                            <td class="px-5 py-4 text-gray-500">
                                {{ $loop->iteration }}
                            </td>


                            {{-- INFORMASI SECTION --}}
                            <td class="px-5 py-4">

                                <div class="max-w-sm">

                                    <p class="font-semibold text-gray-800">
                                        {{ $section->title ?: ucfirst(str_replace('_', ' ', $section->section_type)) }}
                                    </p>

                                    <div class="mt-1 flex flex-wrap items-center gap-2">

                                        <span class="text-xs text-gray-500">
                                            {{ ucfirst(str_replace('_', ' ', $section->section_type)) }}
                                        </span>

                                        <span class="text-gray-300">
                                            |
                                        </span>

                                        <span class="text-xs text-gray-400">
                                            ID: {{ $section->id }}
                                        </span>

                                    </div>

                                </div>

                            </td>


                            {{-- PENGATURAN URUTAN --}}
                            <td class="px-5 py-4">

                                <form
                                    action="{{ route('admin.pages.sections.order', [$page->id, $section->id]) }}"
                                    method="POST"
                                    class="flex items-center gap-2"
                                >

                                    @csrf
                                    @method('PATCH')

                                    <input
                                        type="number"
                                        name="sort_order"
                                        min="1"
                                        required
                                        value="{{ $section->sort_order }}"
                                        aria-label="Urutan section {{ $section->title }}"
                                        class="w-20 rounded-lg border border-gray-300 px-2.5 py-2 text-sm outline-none transition focus:border-amber-400 focus:ring-2 focus:ring-amber-100"
                                    >

                                    <button
                                        type="submit"
                                        class="rounded-lg bg-gray-800 px-3 py-2 text-xs font-semibold text-white transition hover:bg-gray-700"
                                    >
                                        Simpan
                                    </button>

                                </form>

                            </td>


                            {{-- STATUS PUBLIKASI --}}
                            <td class="px-5 py-4">

                                <select
                                    name="status"
                                    form="settings-form-{{ $section->id }}"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-2 py-2 text-xs outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-100"
                                >

                                    <option
                                        value="draft"
                                        {{ $section->status === 'draft' ? 'selected' : '' }}
                                    >
                                        Draft
                                    </option>

                                    <option
                                        value="published"
                                        {{ $section->status === 'published' ? 'selected' : '' }}
                                    >
                                        Published
                                    </option>

                                </select>

                            </td>


                            {{-- VISIBILITAS --}}
                            <td class="px-5 py-4">

                                <select
                                    name="is_visible"
                                    form="settings-form-{{ $section->id }}"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-2 py-2 text-xs outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-100"
                                >

                                    <option
                                        value="1"
                                        {{ $section->is_visible ? 'selected' : '' }}
                                    >
                                        Ditampilkan
                                    </option>

                                    <option
                                        value="0"
                                        {{ !$section->is_visible ? 'selected' : '' }}
                                    >
                                        Disembunyikan
                                    </option>

                                </select>

                            </td>


                            {{-- AKSI --}}
                            <td class="px-5 py-4">

                                <div class="flex flex-wrap items-center justify-center gap-2">

                                    {{-- FORM PENGATURAN --}}
                                    <form
                                        id="settings-form-{{ $section->id }}"
                                        action="{{ route('admin.pages.sections.settings', [$page->id, $section->id]) }}"
                                        method="POST"
                                    >
                                        @csrf
                                        @method('PATCH')
                                    </form>

                                    {{-- SIMPAN PENGATURAN --}}
                                    <button
                                        type="submit"
                                        form="settings-form-{{ $section->id }}"
                                        class="rounded-lg bg-gray-800 px-3 py-2 text-xs font-semibold text-white transition hover:bg-gray-700"
                                    >
                                        Simpan
                                    </button>


                                    {{-- EDIT --}}
                                    <a
                                        href="{{ route('admin.pages.sections.edit', [$page->id, $section->id]) }}"
                                        class="rounded-lg bg-[#FFB22C] px-3 py-2 text-xs font-semibold text-black transition hover:bg-amber-400"
                                    >
                                        Edit
                                    </a>


                                    {{-- HAPUS --}}
                                    <form
                                        action="{{ route('admin.pages.sections.destroy', [$page->id, $section->id]) }}"
                                        method="POST"
                                        onsubmit="return confirm('Apakah kamu yakin ingin menghapus section ini? Data yang dihapus tidak dapat dikembalikan.')"
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="rounded-lg bg-red-50 px-3 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-100"
                                        >
                                            Hapus
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="px-5 py-16 text-center">

                                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-gray-100">

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-7 w-7 text-gray-400"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="1.5"
                                    >
                                        <rect x="3" y="3" width="18" height="18" rx="2"/>
                                        <path d="M8 12h8M12 8v8"/>
                                    </svg>

                                </div>

                                <h3 class="mt-4 font-semibold text-gray-800">
                                    Belum Ada Section
                                </h3>

                                <p class="mt-2 text-sm text-gray-500">
                                    Halaman {{ $page->title }} belum memiliki section konten.
                                </p>

                                <a
                                    href="{{ route('admin.pages.sections.create', $page->id) }}"
                                    class="mt-5 inline-flex rounded-lg bg-[#FFB22C] px-5 py-2.5 text-sm font-semibold text-black hover:bg-amber-400"
                                >
                                    + Tambah Section Pertama
                                </a>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- FOOTER TABEL --}}
        @if ($sections->isNotEmpty())

            <div class="flex flex-col justify-between gap-2 border-t border-gray-200 bg-gray-50 px-5 py-3 sm:flex-row sm:items-center">

                <p class="text-xs text-gray-500">
                    Menampilkan {{ $sections->count() }} section pada halaman {{ $page->title }}.
                </p>

                <p class="text-xs text-gray-500">
                    Gunakan tombol Simpan pada kolom masing-masing untuk menyimpan perubahan.
                </p>

            </div>

        @endif

    </div>


    {{-- KEMBALI --}}
    <div class="pt-2">

        <a
            href="{{ route('admin.pages.index') }}"
            class="inline-flex items-center gap-2 text-sm font-medium text-gray-600 transition hover:text-black"
        >
            &larr; Kembali ke Daftar Halaman
        </a>

    </div>

@endsection
