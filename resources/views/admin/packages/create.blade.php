
@extends('admin.layouts.app')

@section('title', 'Tambah Paket Umroh')

@section('content')

<div class="mx-auto max-w-7xl space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
        <div>
            <a href="{{ route('admin.packages.index') }}"
               class="mb-2 inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-800">
                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17"
                     viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m15 18-6-6 6-6"/>
                </svg>
                Kembali ke daftar paket
            </a>

            <h1 class="text-2xl font-bold text-gray-800">
                Tambah Paket Umroh
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Lengkapi informasi paket perjalanan umroh Ansar Travel.
            </p>
        </div>
    </div>

    {{-- ERROR VALIDASI --}}
    @if ($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 p-4">
            <p class="mb-2 text-sm font-semibold text-red-700">
                Periksa kembali data yang Anda masukkan.
            </p>

            <ul class="list-inside list-disc space-y-1 text-sm text-red-600">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.packages.store') }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf

        {{-- LAYOUT DUA KOLOM --}}
        <div class="grid grid-cols-1 items-start gap-6 lg:grid-cols-3">

            {{-- KOLOM KIRI: INFORMASI PAKET --}}
            <div class="space-y-6 lg:col-span-2">

                <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                    <div class="border-b border-gray-100 px-6 py-4">
                        <h2 class="font-semibold text-gray-800">
                            Informasi Paket
                        </h2>
                        <p class="mt-1 text-xs text-gray-500">
                            Informasi dasar perjalanan dan fasilitas paket umroh.
                        </p>
                    </div>

                    <div class="space-y-5 p-6">

                        {{-- NAMA PAKET --}}
                        <div>
                            <label for="name" class="mb-2 block text-sm font-medium text-gray-700">
                                Nama Paket <span class="text-red-500">*</span>
                            </label>

                            <input type="text"
                                   name="name"
                                   id="name"
                                   value="{{ old('name') }}"
                                   placeholder="Contoh: Paket Umroh Reguler 12 Hari"
                                   required
                                   class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100">
                        </div>

                        {{-- HARGA DAN DURASI --}}
                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                            <div>
                                <label for="price" class="mb-2 block text-sm font-medium text-gray-700">
                                    Harga Paket (Rp)
                                </label>

                                <input type="number"
                                       name="price"
                                       id="price"
                                       value="{{ old('price') }}"
                                       min="0"
                                       step="1000"
                                       placeholder="Contoh: 35000000"
                                       class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100">

                                <p class="mt-1 text-xs text-gray-400">
                                    Masukkan nominal tanpa titik.
                                </p>
                            </div>

                            <div>
                                <label for="duration_days" class="mb-2 block text-sm font-medium text-gray-700">
                                    Durasi Perjalanan
                                </label>

                                <div class="relative">
                                    <input type="number"
                                           name="duration_days"
                                           id="duration_days"
                                           value="{{ old('duration_days') }}"
                                           min="1"
                                           placeholder="Contoh: 12"
                                           class="w-full rounded-lg border border-gray-300 px-4 py-3 pr-16 text-sm outline-none transition focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100">

                                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs text-gray-400">
                                        Hari
                                    </span>
                                </div>
                            </div>

                        </div>

                        {{-- TANGGAL DAN MASKAPAI --}}
                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                            <div>
                                <label for="departure_date" class="mb-2 block text-sm font-medium text-gray-700">
                                    Tanggal Keberangkatan
                                </label>

                                <input type="date"
                                       name="departure_date"
                                       id="departure_date"
                                       value="{{ old('departure_date') }}"
                                       class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100">
                            </div>

                            <div>
                                <label for="airline" class="mb-2 block text-sm font-medium text-gray-700">
                                    Maskapai Penerbangan
                                </label>

                                <input type="text"
                                       name="airline"
                                       id="airline"
                                       value="{{ old('airline') }}"
                                       placeholder="Contoh: Saudi Airlines"
                                       class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100">
                            </div>

                        </div>

                        {{-- HOTEL --}}
                        <div>
                            <label for="hotel" class="mb-2 block text-sm font-medium text-gray-700">
                                Hotel
                            </label>

                            <input type="text"
                                   name="hotel"
                                   id="hotel"
                                   value="{{ old('hotel') }}"
                                   placeholder="Contoh: Hotel Makkah dan Madinah"
                                   class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100">
                        </div>

                        {{-- DESKRIPSI --}}
                        <div>
                            <label for="description" class="mb-2 block text-sm font-medium text-gray-700">
                                Deskripsi Paket
                            </label>

                            <textarea name="description"
                                      id="description"
                                      rows="5"
                                      placeholder="Tuliskan informasi, fasilitas, dan keunggulan paket umroh..."
                                      class="w-full resize-y rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100">{{ old('description') }}</textarea>
                        </div>

                    </div>
                </div>

            </div>

            {{-- KOLOM KANAN: FOTO DAN PUBLIKASI --}}
            <div class="space-y-6">

                {{-- FOTO PAKET --}}
                <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                    <div class="border-b border-gray-100 px-5 py-4">
                        <h2 class="font-semibold text-gray-800">
                            Foto Paket
                        </h2>
                        <p class="mt-1 text-xs text-gray-500">
                            Gambar utama paket perjalanan.
                        </p>
                    </div>

                    <div class="p-5">

                        <label for="image"
                               class="flex min-h-44 cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 px-4 py-6 text-center transition hover:border-yellow-400 hover:bg-yellow-50">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 width="35" height="35"
                                 viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor"
                                 stroke-width="1.5" stroke-linecap="round"
                                 stroke-linejoin="round"
                                 class="mb-3 text-gray-400">
                                <rect x="3" y="3" width="18" height="18" rx="2"/>
                                <circle cx="8.5" cy="8.5" r="1.5"/>
                                <path d="m21 15-5-5L5 21"/>
                            </svg>

                            <span class="text-sm font-semibold text-gray-700">
                                Klik untuk memilih gambar
                            </span>

                            <span class="mt-1 text-xs text-gray-400">
                                JPG, PNG, atau WebP
                            </span>

                            <span class="mt-1 text-xs text-gray-400">
                                Maksimal 2 MB
                            </span>

                        </label>

                        <input type="file"
                               name="image"
                               id="image"
                               accept="image/*"
                               class="hidden">

                        <div id="image-preview-container" class="mt-4 hidden">
                            <img id="image-preview"
                                 src=""
                                 alt="Preview foto paket"
                                 class="max-h-48 w-full rounded-lg object-cover">

                            <button type="button"
                                    id="remove-image"
                                    class="mt-2 text-xs font-medium text-red-600 hover:text-red-700">
                                Hapus gambar
                            </button>
                        </div>

                    </div>
                </div>

                {{-- PENGATURAN PUBLIKASI --}}
                <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                    <div class="border-b border-gray-100 px-5 py-4">
                        <h2 class="font-semibold text-gray-800">
                            Pengaturan Publikasi
                        </h2>
                        <p class="mt-1 text-xs text-gray-500">
                            Atur status dan tampilan paket.
                        </p>
                    </div>

                    <div class="space-y-5 p-5">

                        {{-- STATUS --}}
                        <div>
                            <label for="status" class="mb-2 block text-sm font-medium text-gray-700">
                                Status Paket
                            </label>

                            <select name="status"
                                    id="status"
                                    required
                                    class="w-full rounded-lg border border-gray-300 px-3 py-3 text-sm outline-none transition focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100">

                                <option value="draft" {{ old('status', 'draft') === 'draft' ? 'selected' : '' }}>
                                    Draft
                                </option>

                                <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>
                                    Published
                                </option>

                            </select>
                        </div>

                        <div class="border-t border-gray-100"></div>

                        {{-- PAKET UNGGULAN --}}
                        <label class="flex cursor-pointer items-start gap-3">

                            <input type="checkbox"
                                   name="is_featured"
                                   value="1"
                                   {{ old('is_featured') ? 'checked' : '' }}
                                   class="mt-1 h-4 w-4 rounded border-gray-300 text-yellow-500 focus:ring-yellow-400">

                            <span>
                                <span class="block text-sm font-medium text-gray-800">
                                    Paket Unggulan
                                </span>
                                <span class="mt-1 block text-xs leading-relaxed text-gray-500">
                                    Tampilkan paket pada bagian unggulan di halaman beranda.
                                </span>
                            </span>

                        </label>

                        <div class="border-t border-gray-100"></div>

                        {{-- VISIBILITAS --}}
                        <label class="flex cursor-pointer items-start gap-3">

                            <input type="checkbox"
                                   name="is_visible"
                                   value="1"
                                   {{ old('is_visible', true) ? 'checked' : '' }}
                                   class="mt-1 h-4 w-4 rounded border-gray-300 text-yellow-500 focus:ring-yellow-400">

                            <span>
                                <span class="block text-sm font-medium text-gray-800">
                                    Tampilkan di Website
                                </span>
                                <span class="mt-1 block text-xs leading-relaxed text-gray-500">
                                    Izinkan pengunjung melihat paket ini.
                                </span>
                            </span>

                        </label>

                    </div>
                </div>

            </div>

        </div>

        {{-- TOMBOL AKSI --}}
        <div class="mt-6 flex flex-col-reverse justify-end gap-3 sm:flex-row">

            <a href="{{ route('admin.packages.index') }}"
               class="rounded-lg border border-gray-300 bg-white px-6 py-3 text-center text-sm font-semibold text-gray-600 transition hover:bg-gray-50">
                Batal
            </a>

            <button type="submit"
                    class="rounded-lg bg-[#FDD52E] px-7 py-3 text-sm font-semibold text-gray-900 shadow-sm transition hover:bg-[#eac323]">
                Simpan Paket
            </button>

        </div>

    </form>

</div>

{{-- PREVIEW GAMBAR --}}
<script>
    const imageInput = document.getElementById('image');
    const previewContainer = document.getElementById('image-preview-container');
    const imagePreview = document.getElementById('image-preview');
    const removeImage = document.getElementById('remove-image');

    imageInput.addEventListener('change', function () {
        const file = this.files[0];

        if (file) {
            imagePreview.src = URL.createObjectURL(file);
            previewContainer.classList.remove('hidden');
        }
    });

    removeImage.addEventListener('click', function () {
        imageInput.value = '';
        imagePreview.src = '';
        previewContainer.classList.add('hidden');
    });
</script>

@endsection
