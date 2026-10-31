
@extends('admin.layouts.app')

@section('title', 'Kelola Section')

@section('page-heading', 'Kelola Section')

@section('content')

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

        <a href="#"
           class="inline-block rounded-lg bg-[#FFB22C] px-5 py-3 text-sm font-semibold text-black hover:opacity-80">
            + Tambah Section
        </a>
    </div>

    {{-- Daftar Section --}}
    <div class="space-y-4">

        @forelse ($sections as $section)

            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">

                <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">

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

                    <div class="flex flex-wrap gap-2">

                        <a href="#"
                           class="rounded-lg bg-[#FFB22C] px-4 py-2 text-xs font-semibold text-black hover:opacity-80">
                            Edit
                        </a>

                        <button type="button"
                                class="rounded-lg bg-gray-200 px-4 py-2 text-xs font-semibold text-gray-700">
                            Atur Urutan
                        </button>

                    </div>

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
