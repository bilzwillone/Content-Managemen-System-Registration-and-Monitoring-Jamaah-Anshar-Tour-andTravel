
@extends('admin.layouts.app')

@section('title', 'Kelola Halaman')

@section('page-heading', 'Kelola Halaman')

@section('content')

    {{-- Header Halaman --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            Kelola Halaman Website
        </h1>
        <p class="mt-2 text-sm text-gray-500">
            Atur dan kelola seluruh halaman website Ansar Travel.
        </p>
    </div>

    {{-- Tabel Halaman --}}
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

        <div class="border-b border-gray-200 px-6 py-5">
            <h2 class="text-lg font-semibold text-gray-800">
                Daftar Halaman
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Terdapat {{ $pages->count() }} halaman website.
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">

                <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-6 py-4">No.</th>
                        <th class="px-6 py-4">Nama Halaman</th>
                        <th class="px-6 py-4">Slug</th>
                        <th class="px-6 py-4">Jumlah Section</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">

                    @forelse ($pages as $page)
                        <tr class="hover:bg-gray-50">

                            <td class="px-6 py-4 text-gray-600">
                                {{ $loop->iteration }}
                            </td>

                            <td class="px-6 py-4 font-medium text-gray-800">
                                {{ $page->title }}
                            </td>

                            <td class="px-6 py-4 text-gray-500">
                                {{ $page->slug }}
                            </td>

                            <td class="px-6 py-4 text-gray-600">
                                {{ $page->sections_count }} Section
                            </td>

                            <td class="px-6 py-4">
                                @if ($page->is_published)
                                    <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">
                                        Published
                                    </span>
                                @else
                                    <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-600">
                                        Draft
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('admin.pages.sections', $page->id) }}"
                                class="inline-block rounded-lg bg-[#FFB22C] px-4 py-2 text-xs font-semibold text-black hover:opacity-80">
                                    Kelola Section
                                </a>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                Belum ada halaman website.
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>

@endsection
