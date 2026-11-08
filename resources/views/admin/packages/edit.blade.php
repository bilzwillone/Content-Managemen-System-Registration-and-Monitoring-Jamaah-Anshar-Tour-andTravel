
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

    {{-- NOTIFIKASI BERHASIL --}}
    @if (session('success'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

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

    {{-- FORM HAPUS FOTO GALERI --}}
    {{-- Diletakkan di luar form utama agar tidak terjadi form bersarang --}}
    @foreach ($package->images as $galleryImage)
        <form
            id="delete-gallery-image-{{ $galleryImage->id }}"
            action="{{ route('admin.packages.images.destroy', [$package, $galleryImage]) }}"
            method="POST"
            class="hidden"
        >
            @csrf
            @method('DELETE')
        </form>
    @endforeach


    {{-- FORM UTAMA EDIT PAKET --}}
    <form action="{{ route('admin.packages.update', $package) }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 items-start gap-6 lg:grid-cols-3">

            {{-- KOLOM KIRI: INFORMASI PAKET --}}
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

                        {{-- NAMA PAKET --}}
                        <div>
                            <label for="name" class="mb-2 block text-sm font-medium text-gray-700">
                                Nama Paket <span class="text-red-500">*</span>
                            </label>

                            <input
                                type="text"
                                name="name"
                                id="name"
                                value="{{ old('name', $package->name) }}"
                                required
                                class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100"
                            >
                        </div>

                        {{-- HARGA DAN DURASI --}}
                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                            <div>
                                <label for="price" class="mb-2 block text-sm font-medium text-gray-700">
                                    Harga Paket (Rp)
                                </label>

                                <input
                                    type="number"
                                    name="price"
                                    id="price"
                                    value="{{ old('price', $package->price) }}"
                                    min="0"
                                    step="1000"
                                    placeholder="Contoh: 35000000"
                                    class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100"
                                >
                            </div>

                            <div>
                                <label for="duration_days" class="mb-2 block text-sm font-medium text-gray-700">
                                    Durasi Perjalanan
                                </label>

                                <div class="relative">
                                    <input
                                        type="number"
                                        name="duration_days"
                                        id="duration_days"
                                        value="{{ old('duration_days', $package->duration_days) }}"
                                        min="1"
                                        placeholder="Contoh: 12"
                                        class="w-full rounded-lg border border-gray-300 px-4 py-3 pr-16 text-sm outline-none transition focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100"
                                    >

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

                                <input
                                    type="date"
                                    name="departure_date"
                                    id="departure_date"
                                    value="{{ old('departure_date', $package->departure_date?->format('Y-m-d')) }}"
                                    class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100"
                                >
                            </div>

                            <div>
                                <label for="airline" class="mb-2 block text-sm font-medium text-gray-700">
                                    Maskapai Penerbangan
                                </label>

                                <input
                                    type="text"
                                    name="airline"
                                    id="airline"
                                    value="{{ old('airline', $package->airline) }}"
                                    placeholder="Contoh: Saudi Airlines"
                                    class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100"
                                >
                            </div>

                        </div>

                        {{-- HOTEL LAMA --}}
                        <div>
                            <label for="hotel" class="mb-2 block text-sm font-medium text-gray-700">
                                Hotel
                            </label>

                            <input
                                type="text"
                                name="hotel"
                                id="hotel"
                                value="{{ old('hotel', $package->hotel) }}"
                                placeholder="Nama hotel Makkah dan Madinah"
                                class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100"
                            >
                        </div>

                        {{-- DESKRIPSI --}}
                        <div>
                            <label for="description" class="mb-2 block text-sm font-medium text-gray-700">
                                Deskripsi Paket
                            </label>

                            <textarea
                                name="description"
                                id="description"
                                rows="5"
                                placeholder="Tuliskan informasi dan fasilitas paket..."
                                class="w-full resize-y rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100"
                            >{{ old('description', $package->description) }}</textarea>
                        </div>

                        {{-- FASILITAS PAKET --}}
                        <div class="border-t border-gray-100 pt-5">

                            <label class="mb-3 block text-sm font-medium text-gray-700">
                                Fasilitas Paket
                            </label>

                            <p class="mb-3 text-xs text-gray-500">
                                Masukkan fasilitas yang termasuk dalam paket umroh.
                            </p>

                            <div class="space-y-3">
                                @for ($i = 0; $i < 6; $i++)
                                    <input
                                        type="text"
                                        name="facilities[]"
                                        value="{{ old('facilities.' . $i, $package->facilities[$i] ?? '') }}"
                                        placeholder="Contoh: Tiket pesawat pulang pergi"
                                        class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100"
                                    >
                                @endfor
                            </div>

                        </div>

                        {{-- AKOMODASI --}}
                        <div class="border-t border-gray-100 pt-5">

                            <h3 class="mb-4 font-semibold text-gray-800">
                                Akomodasi
                            </h3>

                            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                                <div>
                                    <label for="hotel_makkah" class="mb-2 block text-sm font-medium text-gray-700">
                                        Hotel Makkah
                                    </label>

                                    <input
                                        type="text"
                                        name="hotel_makkah"
                                        id="hotel_makkah"
                                        value="{{ old('hotel_makkah', $package->hotel_makkah) }}"
                                        placeholder="Nama hotel di Makkah"
                                        class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100"
                                    >
                                </div>

                                <div>
                                    <label for="hotel_madinah" class="mb-2 block text-sm font-medium text-gray-700">
                                        Hotel Madinah
                                    </label>

                                    <input
                                        type="text"
                                        name="hotel_madinah"
                                        id="hotel_madinah"
                                        value="{{ old('hotel_madinah', $package->hotel_madinah) }}"
                                        placeholder="Nama hotel di Madinah"
                                        class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100"
                                    >
                                </div>

                            </div>
                        </div>

                        {{-- KETERANGAN PENERBANGAN --}}
                        <div>
                            <label for="flight_note" class="mb-2 block text-sm font-medium text-gray-700">
                                Keterangan Penerbangan
                            </label>

                            <textarea
                                name="flight_note"
                                id="flight_note"
                                rows="3"
                                placeholder="Contoh: Penerbangan langsung dari Jakarta menuju Jeddah."
                                class="w-full resize-y rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100"
                            >{{ old('flight_note', $package->flight_note) }}</textarea>
                        </div>

                    </div>
                </div>

            </div>


            {{-- KOLOM KANAN --}}
            <div class="space-y-6">

                {{-- FOTO PAKET DAN GALERI --}}
                <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                    <div class="border-b border-gray-100 px-5 py-4">
                        <h2 class="font-semibold text-gray-800">
                            Foto Paket
                        </h2>

                        <p class="mt-1 text-xs text-gray-500">
                            Kelola foto utama dan foto tambahan paket.
                        </p>
                    </div>

                    <div class="space-y-6 p-5">

                        {{-- FOTO UTAMA --}}
                        <div>

                            <p class="mb-2 text-xs font-semibold text-gray-600">
                                Foto Utama Saat Ini
                            </p>

                            @if ($package->image_path)

                                <img
                                    src="{{ asset('storage/' . $package->image_path) }}"
                                    alt="{{ $package->name }}"
                                    class="mb-4 h-40 w-full rounded-lg object-cover"
                                >

                            @else

                                <div class="mb-4 flex h-36 items-center justify-center rounded-lg bg-gray-100 text-sm text-gray-400">
                                    Belum ada foto utama
                                </div>

                            @endif

                            <label
                                for="image"
                                class="flex min-h-32 cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 px-4 py-5 text-center transition hover:border-yellow-400 hover:bg-yellow-50"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     width="30" height="30"
                                     viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor"
                                     stroke-width="1.5">
                                    <rect x="3" y="3" width="18" height="18" rx="2"/>
                                    <circle cx="8.5" cy="8.5" r="1.5"/>
                                    <path d="m21 15-5-5L5 21"/>
                                </svg>

                                <span class="mt-2 text-sm font-semibold text-gray-700">
                                    Pilih foto utama baru
                                </span>

                                <span class="mt-1 text-xs text-gray-400">
                                    JPG, PNG, atau WebP. Maksimal 2 MB.
                                </span>
                            </label>

                            <input
                                type="file"
                                name="image"
                                id="image"
                                accept="image/*"
                                class="hidden"
                            >

                            {{-- PREVIEW FOTO UTAMA --}}
                            <div id="image-preview-container" class="mt-4 hidden">

                                <p class="mb-2 text-xs font-medium text-gray-500">
                                    Preview foto utama baru
                                </p>

                                <img
                                    id="image-preview"
                                    src=""
                                    alt="Preview foto baru"
                                    class="max-h-48 w-full rounded-lg object-cover"
                                >

                                <button
                                    type="button"
                                    id="remove-image"
                                    class="mt-2 text-xs font-medium text-red-600 hover:text-red-700"
                                >
                                    Batalkan foto baru
                                </button>

                            </div>

                        </div>


                        {{-- PEMBATAS --}}
                        <div class="border-t border-gray-100"></div>


                        {{-- FOTO GALERI TERSIMPAN --}}
                        <div>

                            <div class="mb-3">
                                <h3 class="text-sm font-semibold text-gray-800">
                                    Foto Galeri Tersimpan
                                </h3>

                                <p class="mt-1 text-xs leading-5 text-gray-500">
                                    Klik tanda silang pada foto untuk menghapusnya.
                                </p>
                            </div>

                            @if ($package->images->isNotEmpty())

                                <div class="grid grid-cols-2 gap-3">

                                    @foreach ($package->images as $galleryImage)

                                        <div class="relative overflow-hidden rounded-lg border border-gray-200 bg-white">

                                            <a
                                                href="{{ asset('storage/' . $galleryImage->image_path) }}"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                            >
                                                <img
                                                    src="{{ asset('storage/' . $galleryImage->image_path) }}"
                                                    alt="Foto galeri {{ $package->name }}"
                                                    class="h-28 w-full object-cover transition hover:opacity-80 sm:h-32"
                                                    loading="lazy"
                                                >
                                            </a>

                                            {{-- TOMBOL HAPUS FOTO --}}
                                            <button
                                                type="submit"
                                                form="delete-gallery-image-{{ $galleryImage->id }}"
                                                title="Hapus foto"
                                                aria-label="Hapus foto galeri"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus foto ini?')"
                                                class="absolute right-2 top-2 flex h-8 w-8 items-center justify-center rounded-full bg-red-600 text-white shadow-lg transition hover:scale-110 hover:bg-red-700"
                                            >
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    width="17"
                                                    height="17"
                                                    viewBox="0 0 24 24"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    stroke-width="2.5"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                >
                                                    <path d="M18 6 6 18"/>
                                                    <path d="m6 6 12 12"/>
                                                </svg>
                                            </button>

                                        </div>

                                    @endforeach

                                </div>

                            @else

                                <div class="rounded-lg border-2 border-dashed border-gray-200 px-4 py-8 text-center">
                                    <p class="text-sm font-medium text-gray-600">
                                        Belum ada foto galeri.
                                    </p>

                                    <p class="mt-1 text-xs text-gray-400">
                                        Foto tambahan yang diunggah akan muncul di sini.
                                    </p>
                                </div>

                            @endif

                        </div>


                        {{-- PEMBATAS --}}
                        <div class="border-t border-gray-100"></div>


                        {{-- TAMBAH FOTO GALERI --}}
                        <div>

                            <h3 class="mb-2 text-sm font-semibold text-gray-800">
                                Tambah Foto Galeri
                            </h3>

                            <p class="mb-3 text-xs leading-5 text-gray-500">
                                Pilih beberapa foto tambahan seperti hotel Makkah,
                                hotel Madinah, pesawat, atau dokumentasi perjalanan.
                                Maksimal 4 MB per foto.
                            </p>

                            <label
                                for="gallery-images"
                                class="flex min-h-32 cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 px-4 py-5 text-center transition hover:border-yellow-400 hover:bg-yellow-50"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     width="30" height="30"
                                     viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor"
                                     stroke-width="1.5">
                                    <rect x="3" y="3" width="18" height="18" rx="2"/>
                                    <circle cx="8.5" cy="8.5" r="1.5"/>
                                    <path d="m21 15-5-5L5 21"/>
                                </svg>

                                <span class="mt-2 text-sm font-semibold text-gray-700">
                                    Pilih beberapa foto
                                </span>

                                <span class="mt-1 text-xs text-gray-400">
                                    JPG, PNG, atau WebP
                                </span>
                            </label>

                            <input
                                type="file"
                                name="images[]"
                                id="gallery-images"
                                accept="image/jpeg,image/png,image/webp"
                                multiple
                                class="hidden"
                            >

                            {{-- PREVIEW GALERI BARU --}}
                            <div
                                id="gallery-preview"
                                class="mt-4 grid grid-cols-2 gap-3"
                            ></div>

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

                        <div>
                            <label for="status" class="mb-2 block text-sm font-medium text-gray-700">
                                Status Paket
                            </label>

                            <select
                                name="status"
                                id="status"
                                required
                                class="w-full rounded-lg border border-gray-300 px-3 py-3 text-sm outline-none transition focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100"
                            >
                                <option value="draft" {{ old('status', $package->status) === 'draft' ? 'selected' : '' }}>
                                    Draft
                                </option>

                                <option value="published" {{ old('status', $package->status) === 'published' ? 'selected' : '' }}>
                                    Published
                                </option>
                            </select>
                        </div>

                        <div class="border-t border-gray-100"></div>

                        {{-- PAKET UNGGULAN --}}
                        <label class="flex cursor-pointer items-start gap-3">

                            <input
                                type="checkbox"
                                name="is_featured"
                                value="1"
                                {{ old('is_featured', $package->is_featured) ? 'checked' : '' }}
                                class="mt-1 h-4 w-4 rounded border-gray-300 text-yellow-500 focus:ring-yellow-400"
                            >

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

                        {{-- VISIBILITAS --}}
                        <label class="flex cursor-pointer items-start gap-3">

                            <input
                                type="checkbox"
                                name="is_visible"
                                value="1"
                                {{ old('is_visible', $package->is_visible) ? 'checked' : '' }}
                                class="mt-1 h-4 w-4 rounded border-gray-300 text-yellow-500 focus:ring-yellow-400"
                            >

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


        {{-- TOMBOL AKSI: TETAP DI BAWAH FORM --}}
        <div class="mt-6 flex flex-col-reverse justify-end gap-3 sm:flex-row">

            <a
                href="{{ route('admin.packages.index') }}"
                class="rounded-lg border border-gray-300 bg-white px-6 py-3 text-center text-sm font-semibold text-gray-600 hover:bg-gray-50"
            >
                Batal
            </a>

            <button
                type="submit"
                class="rounded-lg bg-[#FDD52E] px-7 py-3 text-sm font-semibold text-gray-900 shadow-sm transition hover:bg-[#eac323]"
            >
                Simpan Perubahan
            </button>

        </div>

    </form>

</div>


{{-- JAVASCRIPT PREVIEW FOTO --}}
<script>

    // PREVIEW FOTO UTAMA
    const imageInput = document.getElementById('image');
    const previewContainer = document.getElementById('image-preview-container');
    const imagePreview = document.getElementById('image-preview');
    const removeImage = document.getElementById('remove-image');

    let previewUrl = null;

    imageInput.addEventListener('change', function () {

        const file = this.files[0];

        if (previewUrl) {
            URL.revokeObjectURL(previewUrl);
            previewUrl = null;
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


    // PREVIEW FOTO GALERI BARU
    const galleryInput = document.getElementById('gallery-images');
    const galleryPreview = document.getElementById('gallery-preview');

    let galleryPreviewUrls = [];

    galleryInput.addEventListener('change', function () {

        galleryPreviewUrls.forEach((url) => {
            URL.revokeObjectURL(url);
        });

        galleryPreviewUrls = [];
        galleryPreview.innerHTML = '';

        const files = Array.from(this.files || []);

        files.forEach((file, index) => {

            const imageUrl = URL.createObjectURL(file);
            galleryPreviewUrls.push(imageUrl);

            const wrapper = document.createElement('div');
            wrapper.className = 'overflow-hidden rounded-lg border border-gray-200 bg-white';

            const img = document.createElement('img');
            img.src = imageUrl;
            img.alt = 'Preview foto galeri ' + (index + 1);
            img.className = 'h-28 w-full object-cover';

            const caption = document.createElement('p');
            caption.className = 'truncate px-2 py-2 text-xs text-gray-500';
            caption.textContent = file.name;

            wrapper.appendChild(img);
            wrapper.appendChild(caption);

            galleryPreview.appendChild(wrapper);

        });

    });

</script>

@endsection
