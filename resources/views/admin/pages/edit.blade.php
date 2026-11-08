
@extends('admin.layouts.app')

@section('title', 'Edit Halaman')

@section('page-heading', 'Edit Halaman')

@section('content')

<div class="mx-auto max-w-7xl space-y-6">

    {{-- ERROR VALIDASI --}}
    @if ($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 p-4">
            <p class="mb-2 text-sm font-semibold text-red-700">
                Periksa kembali data yang dimasukkan.
            </p>

            <ul class="list-inside list-disc space-y-1 text-sm text-red-600">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.pages.update', $page->id) }}" method="POST">

        @csrf
        @method('PUT')

        {{-- HEADER --}}
        <div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">

            <div>
                <a
                    href="{{ route('admin.pages.index') }}"
                    class="mb-2 inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-800"
                >
                    &larr; Kembali ke daftar halaman
                </a>

                <h1 class="text-2xl font-bold text-gray-800">
                    Edit Halaman Website
                </h1>

                <p class="mt-1 text-sm text-gray-500">
                    Perbarui informasi halaman {{ $page->title }}.
                </p>
            </div>

            <div class="flex items-center gap-3">

                <a
                    href="{{ route('admin.pages.index') }}"
                    class="rounded-lg border border-gray-300 bg-white px-5 py-3 text-sm font-semibold text-gray-600 hover:bg-gray-50"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="rounded-lg bg-[#FDD52E] px-6 py-3 text-sm font-semibold text-gray-900 shadow-sm hover:bg-[#eac323]"
                >
                    Simpan Perubahan
                </button>

            </div>

        </div>

        {{-- LAYOUT DUA KOLOM --}}
        <div class="grid grid-cols-1 items-start gap-6 lg:grid-cols-3">

            {{-- KOLOM KIRI --}}
            <div class="space-y-6 lg:col-span-2">

                {{-- INFORMASI HALAMAN --}}
                <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                    <div class="border-b border-gray-100 px-6 py-4">
                        <h2 class="font-semibold text-gray-800">
                            Informasi Halaman
                        </h2>

                        <p class="mt-1 text-xs text-gray-500">
                            Perbarui identitas dan deskripsi halaman website.
                        </p>
                    </div>

                    <div class="space-y-5 p-6">

                        {{-- JUDUL DAN SLUG --}}
                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                            <div>
                                <label for="title" class="mb-2 block text-sm font-medium text-gray-700">
                                    Nama Halaman <span class="text-red-500">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="title"
                                    id="title"
                                    value="{{ old('title', $page->title) }}"
                                    required
                                    class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100"
                                >
                            </div>

                            <div>
                                <label for="slug" class="mb-2 block text-sm font-medium text-gray-700">
                                    URL / Slug
                                </label>

                                <input
                                    type="text"
                                    name="slug"
                                    id="slug"
                                    value="{{ old('slug', $page->slug) }}"
                                    class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100"
                                >

                                <p class="mt-1 text-[11px] text-gray-400">
                                    Contoh: promo-umroh.
                                </p>
                            </div>

                        </div>

                        {{-- DESKRIPSI --}}
                        <div>
                            <label for="description" class="mb-2 block text-sm font-medium text-gray-700">
                                Deskripsi Halaman
                            </label>

                            <textarea
                                name="description"
                                id="description"
                                rows="4"
                                class="w-full resize-y rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100"
                            >{{ old('description', $page->description) }}</textarea>
                        </div>

                    </div>

                </div>

                {{-- SEO --}}
                <details class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                    <summary class="cursor-pointer px-6 py-4 transition hover:bg-gray-50">
                        <span class="font-semibold text-gray-800">
                            Pengaturan SEO
                        </span>

                        <p class="mt-1 text-xs text-gray-500">
                            Meta title dan meta description untuk mesin pencari.
                        </p>
                    </summary>

                    <div class="space-y-5 border-t border-gray-100 p-6">

                        <div>
                            <label for="meta_title" class="mb-2 block text-sm font-medium text-gray-700">
                                Meta Title
                            </label>

                            <input
                                type="text"
                                name="meta_title"
                                id="meta_title"
                                value="{{ old('meta_title', $page->meta_title) }}"
                                class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100"
                            >
                        </div>

                        <div>
                            <label for="meta_description" class="mb-2 block text-sm font-medium text-gray-700">
                                Meta Description
                            </label>

                            <textarea
                                name="meta_description"
                                id="meta_description"
                                rows="3"
                                class="w-full resize-y rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100"
                            >{{ old('meta_description', $page->meta_description) }}</textarea>
                        </div>

                    </div>

                </details>

            </div>

            {{-- KOLOM KANAN --}}
            <div class="space-y-6">

                {{-- NAVIGASI --}}
                <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                    <div class="border-b border-gray-100 px-5 py-4">
                        <h2 class="font-semibold text-gray-800">
                            Pengaturan Navigasi
                        </h2>

                        <p class="mt-1 text-xs text-gray-500">
                            Atur tampilan halaman pada navbar.
                        </p>
                    </div>

                    <div class="space-y-5 p-5">

                        <label class="flex cursor-pointer items-start gap-3">

                            <input
                                type="checkbox"
                                name="show_in_navbar"
                                value="1"
                                {{ old('show_in_navbar', $page->show_in_navbar) ? 'checked' : '' }}
                                class="mt-1 h-4 w-4 rounded border-gray-300 text-yellow-500 focus:ring-yellow-400"
                            >

                            <span>
                                <span class="block text-sm font-medium text-gray-800">
                                    Tampilkan di Navbar
                                </span>

                                <span class="mt-1 block text-xs leading-relaxed text-gray-500">
                                    Tampilkan halaman pada navigasi website.
                                </span>
                            </span>

                        </label>

                        <div class="border-t border-gray-100"></div>

                        <div>
                            <label for="nav_order" class="mb-2 block text-sm font-medium text-gray-700">
                                Urutan Navbar
                            </label>

                            <input
                                type="number"
                                name="nav_order"
                                id="nav_order"
                                value="{{ old('nav_order', $page->nav_order) }}"
                                min="0"
                                class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100"
                            >

                        </div>

                    </div>
                </div>

                {{-- PUBLIKASI --}}
                <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                    <div class="border-b border-gray-100 px-5 py-4">
                        <h2 class="font-semibold text-gray-800">
                            Pengaturan Publikasi
                        </h2>

                        <p class="mt-1 text-xs text-gray-500">
                            Atur status halaman.
                        </p>
                    </div>

                    <div class="p-5">

                        <label class="flex cursor-pointer items-start gap-3">

                            <input
                                type="checkbox"
                                name="is_published"
                                value="1"
                                {{ old('is_published', $page->is_published) ? 'checked' : '' }}
                                class="mt-1 h-4 w-4 rounded border-gray-300 text-yellow-500 focus:ring-yellow-400"
                            >

                            <span>
                                <span class="block text-sm font-medium text-gray-800">
                                    Publikasikan Halaman
                                </span>

                                <span class="mt-1 block text-xs leading-relaxed text-gray-500">
                                    Izinkan pengunjung mengakses halaman ini.
                                </span>
                            </span>

                        </label>

                    </div>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection
