
@extends('admin.layouts.app')

@section('title', 'Edit Paket Umroh')

@section('content')

<div class="mx-auto max-w-7xl space-y-6">

    {{-- HEADER --}}
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
            Edit Paket Umroh
        </h1>

        <p class="mt-1 text-sm text-gray-500">
            Perbarui informasi paket perjalanan umroh Ansar Travel.
        </p>
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

    <form action="{{ route('admin.packages.update', $package) }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 items-start gap-6 lg:grid-cols-3">

            {{-- KOLOM KIRI --}}
            <div class="space-y-6 lg:col-span-2">

                <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                    <div class="border-b border-gray-100 px-6 py-4">
                        <h2 class="font-semibold text-gray-800">
                            Informasi Paket
                        </h2>
                        <p class="mt-1 text-xs text-gray-500">
                            Perbarui informasi perjalanan dan fasilitas paket.
                        </p>
                    </div>

                    <div class="space-y-5 p-6">

                        {{-- NAMA --}}
                        <div>
                            <label for="name" class="mb-2 block text-sm font-medium text-gray-700">
                                Nama Paket <span class="text-red-500">*</span>
                            </label>

                            <input type="text"
                                   name="name"
                                   id="name"
                                   value="{{ old('name', $package->name) }}"
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
                                       value="{{ old('price', $package->price) }}"
                                       min="0"
                                       step="1000"
                                       placeholder="Contoh: 35000000"
                                       class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100">
                            </div>

                            <div>
                                <label for="duration_days" class="mb-2 block text-sm font-medium text-gray-700">
                                    Durasi Perjalanan
                                </label>

                                <div class="relative">
                                    <input type="number"
                                           name="duration_days"
                                           id="duration_days"
                                           value="{{ old('duration_days', $package->duration_days) }}"
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
                                       value="{{ old('departure_date', $package->departure_date?->format('Y-m-d')) }}"
                                       class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100">
                            </div>

                            <div>
                                <label for="airline" class="mb-2 block text-sm font-medium text-gray-700">
                                    Maskapai Penerbangan
                                </label>

                                <input type="text"
                                       name="airline"
                                       id="airline"
                                       value="{{ old('airline', $package->airline) }}"
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
                                   value="{{ old('hotel', $package->hotel) }}"
                                   placeholder="Nama hotel Makkah dan Madinah"
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
                                      placeholder="Tuliskan informasi dan fasilitas paket..."
                                      class="w-full resize-y rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100">{{ old('description', $package->description) }}</textarea>
                        </div>

                    </div>
                </div>

            </div>

            {{-- KOLOM KANAN --}}
            <div class="space-y-6">

                {{-- FOTO --}}
                <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                    <div class="border-b border-gray-100 px-5 py-4">
                        <h2 class="font-semibold text-gray-800">
                            Foto Paket
                        </h2>
                        <p class="mt-1 text-xs text-gray-500">
                            Ganti gambar jika diperlukan.
                        </p>
                    </div>

                    <div class="p-5">

                        @if ($package->image_path)
                            <div id="current-image-container">
                                <p class="mb-2 text-xs font-medium text-gray-500">
                                    Foto saat ini
                                </p>

                                <img
                                    src="{{ asset('storage/' . $package->image_path) }}"
                                    alt="{{ $package->name }}"
                                    class="mb-4 h-40 w-full rounded-lg object-cover"
                                >
                            </div>
                        @endif

                        <label for="image"
                               class="flex min-h-36 cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 px-4 py-6 text-center transition hover:border-yellow-400 hover:bg-yellow-50">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 width="32" height="32"
                                 viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor"
                                 stroke-width="1.5" stroke-linecap="round"
                                 stroke-linejoin="round"
                                 class="mb-2 text-gray-400">
                                <rect x="3" y="3" width="18" height="18" rx="2"/>
                                <circle cx="8.5" cy="8.5" r="1.5"/>
                                <path d="m21 15-5-5L5 21"/>
                            </svg>

                            <span class="text-sm font-semibold text-gray-700">
                                Pilih foto baru
                            </span>

                            <span class="mt-1 text-xs text-gray-400">
                                JPG, PNG, atau WebP. Maksimal 2 MB.
                            </span>

                        </label>

                        <input type="file"
                               name="image"
                               id="image"
                               accept="image/*"
                               class="hidden">

                        <div id="image-preview-container" class="mt-4 hidden">
                            <p class="mb-2 text-xs font-medium text-gray-500">
                                Preview foto baru
                            </p>

                            <img id="image-preview"
                                 src=""
                                 alt="Preview foto baru"
                                 class="max-h-48 w-full rounded-lg object-cover">

                            <button type="button"
                                    id="remove-image"
                                    class="mt-2 text-xs font-medium text-red-600 hover:text-red-700">
                                Batalkan foto baru
                            </button>
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
                            Atur status dan tampilan paket.
                        </p>
                    </div>

                    <div class="space-y-5 p-5">

                        <div>
                            <label for="status" class="mb-2 block text-sm font-medium text-gray-700">
                                Status Paket
                            </label>

                            <select name="status"
                                    id="status"
                                    required
                                    class="w-full rounded-lg border border-gray-300 px-3 py-3 text-sm outline-none transition focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100">

                                <option value="draft" {{ old('status', $package->status) === 'draft' ? 'selected' : '' }}>
                                    Draft
                                </option>

                                <option value="published" {{ old('status', $package->status) === 'published' ? 'selected' : '' }}>
                                    Published
                                </option>

                            </select>
                        </div>

                        <div class="border-t border-gray-100"></div>

                        <label class="flex cursor-pointer items-start gap-3">
                            <input type="checkbox"
                                   name="is_featured"
                                   value="1"
                                   {{ old('is_featured', $package->is_featured) ? 'checked' : '' }}
                                   class="mt-1 h-4 w-4 rounded border-gray-300 text-yellow-500 focus:ring-yellow-400">

                            <span>
                                <span class="block text-sm font-medium text-gray-800">
                                    Paket Unggulan
                                </span>
                                <span class="mt-1 block text-xs leading-relaxed text-gray-500">
                                    Tampilkan pada bagian paket unggulan di beranda.
                                </span>
                            </span>
                        </label>

                        <div class="border-t border-gray-100"></div>

                        <label class="flex cursor-pointer items-start gap-3">
                            <input type="checkbox"
                                   name="is_visible"
                                   value="1"
                                   {{ old('is_visible', $package->is_visible) ? 'checked' : '' }}
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

        {{-- TOMBOL --}}
        <div class="mt-6 flex flex-col-reverse justify-end gap-3 sm:flex-row">

            <a href="{{ route('admin.packages.index') }}"
               class="rounded-lg border border-gray-300 bg-white px-6 py-3 text-center text-sm font-semibold text-gray-600 hover:bg-gray-50">
                Batal
            </a>

            <button type="submit"
                    class="rounded-lg bg-[#FDD52E] px-7 py-3 text-sm font-semibold text-gray-900 shadow-sm transition hover:bg-[#eac323]">
                Simpan Perubahan
            </button>

        </div>

    </form>

</div>

{{-- PREVIEW FOTO BARU --}}
<script>
    const imageInput = document.getElementById('image');
    const previewContainer = document.getElementById('image-preview-container');
    const imagePreview = document.getElementById('image-preview');
    const removeImage = document.getElementById('remove-image');

    let previewUrl = null;

    imageInput.addEventListener('change', function () {
        const file = this.files[0];

        if (previewUrl) {
            URL.revokeObjectURL(previewUrl);
        }

        if (file) {
            previewUrl = URL.createObjectURL(file);
            imagePreview.src = previewUrl;
            previewContainer.classList.remove('hidden');
        }
    });

    removeImage.addEventListener('click', function () {
        imageInput.value = '';
        imagePreview.src = '';
        previewContainer.classList.add('hidden');

        if (previewUrl) {
            URL.revokeObjectURL(previewUrl);
            previewUrl = null;
        }
    });
</script>

@endsection
