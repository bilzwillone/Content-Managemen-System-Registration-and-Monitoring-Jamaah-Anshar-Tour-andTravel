
@extends('admin.layouts.app')

@section('title', 'Kelola Section')

@section('page-heading', 'Kelola Section')

@section('content')

    {{-- Notifikasi --}}
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

    {{-- Header --}}
    <div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Kelola Section {{ $page->title }}
            </h1>

            <p class="mt-2 text-sm text-gray-500">
                Atur konten dan tampilan setiap bagian halaman {{ $page->title }}.
            </p>
        </div>

        <a href="{{ route('admin.pages.sections.create', $page->id) }}"
           class="inline-block rounded-lg bg-[#FFB22C] px-5 py-3 text-sm font-semibold text-black hover:opacity-80">
            + Tambah Section
        </a>
    </div>

    {{-- Daftar Section --}}
    <div class="space-y-4">

        @forelse ($sections as $section)

            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">

                <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">

                    {{-- Informasi Section --}}
                    <div>
                        <div class="mb-2 flex items-center gap-3">
                            <span class="text-sm font-semibold text-gray-400">
                                #{{ $section->sort_order }}
                            </span>

                            <h2 class="text-lg font-semibold text-gray-800">
                                {{ $section->title ?? ucfirst($section->section_type) }}
                            </h2>
                        </div>

                        <p class="text-sm text-gray-500">
                            Tipe Section: {{ $section->section_type }}
                        </p>

                        {{-- Status dan Visibilitas --}}
                        <div class="mt-3 flex flex-wrap gap-2">

                            @if ($section->status === 'published')
                                <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">
                                    Published
                                </span>
                            @else
                                <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-600">
                                    Draft
                                </span>
                            @endif

                            @if ($section->is_visible)
                                <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-700">
                                    Ditampilkan
                                </span>
                            @else
                                <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-700">
                                    Disembunyikan
                                </span>
                            @endif

                        </div>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="flex flex-wrap gap-2">

                        {{-- Edit --}}
                        <a href="{{ route('admin.pages.sections.edit', [$page->id, $section->id]) }}"
                           class="rounded-lg bg-[#FFB22C] px-4 py-2 text-xs font-semibold text-black hover:opacity-80">
                            Edit
                        </a>

                        {{-- Hapus --}}
                        <form action="{{ route('admin.pages.sections.destroy', [$page->id, $section->id]) }}"
                              method="POST"
                              onsubmit="return confirm('Apakah kamu yakin ingin menghapus section ini? Data yang dihapus tidak dapat dikembalikan.')">

                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    class="rounded-lg bg-red-100 px-4 py-2 text-xs font-semibold text-red-700 hover:bg-red-200">
                                Hapus
                            </button>

                        </form>

                    </div>

                </div>

                {{-- Pengaturan Urutan --}}
                <div class="mt-6 border-t border-gray-100 pt-5">

                    <form action="{{ route('admin.pages.sections.order', [$page->id, $section->id]) }}"
                          method="POST"
                          class="flex flex-col gap-3 sm:flex-row sm:items-end">

                        @csrf
                        @method('PATCH')

                        <div class="flex-1">
                            <label class="mb-2 block text-xs font-semibold text-gray-600">
                                Urutan Section
                            </label>

                            <input type="number"
                                   name="sort_order"
                                   min="1"
                                   required
                                   value="{{ $section->sort_order }}"
                                   class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm">

                        </div>

                        <button type="submit"
                                class="rounded-lg bg-gray-800 px-5 py-2.5 text-sm font-semibold text-white hover:bg-gray-700">
                            Simpan Urutan
                        </button>

                    </form>

                </div>

                {{-- Pengaturan Status dan Visibilitas --}}
                <div class="mt-5 border-t border-gray-100 pt-5">

                    <h3 class="mb-4 text-sm font-semibold text-gray-800">
                        Pengaturan Publikasi
                    </h3>

                    <form action="{{ route('admin.pages.sections.settings', [$page->id, $section->id]) }}"
                          method="POST"
                          class="flex flex-col gap-4 sm:flex-row sm:items-end">

                        @csrf
                        @method('PATCH')

                        {{-- Status Publikasi --}}
                        <div class="flex-1">
                            <label class="mb-2 block text-xs font-semibold text-gray-600">
                                Status Publikasi
                            </label>

                            <select name="status"
                                    required
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm">

                                <option value="draft"
                                    {{ $section->status === 'draft' ? 'selected' : '' }}>
                                    Draft
                                </option>

                                <option value="published"
                                    {{ $section->status === 'published' ? 'selected' : '' }}>
                                    Published
                                </option>

                            </select>
                        </div>

                        {{-- Visibilitas --}}
                        <div class="flex-1">
                            <label class="mb-2 block text-xs font-semibold text-gray-600">
                                Visibilitas Section
                            </label>

                            <select name="is_visible"
                                    required
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm">

                                <option value="1"
                                    {{ $section->is_visible ? 'selected' : '' }}>
                                    Ditampilkan
                                </option>

                                <option value="0"
                                    {{ !$section->is_visible ? 'selected' : '' }}>
                                    Disembunyikan
                                </option>

                            </select>
                        </div>

                        <div>
                            <button type="submit"
                                    class="w-full rounded-lg bg-gray-800 px-5 py-2.5 text-sm font-semibold text-white hover:bg-gray-700">
                                Simpan Pengaturan
                            </button>
                        </div>

                    </form>

                </div>

            </div>

        @empty

            <div class="rounded-xl border border-gray-200 bg-white p-10 text-center">
                <h2 class="text-lg font-semibold text-gray-800">
                    Belum Ada Section
                </h2>

                <p class="mt-2 text-sm text-gray-500">
                    Halaman ini belum memiliki section konten.
                </p>
            </div>

        @endforelse

    </div>

    {{-- Tombol Kembali --}}
    <div class="mt-6">
        <a href="{{ route('admin.pages.index') }}"
           class="text-sm font-medium text-gray-600 hover:text-black">
            ← Kembali ke Daftar Halaman
        </a>
    </div>

@endsection
