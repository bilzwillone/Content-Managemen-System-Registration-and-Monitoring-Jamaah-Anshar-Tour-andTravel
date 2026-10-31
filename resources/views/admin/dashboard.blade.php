
@extends('admin.layouts.app')

@section('title', 'Dashboard Admin')

@section('page-heading', 'Dashboard')

@section('content')

    {{-- Welcome --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">
            Selamat Datang, {{ auth()->user()->name }}
        </h1>
        <p class="mt-2 text-sm text-gray-500">
            Kelola konten dan informasi website Ansar Travel melalui halaman ini.
        </p>
    </div>

    {{-- Statistik --}}
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-4">

        {{-- Halaman Website --}}
        <div class="rounded-xl border-l-4 border-l-[#FFB22C] bg-white p-6 shadow-sm">
            <p class="text-sm font-medium text-gray-500">Halaman Website</p>
            <h2 class="mt-3 text-3xl font-bold text-black">
                {{ \App\Models\Page::count() }}
            </h2>
            <p class="mt-2 text-sm text-gray-400">Total halaman website</p>
        </div>

        {{-- Paket Umroh --}}
        <div class="rounded-xl border-l-4 border-l-[#FFB22C] bg-white p-6 shadow-sm">
            <p class="text-sm font-medium text-gray-500">Paket Umroh</p>
            <h2 class="mt-3 text-3xl font-bold text-black">
                {{ \App\Models\Package::count() }}
            </h2>
            <p class="mt-2 text-sm text-gray-400">Total paket terdaftar</p>
        </div>

        {{-- Testimoni --}}
        <div class="rounded-xl border-l-4 border-l-[#FFB22C] bg-white p-6 shadow-sm">
            <p class="text-sm font-medium text-gray-500">Testimoni</p>
            <h2 class="mt-3 text-3xl font-bold text-black">
                {{ \App\Models\Testimonial::count() }}
            </h2>
            <p class="mt-2 text-sm text-gray-400">Total testimoni jamaah</p>
        </div>

        {{-- Galeri --}}
        <div class="rounded-xl border-l-4 border-l-[#FFB22C] bg-white p-6 shadow-sm">
            <p class="text-sm font-medium text-gray-500">Galeri Foto</p>
            <h2 class="mt-3 text-3xl font-bold text-black">
                {{ \App\Models\Gallery::count() }}
            </h2>
            <p class="mt-2 text-sm text-gray-400">Total dokumentasi foto</p>
        </div>

    </div>

    {{-- Informasi CMS --}}
    <div class="mt-8 rounded-xl bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold text-gray-800">
            Pengelolaan Website
        </h2>

        <p class="mt-2 text-sm leading-6 text-gray-500">
            Melalui panel Admin Ansar Travel, Anda dapat mengelola halaman
            website, mengatur section konten, memperbarui paket umroh,
            mengelola testimoni jamaah, dan mengatur galeri foto.
        </p>
    </div>

@endsection
