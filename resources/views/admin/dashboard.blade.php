@extends('admin.layouts.app')

@section('title', 'Dashboard Admin')
@section('page-heading', 'Dashboard')

@section('content')
<div class="space-y-8">
    <section class="relative overflow-hidden rounded-2xl bg-[#171717] p-7 text-white shadow-sm">
        <div class="absolute -right-10 -top-14 h-48 w-48 rounded-full bg-[#FDD52E]/10"></div>
        <div class="relative">
            <p class="text-sm font-medium text-[#FDD52E]">ADMIN PANEL · ANSAR TRAVEL</p>
            <h1 class="mt-3 text-2xl font-bold md:text-3xl">
                Selamat Datang, {{ auth()->user()->name }}
            </h1>
            <p class="mt-2 max-w-2xl text-sm leading-6 text-gray-300">
                Kelola konten dan informasi website Ansar Travel melalui ringkasan dashboard ini.
            </p>
        </div>
    </section>

    <section>
        <div class="mb-4 flex flex-wrap items-end justify-between gap-2">
            <div>
                <h2 class="text-lg font-bold text-gray-900">Ringkasan Website</h2>
                <p class="mt-1 text-sm text-gray-500">Ikhtisar data yang dikelola melalui panel admin.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-2xl border border-gray-200 border-t-4 border-t-[#FFB22C] bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-gray-500">Halaman Website</p>
                <p class="mt-4 text-3xl font-bold text-gray-900">{{ \App\Models\Page::count() }}</p>
                <p class="mt-2 text-xs text-gray-400">Total halaman website</p>
            </div>
            <div class="rounded-2xl border border-gray-200 border-t-4 border-t-[#FFB22C] bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-gray-500">Paket Umroh</p>
                <p class="mt-4 text-3xl font-bold text-gray-900">{{ \App\Models\Package::count() }}</p>
                <p class="mt-2 text-xs text-gray-400">Total paket terdaftar</p>
            </div>
            <div class="rounded-2xl border border-gray-200 border-t-4 border-t-[#FFB22C] bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-gray-500">Testimoni</p>
                <p class="mt-4 text-3xl font-bold text-gray-900">{{ \App\Models\Testimonial::count() }}</p>
                <p class="mt-2 text-xs text-gray-400">Total testimoni jamaah</p>
            </div>
            <div class="rounded-2xl border border-gray-200 border-t-4 border-t-[#FFB22C] bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-gray-500">Galeri Foto</p>
                <p class="mt-4 text-3xl font-bold text-gray-900">{{ \App\Models\Gallery::count() }}</p>
                <p class="mt-2 text-xs text-gray-400">Total dokumentasi foto</p>
            </div>
        </div>
    </section>

    <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <div class="flex items-center gap-3">
            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#FDD52E]/20 text-xl">✦</div>
            <div>
                <h2 class="font-bold text-gray-900">Pengelolaan Website</h2>
                <p class="mt-1 text-sm text-gray-500">Pusat pengelolaan konten Ansar Travel.</p>
            </div>
        </div>
        <p class="mt-4 max-w-3xl text-sm leading-6 text-gray-600">
            Melalui panel Admin Ansar Travel, Anda dapat mengelola halaman website,
            mengatur section konten, memperbarui paket umroh, mengelola testimoni jamaah,
            dan mengatur galeri foto.
        </p>
    </section>
</div>
@endsection
