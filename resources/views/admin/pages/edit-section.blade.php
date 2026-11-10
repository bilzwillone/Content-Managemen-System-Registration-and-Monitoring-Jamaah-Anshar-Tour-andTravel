
@extends('admin.layouts.app')

@section('title', 'Edit Section')
@section('page-heading', 'Edit Section')

@section('content')

@php
    // Mengambil data card yang tersimpan atau data input sebelumnya.
    $existingCards = old('cards', data_get($section->content, 'cards', []));

    if (empty($existingCards)) {
        $existingCards = [
            ['icon' => '', 'title' => '', 'description' => '']
        ];
    }

    // Mengambil data statistik.
    $existingStatistics = old('statistics', data_get($section->content, 'items', []));

    if (empty($existingStatistics)) {
        $existingStatistics = [
            ['value' => '', 'label' => '']
        ];
    }

    // Mengambil deskripsi.
    $existingDescription = old(
        'content',
        data_get($section->content, 'description', '')
    );

    // Mengambil data testimoni yang tersimpan.
    $storedTestimonials = data_get($section->content, 'items', []);
    $existingTestimonials = old('testimonials');

    // Jika validasi sebelumnya gagal, gabungkan input lama dengan foto dari database.
    if ($existingTestimonials !== null) {
        foreach ($existingTestimonials as $index => $testimonial) {
            foreach ($storedTestimonials as $storedTestimonial) {
                if (
                    !empty($testimonial['id']) &&
                    ($storedTestimonial['id'] ?? null) === $testimonial['id']
                ) {
                    $existingTestimonials[$index]['photo_path'] =
                        $storedTestimonial['photo_path'] ?? null;
                    break;
                }
            }
        }
    } else {
        $existingTestimonials = $storedTestimonials;
    }

    if (empty($existingTestimonials)) {
        $existingTestimonials = [
            ['id' => '', 'name' => '', 'origin' => '', 'rating' => 5, 'message' => '', 'photo_path' => null]
        ];
    }


    $storedGalleryItems = $section->section_type === 'gallery'
        ? data_get($section->content, 'items', [])
        : [];
    $existingGalleryItems = old('gallery');

    if ($existingGalleryItems !== null) {
        foreach ($existingGalleryItems as $index => $galleryItem) {
            foreach ($storedGalleryItems as $storedItem) {
                if (!empty($galleryItem['id']) && ($storedItem['id'] ?? null) === $galleryItem['id']) {
                    $existingGalleryItems[$index]['image_path'] = $storedItem['image_path'] ?? null;
                    break;
                }
            }
        }
    } else {
        $existingGalleryItems = $storedGalleryItems;
    }

    if (empty($existingGalleryItems)) {
        $existingGalleryItems = [
            ['id' => '', 'caption' => '', 'alt' => '', 'sort_order' => 1, 'image_path' => null]
        ];
    }

@endphp

<div class="mx-auto max-w-7xl space-y-6">

    {{-- HEADER --}}
    <div>
        <a href="{{ route('admin.pages.sections', $page->id) }}"
           class="mb-2 inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-800">
            &larr; Kembali ke daftar section
        </a>

        <h1 class="text-2xl font-bold text-gray-800">
            Edit Section {{ $page->title }}
        </h1>

        <p class="mt-1 text-sm text-gray-500">
            Perbarui informasi dan konten section {{ $page->title }}.
        </p>
    </div>

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


    {{-- FORM EDIT SECTION --}}
    <form
        action="{{ route('admin.pages.sections.update', [$page->id, $section->id]) }}"
        method="POST"
        enctype="multipart/form-data"
    >
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 items-start gap-6 lg:grid-cols-3">

            {{-- KOLOM KIRI --}}
            <div class="space-y-6 lg:col-span-2">

                {{-- INFORMASI SECTION --}}
                <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                    <div class="border-b border-gray-100 px-6 py-4">
                        <h2 class="font-semibold text-gray-800">
                            Informasi Section
                        </h2>

                        <p class="mt-1 text-xs text-gray-500">
                            Perbarui tipe dan informasi utama section.
                        </p>
                    </div>

                    <div class="space-y-5 p-6">

                        {{-- TIPE SECTION --}}
                        <div>
                            <label for="section_type" class="mb-2 block text-sm font-semibold text-gray-700">
                                Tipe Section <span class="text-red-500">*</span>
                            </label>

                            <select
                                name="section_type"
                                id="section_type"
                                required
                                class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100"
                            >
                                @foreach ([
                                    'hero' => 'Hero',
                                    'statistics' => 'Statistik',
                                    'about' => 'Tentang Kami',
                                    'why_choose_us' => 'Mengapa Memilih Ansar Travel?',
                                    'services' => 'Layanan',
                                    'packages' => 'Paket Umroh',
                                    'testimonials' => 'Testimoni',
                                    'gallery' => 'Galeri',
                                    'contact' => 'Kontak',
                                ] as $value => $label)

                                    <option
                                        value="{{ $value }}"
                                        {{ old('section_type', $section->section_type) === $value ? 'selected' : '' }}
                                    >
                                        {{ $label }}
                                    </option>

                                @endforeach
                            </select>

                            @error('section_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- JUDUL --}}
                        <div>
                            <label for="title" class="mb-2 block text-sm font-semibold text-gray-700">
                                Judul Section
                            </label>

                            <input
                                type="text"
                                name="title"
                                id="title"
                                value="{{ old('title', $section->title) }}"
                                placeholder="Masukkan judul section"
                                class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100"
                            >

                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- SUBJUDUL --}}
                        <div>
                            <label for="subtitle" class="mb-2 block text-sm font-semibold text-gray-700">
                                Subjudul
                            </label>

                            <input
                                type="text"
                                name="subtitle"
                                id="subtitle"
                                value="{{ old('subtitle', $section->subtitle) }}"
                                placeholder="Masukkan subjudul section"
                                class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100"
                            >

                            @error('subtitle')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- DESKRIPSI --}}
                        <div id="description-container">
                            <label for="content" class="mb-2 block text-sm font-semibold text-gray-700">
                                Deskripsi / Konten Pengantar
                            </label>

                            <textarea
                                name="content"
                                id="content"
                                rows="5"
                                placeholder="Tuliskan deskripsi section..."
                                class="w-full resize-y rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100"
                            >{{ $existingDescription }}</textarea>

                            <p class="mt-1 text-xs text-gray-500">
                                Deskripsi ini akan ditampilkan sesuai dengan tipe section.
                            </p>

                            @error('content')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                </div>


                {{-- GAMBAR SECTION --}}
                <div
                    id="image-section-container"
                    class="hidden overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm"
                >

                    <div class="border-b border-gray-100 px-6 py-4">
                        <h2 class="font-semibold text-gray-800">
                            Gambar Section
                        </h2>

                        <p class="mt-1 text-xs text-gray-500">
                            Kelola gambar Hero atau Tentang Kami.
                        </p>
                    </div>

                    <div class="space-y-5 p-6">

                        {{-- GAMBAR TERSIMPAN --}}
                        @if ($section->image_path)

                            <div id="current-image-container">

                                <p class="mb-2 text-xs font-semibold text-gray-600">
                                    Gambar Saat Ini
                                </p>

                                <img
                                    src="{{ asset('storage/' . $section->image_path) }}"
                                    alt="{{ $section->title }}"
                                    class="max-h-64 w-full rounded-xl border border-gray-200 object-cover"
                                >

                                {{-- PILIH HAPUS GAMBAR --}}
                                <label class="mt-3 flex cursor-pointer items-start gap-3 rounded-lg bg-red-50 p-3">

                                    <input
                                        type="checkbox"
                                        name="remove_image"
                                        id="remove_image"
                                        value="1"
                                        class="mt-1 h-4 w-4 rounded border-gray-300 text-red-600 focus:ring-red-500"
                                    >

                                    <span>
                                        <span class="block text-sm font-semibold text-red-700">
                                            Hapus gambar saat ini
                                        </span>

                                        <span class="mt-1 block text-xs text-red-500">
                                            Gambar akan dihapus dari penyimpanan ketika perubahan disimpan.
                                        </span>
                                    </span>

                                </label>

                            </div>

                        @endif

                        {{-- UPLOAD GAMBAR BARU --}}
                        <div>

                            <label
                                for="image"
                                class="flex min-h-36 cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 px-5 py-7 text-center transition hover:border-yellow-400 hover:bg-yellow-50"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     width="36" height="36"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="1.5"
                                     class="mb-3 text-gray-400">
                                    <rect x="3" y="3" width="18" height="18" rx="2"/>
                                    <circle cx="8.5" cy="8.5" r="1.5"/>
                                    <path d="m21 15-5-5L5 21"/>
                                </svg>

                                <span class="text-sm font-semibold text-gray-700">
                                    Pilih Gambar Baru
                                </span>

                                <span class="mt-1 text-xs text-gray-400">
                                    JPG, PNG, atau WebP. Maksimal 5 MB.
                                </span>
                            </label>

                            <input
                                type="file"
                                name="image"
                                id="image"
                                accept="image/jpeg,image/png,image/webp"
                                class="hidden"
                            >

                        </div>

                        {{-- PREVIEW GAMBAR BARU --}}
                        <div id="image-preview-container" class="hidden">

                            <p class="mb-2 text-xs font-semibold text-gray-600">
                                Preview Gambar Baru
                            </p>

                            <img
                                id="image-preview"
                                src=""
                                alt="Preview gambar section"
                                class="max-h-72 w-full rounded-xl border border-gray-200 object-cover"
                            >

                            <button
                                type="button"
                                id="remove-new-image"
                                class="mt-3 text-sm font-semibold text-red-600 hover:text-red-700"
                            >
                                Batalkan Gambar Baru
                            </button>

                        </div>

                        @error('image')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        @error('remove_image')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror

                    </div>
                </div>


                {{-- PENGATURAN TOMBOL HERO --}}
                <div
                    id="button-container"
                    class="hidden overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm"
                >

                    <div class="border-b border-gray-100 px-6 py-4">
                        <h2 class="font-semibold text-gray-800">
                            Pengaturan Tombol Hero
                        </h2>

                        <p class="mt-1 text-xs text-gray-500">
                            Atur teks dan alamat tujuan tombol pada banner.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 gap-5 p-6 sm:grid-cols-2">

                        <div>
                            <label for="button_text" class="mb-2 block text-sm font-semibold text-gray-700">
                                Teks Tombol
                            </label>

                            <input
                                type="text"
                                name="button_text"
                                id="button_text"
                                value="{{ old('button_text', $section->button_text) }}"
                                placeholder="Contoh: Lihat Paket Umroh"
                                class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100"
                            >

                            @error('button_text')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="button_url" class="mb-2 block text-sm font-semibold text-gray-700">
                                URL Tujuan Tombol
                            </label>

                            <input
                                type="text"
                                name="button_url"
                                id="button_url"
                                value="{{ old('button_url', $section->button_url) }}"
                                placeholder="Contoh: /paket-umroh"
                                class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100"
                            >

                            @error('button_url')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                </div>


                {{-- CARD LAYANAN DAN MENGAPA MEMILIH --}}
                <div
                    id="cards-container"
                    class="hidden overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm"
                >

                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-100 px-6 py-4">

                        <div>
                            <h2 id="cards-heading" class="font-semibold text-gray-800">
                                Daftar Card
                            </h2>

                            <p id="cards-description" class="mt-1 text-xs text-gray-500">
                                Atur card pada section.
                            </p>
                        </div>

                        <button
                            type="button"
                            id="add-card"
                            class="rounded-lg bg-[#FDD52E] px-4 py-2 text-sm font-semibold text-gray-900 hover:bg-yellow-400"
                        >
                            + Tambah Card
                        </button>

                    </div>

                    <div id="cards-list" class="space-y-4 p-6">

                        @foreach ($existingCards as $index => $card)

                            <div class="card-item rounded-xl border border-gray-200 p-5">

                                <div class="mb-4 flex items-center justify-between">
                                    <h3 class="card-number font-semibold text-gray-800">
                                        Card {{ $loop->iteration }}
                                    </h3>

                                    <button
                                        type="button"
                                        class="remove-card text-sm font-semibold text-red-600 hover:text-red-800"
                                    >
                                        Hapus Card
                                    </button>
                                </div>

                                {{-- IKON --}}
                                <div class="icon-field mb-4 hidden">

                                    <label class="mb-2 block text-sm font-semibold text-gray-700">
                                        Ikon (Emoji)
                                    </label>

                                    <input
                                        type="text"
                                        name="cards[{{ $index }}][icon]"
                                        value="{{ $card['icon'] ?? '' }}"
                                        placeholder="Contoh: 🛡️"
                                        class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm"
                                    >

                                </div>

                                {{-- JUDUL CARD --}}
                                <div class="mb-4">

                                    <label class="mb-2 block text-sm font-semibold text-gray-700">
                                        Judul Card
                                    </label>

                                    <input
                                        type="text"
                                        name="cards[{{ $index }}][title]"
                                        value="{{ $card['title'] ?? '' }}"
                                        placeholder="Masukkan judul card"
                                        class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm"
                                    >

                                </div>

                                {{-- DESKRIPSI CARD --}}
                                <div>

                                    <label class="mb-2 block text-sm font-semibold text-gray-700">
                                        Deskripsi Card
                                    </label>

                                    <textarea
                                        name="cards[{{ $index }}][description]"
                                        rows="3"
                                        placeholder="Masukkan deskripsi card"
                                        class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm"
                                    >{{ $card['description'] ?? '' }}</textarea>

                                </div>

                            </div>

                        @endforeach

                    </div>

                    @error('cards')
                        <p class="px-6 pb-5 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                </div>


                {{-- STATISTIK --}}
                <div
                    id="statistics-container"
                    class="hidden overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm"
                >

                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-100 px-6 py-4">

                        <div>
                            <h2 class="font-semibold text-gray-800">
                                Daftar Statistik
                            </h2>

                            <p class="mt-1 text-xs text-gray-500">
                                Atur angka dan keterangan statistik.
                            </p>
                        </div>

                        <button
                            type="button"
                            id="add-statistic"
                            class="rounded-lg bg-[#FDD52E] px-4 py-2 text-sm font-semibold text-gray-900 hover:bg-yellow-400"
                        >
                            + Tambah Statistik
                        </button>

                    </div>

                    <div id="statistics-list" class="space-y-4 p-6">

                        @foreach ($existingStatistics as $index => $statistic)

                            <div class="statistic-item rounded-xl border border-gray-200 p-5">

                                <div class="mb-4 flex items-center justify-between">

                                    <h3 class="statistic-number font-semibold text-gray-800">
                                        Statistik {{ $loop->iteration }}
                                    </h3>

                                    <button
                                        type="button"
                                        class="remove-statistic text-sm font-semibold text-red-600 hover:text-red-800"
                                    >
                                        Hapus Statistik
                                    </button>

                                </div>

                                <div class="mb-4">

                                    <label class="mb-2 block text-sm font-semibold text-gray-700">
                                        Nilai Statistik
                                    </label>

                                    <input
                                        type="text"
                                        name="statistics[{{ $index }}][value]"
                                        value="{{ $statistic['value'] ?? '' }}"
                                        placeholder="Contoh: 1000+"
                                        class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm"
                                    >

                                </div>

                                <div>

                                    <label class="mb-2 block text-sm font-semibold text-gray-700">
                                        Label Statistik
                                    </label>

                                    <input
                                        type="text"
                                        name="statistics[{{ $index }}][label]"
                                        value="{{ $statistic['label'] ?? '' }}"
                                        placeholder="Contoh: Jamaah Terlayani"
                                        class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm"
                                    >

                                </div>

                            </div>

                        @endforeach

                    </div>

                    @error('statistics')
                        <p class="px-6 pb-5 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                </div>

            </div>



                {{-- TESTIMONI --}}
                <div id="testimonials-container"
                     class="hidden overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm lg:col-span-2">

                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-100 px-6 py-4">
                        <div>
                            <h2 class="font-semibold text-gray-800">Daftar Testimoni Jamaah</h2>
                            <p class="mt-1 text-xs text-gray-500">
                                Perbarui nama, asal, rating, komentar, dan foto jamaah.
                            </p>
                        </div>

                        <button
                            type="button"
                            id="add-testimonial"
                            class="rounded-lg bg-[#FDD52E] px-4 py-2 text-sm font-semibold text-gray-900 hover:bg-yellow-400"
                        >
                            + Tambah Testimoni
                        </button>
                    </div>

                    <div id="testimonials-list" class="space-y-4 p-6">
                        @foreach ($existingTestimonials as $index => $testimonial)
                            <div class="testimonial-item rounded-xl border border-gray-200 p-5">
                                <div class="mb-4 flex items-center justify-between">
                                    <h3 class="testimonial-number font-semibold text-gray-800">
                                        Testimoni {{ $loop->iteration }}
                                    </h3>
                                    <button type="button" class="remove-testimonial text-sm font-semibold text-red-600 hover:text-red-800">
                                        Hapus Testimoni
                                    </button>
                                </div>

                                <input type="hidden" name="testimonials[{{ $index }}][id]" value="{{ $testimonial['id'] ?? '' }}">

                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                    <div>
                                        <label class="mb-2 block text-sm font-semibold text-gray-700">Nama Jamaah <span class="text-red-500">*</span></label>
                                        <input type="text" name="testimonials[{{ $index }}][name]"
                                               value="{{ $testimonial['name'] ?? '' }}" required
                                               placeholder="Contoh: Bapak Ahmad"
                                               class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm">
                                    </div>
                                    <div>
                                        <label class="mb-2 block text-sm font-semibold text-gray-700">Asal / Keterangan</label>
                                        <input type="text" name="testimonials[{{ $index }}][origin]"
                                               value="{{ $testimonial['origin'] ?? '' }}"
                                               placeholder="Contoh: Padang, Sumatera Barat"
                                               class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm">
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <label class="mb-2 block text-sm font-semibold text-gray-700">Rating <span class="text-red-500">*</span></label>
                                    <select name="testimonials[{{ $index }}][rating]" required
                                            class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm sm:max-w-xs">
                                        @for ($rating = 5; $rating >= 1; $rating--)
                                            <option value="{{ $rating }}" {{ (int) ($testimonial['rating'] ?? 5) === $rating ? 'selected' : '' }}>
                                                {{ $rating }} dari 5 bintang
                                            </option>
                                        @endfor
                                    </select>
                                </div>

                                <div class="mt-4">
                                    <label class="mb-2 block text-sm font-semibold text-gray-700">Isi Testimoni <span class="text-red-500">*</span></label>
                                    <textarea name="testimonials[{{ $index }}][message]" rows="4" required
                                              placeholder="Tuliskan pengalaman jamaah selama menggunakan layanan Ansar Travel..."
                                              class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm">{{ $testimonial['message'] ?? '' }}</textarea>
                                </div>

                                @if (!empty($testimonial['photo_path']))
                                    <div class="mt-4 testimonial-current-photo">
                                        <label class="mb-2 block text-sm font-semibold text-gray-700">Foto Saat Ini</label>
                                        <img src="{{ asset('storage/' . $testimonial['photo_path']) }}"
                                             alt="Foto {{ $testimonial['name'] ?? 'jamaah' }}"
                                             class="h-32 w-32 rounded-xl border border-gray-200 object-cover">
                                        <label class="mt-3 inline-flex cursor-pointer items-center gap-2 rounded-lg bg-red-50 px-3 py-2 text-xs font-semibold text-red-700">
                                            <input type="checkbox" name="testimonials[{{ $index }}][remove_photo]" value="1"
                                                   class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                            Hapus foto saat ini
                                        </label>
                                    </div>
                                @endif

                                <div class="mt-4">
                                    <label class="mb-2 block text-sm font-semibold text-gray-700">
                                        {{ !empty($testimonial['photo_path']) ? 'Ganti Foto Jamaah' : 'Foto Jamaah (Opsional)' }}
                                    </label>
                                    <input type="file" name="testimonials[{{ $index }}][photo]"
                                           accept="image/jpeg,image/png,image/webp"
                                           class="testimonial-photo-input block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm file:mr-3 file:rounded-md file:border-0 file:bg-yellow-100 file:px-3 file:py-2 file:text-xs file:font-semibold">
                                    <p class="mt-1 text-xs text-gray-400">JPG, PNG, atau WebP. Maksimal 2 MB per foto.</p>

                                    <div class="testimonial-preview-container mt-3 hidden">
                                        <img src="" alt="Preview foto jamaah baru" class="testimonial-preview-image h-32 w-32 rounded-xl border border-gray-200 object-cover">
                                        <button type="button" class="remove-testimonial-preview mt-2 text-xs font-semibold text-red-600 hover:text-red-800">
                                            Batalkan foto baru
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @error('testimonials')
                        <p class="px-6 pb-5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>



                <div id="gallery-container" class="hidden overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm lg:col-span-2">
                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-100 px-6 py-4">
                        <div>
                            <h2 class="font-semibold text-gray-800">Foto Galeri</h2>
                            <p class="mt-1 text-xs text-gray-500">Kelola foto yang tersimpan. Hapus satu kartu foto untuk menghapus foto itu saja. Maksimal 20 foto, 4 MB per foto.</p>
                        </div>
                        <button type="button" id="add-gallery-item" class="rounded-lg bg-[#FDD52E] px-4 py-2 text-sm font-semibold text-gray-900 hover:bg-yellow-400">+ Tambah Foto</button>
                    </div>
                    <div id="gallery-list" class="grid grid-cols-1 gap-4 p-6 md:grid-cols-2">
                        @foreach ($existingGalleryItems as $index => $photo)
                            <div class="gallery-item rounded-xl border border-gray-200 p-4">
                                <div class="mb-3 flex items-center justify-between gap-2">
                                    <h3 class="gallery-number font-semibold text-gray-800">Foto {{ $loop->iteration }}</h3>
                                    <button type="button" class="remove-gallery-item text-xs font-semibold text-red-600 hover:text-red-800">Hapus Foto</button>
                                </div>
                                <input type="hidden" name="gallery[{{ $index }}][id]" value="{{ $photo['id'] ?? '' }}">
                                @if (!empty($photo['image_path']))
                                    <div class="gallery-current-image mb-3">
                                        <p class="mb-2 text-xs font-semibold text-gray-600">Foto Saat Ini</p>
                                        <img src="{{ asset('storage/' . $photo['image_path']) }}" alt="{{ $photo['alt'] ?? $photo['caption'] ?? 'Foto galeri' }}" class="h-40 w-full rounded-lg border border-gray-200 object-cover">
                                    </div>
                                @endif
                                <label class="mb-2 block text-sm font-semibold text-gray-700">{{ !empty($photo['image_path']) ? 'Ganti Foto (Opsional)' : 'Pilih Foto' }}</label>
                                <input type="file" name="gallery[{{ $index }}][image]" accept="image/jpeg,image/png,image/webp" {{ empty($photo['image_path']) ? 'required' : '' }} class="gallery-image-input block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm file:mr-3 file:rounded-md file:border-0 file:bg-yellow-100 file:px-3 file:py-2 file:text-xs file:font-semibold">
                                <div class="gallery-preview-container mt-3 hidden">
                                    <img src="" alt="Preview foto galeri" class="gallery-preview-image h-40 w-full rounded-lg border border-gray-200 object-cover">
                                    <button type="button" class="remove-gallery-preview mt-2 text-xs font-semibold text-red-600 hover:text-red-800">Batalkan foto baru</button>
                                </div>
                                <div class="mt-4">
                                    <label class="mb-2 block text-sm font-semibold text-gray-700">Keterangan Foto</label>
                                    <input type="text" name="gallery[{{ $index }}][caption]" value="{{ $photo['caption'] ?? '' }}" maxlength="255" placeholder="Contoh: Kegiatan jamaah di Masjidil Haram" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm">
                                </div>
                                <div class="mt-3">
                                    <label class="mb-2 block text-sm font-semibold text-gray-700">Teks Alternatif (Alt)</label>
                                    <input type="text" name="gallery[{{ $index }}][alt]" value="{{ $photo['alt'] ?? '' }}" maxlength="255" placeholder="Deskripsi singkat foto" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm">
                                </div>
                                <div class="mt-3">
                                    <label class="mb-2 block text-sm font-semibold text-gray-700">Urutan Foto</label>
                                    <input type="number" name="gallery[{{ $index }}][sort_order]" min="1" required value="{{ $photo['sort_order'] ?? ($index + 1) }}" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm">
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @error('gallery')
                        <p class="px-6 pb-5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @foreach ($errors->getMessages() as $field => $messages)
                        @if (str_starts_with($field, 'gallery.'))
                            <p class="px-6 pb-2 text-sm text-red-600">{{ $messages[0] }}</p>
                        @endif
                    @endforeach
                </div>



                <div id="contact-container" class="hidden overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm lg:col-span-2">
                    <div class="border-b border-gray-100 px-6 py-4">
                        <h2 class="font-semibold text-gray-800">Informasi Kontak Perusahaan</h2>
                        <p class="mt-1 text-xs text-gray-500">Perbarui saluran komunikasi resmi Ansar Travel. Kolom yang tidak digunakan boleh dikosongkan.</p>
                    </div>
                    <div class="grid grid-cols-1 gap-5 p-6 md:grid-cols-2">
                        <div>
                            <label for="contact_whatsapp" class="mb-2 block text-sm font-semibold text-gray-700">Nomor WhatsApp</label>
                            <input type="text" id="contact_whatsapp" name="contact[whatsapp]" value="{{ old('contact.whatsapp', data_get($section->content, 'contact.whatsapp', '6282170700166')) }}" placeholder="Contoh: 6282170700166" class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm">
                            <p class="mt-1 text-xs text-gray-400">Gunakan kode negara, tanpa tanda + atau spasi.</p>
                            @error('contact.whatsapp') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="contact_phone" class="mb-2 block text-sm font-semibold text-gray-700">Nomor Telepon</label>
                            <input type="text" id="contact_phone" name="contact[phone]" value="{{ old('contact.phone', data_get($section->content, 'contact.phone')) }}" placeholder="Contoh: (0751) 123456" class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm">
                            @error('contact.phone') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="contact_email" class="mb-2 block text-sm font-semibold text-gray-700">Email</label>
                            <input type="email" id="contact_email" name="contact[email]" value="{{ old('contact.email', data_get($section->content, 'contact.email')) }}" placeholder="Contoh: info@ansartravel.co.id" class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm">
                            @error('contact.email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="contact_office_hours" class="mb-2 block text-sm font-semibold text-gray-700">Jam Operasional</label>
                            <input type="text" id="contact_office_hours" name="contact[office_hours]" value="{{ old('contact.office_hours', data_get($section->content, 'contact.office_hours')) }}" placeholder="Contoh: Senin–Sabtu, 08.00–17.00 WIB" class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm">
                            @error('contact.office_hours') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label for="contact_address" class="mb-2 block text-sm font-semibold text-gray-700">Alamat Kantor</label>
                            <textarea id="contact_address" name="contact[address]" rows="3" placeholder="Masukkan alamat lengkap kantor Ansar Travel" class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm">{{ old('contact.address', data_get($section->content, 'contact.address')) }}</textarea>
                            @error('contact.address') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label for="contact_maps_url" class="mb-2 block text-sm font-semibold text-gray-700">Link Google Maps</label>
                            <input type="url" id="contact_maps_url" name="contact[maps_url]" value="{{ old('contact.maps_url', data_get($section->content, 'contact.maps_url')) }}" placeholder="https://maps.google.com/..." class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm">
                            @error('contact.maps_url') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="contact_website" class="mb-2 block text-sm font-semibold text-gray-700">Website Perusahaan</label>
                            <input type="url" id="contact_website" name="contact[website]" value="{{ old('contact.website', data_get($section->content, 'contact.website')) }}" placeholder="https://www.ansartravel.co.id" class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm">
                            @error('contact.website') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="contact_instagram" class="mb-2 block text-sm font-semibold text-gray-700">Link Instagram</label>
                            <input type="url" id="contact_instagram" name="contact[instagram]" value="{{ old('contact.instagram', data_get($section->content, 'contact.instagram')) }}" placeholder="https://www.instagram.com/akun" class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm">
                            @error('contact.instagram') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label for="contact_facebook" class="mb-2 block text-sm font-semibold text-gray-700">Link Facebook</label>
                            <input type="url" id="contact_facebook" name="contact[facebook]" value="{{ old('contact.facebook', data_get($section->content, 'contact.facebook')) }}" placeholder="https://www.facebook.com/akun" class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm">
                            @error('contact.facebook') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>


            {{-- KOLOM KANAN --}}
            <div class="space-y-6">

                {{-- PANDUAN SECTION --}}
                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">

                    <h2 class="font-semibold text-gray-800">
                        Panduan Section
                    </h2>

                    <p id="section-help" class="mt-3 text-sm leading-7 text-gray-500">
                        Pengaturan akan berubah mengikuti tipe section yang dipilih.
                    </p>

                </div>

                {{-- PENGATURAN URUTAN --}}
                <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                    <div class="border-b border-gray-100 px-5 py-4">
                        <h2 class="font-semibold text-gray-800">
                            Pengaturan Section
                        </h2>

                        <p class="mt-1 text-xs text-gray-500">
                            Atur posisi section pada halaman.
                        </p>
                    </div>

                    <div class="p-5">

                        <label for="sort_order" class="mb-2 block text-sm font-semibold text-gray-700">
                            Urutan Section
                        </label>

                        <input
                            type="number"
                            name="sort_order"
                            id="sort_order"
                            min="1"
                            value="{{ old('sort_order', $section->sort_order) }}"
                            required
                            class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none focus:border-yellow-400 focus:ring-2 focus:ring-yellow-100"
                        >

                        <p class="mt-2 text-xs text-gray-500">
                            Gunakan nomor urutan yang berbeda untuk setiap section pada halaman ini.
                            Contoh: 1, 2, 3, dan seterusnya.
                        </p>

                        @error('sort_order')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- TOMBOL AKSI --}}
        <div class="mt-6 flex flex-col-reverse justify-end gap-3 sm:flex-row">

            <a
                href="{{ route('admin.pages.sections', $page->id) }}"
                class="rounded-lg border border-gray-300 bg-white px-6 py-3 text-center text-sm font-semibold text-gray-600 hover:bg-gray-50"
            >
                Batal
            </a>

            <button
                type="submit"
                class="rounded-lg bg-[#FDD52E] px-7 py-3 text-sm font-semibold text-gray-900 shadow-sm hover:bg-yellow-400"
            >
                Simpan Perubahan
            </button>

        </div>

    </form>

</div>


{{-- JAVASCRIPT FORM EDIT --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const sectionType = document.getElementById('section_type');

    const descriptionContainer = document.getElementById('description-container');
    const imageContainer = document.getElementById('image-section-container');
    const buttonContainer = document.getElementById('button-container');
    const cardsContainer = document.getElementById('cards-container');
    const statisticsContainer = document.getElementById('statistics-container');
    const testimonialsContainer = document.getElementById('testimonials-container');
    const galleryContainer = document.getElementById('gallery-container');
    const contactContainer = document.getElementById('contact-container');
    const galleryList = document.getElementById('gallery-list');

    const cardsList = document.getElementById('cards-list');
    const statisticsList = document.getElementById('statistics-list');
    const testimonialsList = document.getElementById('testimonials-list');

    const cardsHeading = document.getElementById('cards-heading');
    const cardsDescription = document.getElementById('cards-description');
    const sectionHelp = document.getElementById('section-help');

    // Mengatur tampilan form berdasarkan tipe.
    function updateFormVisibility() {

        const type = sectionType.value;

        const isCardSection = ['why_choose_us', 'services'].includes(type);
        const isWhyChooseUs = type === 'why_choose_us';
        const isImageSection = ['hero', 'about'].includes(type);
        const isHero = type === 'hero';

        descriptionContainer.classList.toggle(
            'hidden',
            type === 'statistics'
        );

        imageContainer.classList.toggle(
            'hidden',
            !isImageSection
        );

        buttonContainer.classList.toggle(
            'hidden',
            !isHero
        );

        cardsContainer.classList.toggle(
            'hidden',
            !isCardSection
        );

        statisticsContainer.classList.toggle(
            'hidden',
            type !== 'statistics'
        );

        testimonialsContainer.classList.toggle(
            'hidden',
            type !== 'testimonials'
        );

        galleryContainer.classList.toggle('hidden', type !== 'gallery');
        contactContainer.classList.toggle('hidden', type !== 'contact');
        // Input dari panel yang sedang tersembunyi harus dinonaktifkan.
        // Jika tidak, atribut required pada panel lain tetap ikut divalidasi browser.
        const activePanels = [
            [descriptionContainer, type !== 'statistics'],
            [imageContainer, isImageSection],
            [buttonContainer, isHero],
            [cardsContainer, isCardSection],
            [statisticsContainer, type === 'statistics'],
            [testimonialsContainer, type === 'testimonials'],
            [galleryContainer, type === 'gallery'],
            [contactContainer, type === 'contact'],
        ];

        activePanels.forEach(([panel, isActive]) => {
            if (!panel) return;

            panel.querySelectorAll('input, select, textarea').forEach(control => {
                control.disabled = !isActive;
            });
        });


        // Menampilkan ikon hanya untuk Mengapa Memilih.
        cardsList.querySelectorAll('.card-item').forEach(card => {

            const iconField = card.querySelector('.icon-field');

            if (iconField) {
                iconField.classList.toggle(
                    'hidden',
                    !isWhyChooseUs
                );
            }

        });

        if (isWhyChooseUs) {

            cardsHeading.textContent = 'Daftar Keunggulan';

            cardsDescription.textContent =
                'Tambahkan alasan jamaah memilih Ansar Travel.';

        } else if (type === 'services') {

            cardsHeading.textContent = 'Daftar Layanan';

            cardsDescription.textContent =
                'Tambahkan layanan yang disediakan Ansar Travel.';

        }

        // Petunjuk pengelolaan berdasarkan tipe.
        const helpTexts = {
            hero: 'Hero merupakan banner utama. Atur judul, subtitle, deskripsi, gambar latar, serta tombol ajakan.',
            statistics: 'Statistik menampilkan angka pencapaian Ansar Travel seperti jumlah jamaah dan pengalaman.',
            about: 'Tentang Kami digunakan untuk memperkenalkan perusahaan melalui judul, deskripsi, dan gambar.',
            why_choose_us: 'Tambahkan beberapa keunggulan yang menjelaskan alasan jamaah memilih Ansar Travel.',
            services: 'Tambahkan kartu layanan yang disediakan oleh Ansar Travel.',
            packages: 'Section ini nantinya akan menampilkan data paket dari Manajemen Paket Umroh.',
            testimonials: 'Kelola beberapa testimoni jamaah. Setiap item dapat memiliki nama, asal, rating bintang, komentar, dan foto.',
            gallery: 'Kelola beberapa foto kegiatan dan dokumentasi perjalanan. Setiap foto memiliki keterangan, teks alternatif, serta urutan sendiri.',
            contact: 'Atur WhatsApp, telepon, email, alamat, jam operasional, Google Maps, website, dan media sosial Ansar Travel.'
        };

        sectionHelp.textContent =
            helpTexts[type] || 'Pilih tipe section untuk mengatur kontennya.';
    }


    // Memperbarui nomor dan index card.
    function updateCardNumbers() {

        cardsList.querySelectorAll('.card-item').forEach((card, index) => {

            card.querySelector('.card-number').textContent =
                `Card ${index + 1}`;

            card.querySelectorAll('input, textarea').forEach(field => {

                const match = field.name.match(/\[(icon|title|description)\]$/);

                if (match) {
                    field.name = `cards[${index}][${match[1]}]`;
                }

            });

        });
    }


    // Memperbarui nomor statistik.
    function updateStatisticNumbers() {

        statisticsList.querySelectorAll('.statistic-item').forEach((item, index) => {

            item.querySelector('.statistic-number').textContent =
                `Statistik ${index + 1}`;

            item.querySelectorAll('input').forEach(field => {

                const match = field.name.match(/\[(value|label)\]$/);

                if (match) {
                    field.name = `statistics[${index}][${match[1]}]`;
                }

            });

        });
    }


    // Membuat card baru.
    function createCard(index) {

        const card = document.createElement('div');

        const showIcon = sectionType.value === 'why_choose_us';

        card.className = 'card-item rounded-xl border border-gray-200 p-5';

        card.innerHTML = `
            <div class="mb-4 flex items-center justify-between">

                <h4 class="card-number font-semibold text-gray-800">
                    Card ${index + 1}
                </h4>

                <button
                    type="button"
                    class="remove-card text-sm font-semibold text-red-600"
                >
                    Hapus Card
                </button>

            </div>

            <div class="icon-field mb-4 ${showIcon ? '' : 'hidden'}">

                <label class="mb-2 block text-sm font-semibold text-gray-700">
                    Ikon (Emoji)
                </label>

                <input
                    type="text"
                    name="cards[${index}][icon]"
                    placeholder="Contoh: 🛡️"
                    class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm"
                >

            </div>

            <div class="mb-4">

                <label class="mb-2 block text-sm font-semibold text-gray-700">
                    Judul Card
                </label>

                <input
                    type="text"
                    name="cards[${index}][title]"
                    placeholder="Masukkan judul card"
                    class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm"
                >

            </div>

            <div>

                <label class="mb-2 block text-sm font-semibold text-gray-700">
                    Deskripsi Card
                </label>

                <textarea
                    name="cards[${index}][description]"
                    rows="3"
                    class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm"
                ></textarea>

            </div>
        `;

        return card;
    }


    // Membuat statistik baru.
    function createStatistic(index) {

        const item = document.createElement('div');

        item.className = 'statistic-item rounded-xl border border-gray-200 p-5';

        item.innerHTML = `
            <div class="mb-4 flex items-center justify-between">

                <h4 class="statistic-number font-semibold text-gray-800">
                    Statistik ${index + 1}
                </h4>

                <button
                    type="button"
                    class="remove-statistic text-sm font-semibold text-red-600"
                >
                    Hapus Statistik
                </button>

            </div>

            <div class="mb-4">

                <label class="mb-2 block text-sm font-semibold text-gray-700">
                    Nilai Statistik
                </label>

                <input
                    type="text"
                    name="statistics[${index}][value]"
                    placeholder="Contoh: 1000+"
                    class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm"
                >

            </div>

            <div>

                <label class="mb-2 block text-sm font-semibold text-gray-700">
                    Label Statistik
                </label>

                <input
                    type="text"
                    name="statistics[${index}][label]"
                    placeholder="Contoh: Jamaah Terlayani"
                    class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm"
                >

            </div>
        `;

        return item;
    }


    // Tambah card.
    document.getElementById('add-card').addEventListener('click', function () {

        const index = cardsList.querySelectorAll('.card-item').length;

        cardsList.appendChild(createCard(index));

        updateCardNumbers();
    });


    // Hapus card.
    cardsList.addEventListener('click', function (event) {

        const button = event.target.closest('.remove-card');

        if (!button) {
            return;
        }

        const cards = cardsList.querySelectorAll('.card-item');

        if (cards.length <= 1) {
            alert('Minimal harus ada satu card.');
            return;
        }

        button.closest('.card-item').remove();

        updateCardNumbers();
    });


    // Tambah statistik.
    document.getElementById('add-statistic').addEventListener('click', function () {

        const index = statisticsList.querySelectorAll('.statistic-item').length;

        statisticsList.appendChild(createStatistic(index));

        updateStatisticNumbers();
    });


    // Hapus statistik.
    statisticsList.addEventListener('click', function (event) {

        const button = event.target.closest('.remove-statistic');

        if (!button) {
            return;
        }

        const items = statisticsList.querySelectorAll('.statistic-item');

        if (items.length <= 1) {
            alert('Minimal harus ada satu statistik.');
            return;
        }

        button.closest('.statistic-item').remove();

        updateStatisticNumbers();
    });


    // Mengatur ulang nomor dan nama field testimoni.
    function updateTestimonialNumbers() {
        testimonialsList.querySelectorAll('.testimonial-item').forEach((item, index) => {
            item.querySelector('.testimonial-number').textContent = `Testimoni ${index + 1}`;

            item.querySelectorAll('input, select, textarea').forEach(field => {
                const match = field.name.match(/\[(id|name|origin|rating|message|photo|remove_photo)\]$/);
                if (match) {
                    field.name = `testimonials[${index}][${match[1]}]`;
                }
            });
        });
    }

    // Membuat item testimoni baru pada form edit.
    function createTestimonial(index) {
        const item = document.createElement('div');
        item.className = 'testimonial-item rounded-xl border border-gray-200 p-5';
        item.innerHTML = `
            <div class="mb-4 flex items-center justify-between">
                <h3 class="testimonial-number font-semibold text-gray-800">Testimoni ${index + 1}</h3>
                <button type="button" class="remove-testimonial text-sm font-semibold text-red-600 hover:text-red-800">Hapus Testimoni</button>
            </div>
            <input type="hidden" name="testimonials[${index}][id]" value="">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-700">Nama Jamaah <span class="text-red-500">*</span></label>
                    <input type="text" name="testimonials[${index}][name]" required placeholder="Contoh: Bapak Ahmad" class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-700">Asal / Keterangan</label>
                    <input type="text" name="testimonials[${index}][origin]" placeholder="Contoh: Padang, Sumatera Barat" class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm">
                </div>
            </div>
            <div class="mt-4">
                <label class="mb-2 block text-sm font-semibold text-gray-700">Rating <span class="text-red-500">*</span></label>
                <select name="testimonials[${index}][rating]" required class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm sm:max-w-xs">
                    <option value="5" selected>5 dari 5 bintang</option>
                    <option value="4">4 dari 5 bintang</option>
                    <option value="3">3 dari 5 bintang</option>
                    <option value="2">2 dari 5 bintang</option>
                    <option value="1">1 dari 5 bintang</option>
                </select>
            </div>
            <div class="mt-4">
                <label class="mb-2 block text-sm font-semibold text-gray-700">Isi Testimoni <span class="text-red-500">*</span></label>
                <textarea name="testimonials[${index}][message]" rows="4" required placeholder="Tuliskan pengalaman jamaah..." class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm"></textarea>
            </div>
            <div class="mt-4">
                <label class="mb-2 block text-sm font-semibold text-gray-700">Foto Jamaah (Opsional)</label>
                <input type="file" name="testimonials[${index}][photo]" accept="image/jpeg,image/png,image/webp" class="testimonial-photo-input block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm file:mr-3 file:rounded-md file:border-0 file:bg-yellow-100 file:px-3 file:py-2 file:text-xs file:font-semibold">
                <p class="mt-1 text-xs text-gray-400">JPG, PNG, atau WebP. Maksimal 2 MB per foto.</p>
                <div class="testimonial-preview-container mt-3 hidden">
                    <img src="" alt="Preview foto jamaah baru" class="testimonial-preview-image h-32 w-32 rounded-xl border border-gray-200 object-cover">
                    <button type="button" class="remove-testimonial-preview mt-2 text-xs font-semibold text-red-600 hover:text-red-800">Batalkan foto baru</button>
                </div>
            </div>
        `;
        return item;
    }

    // Tambah testimoni.
    document.getElementById('add-testimonial').addEventListener('click', function () {
        const index = testimonialsList.querySelectorAll('.testimonial-item').length;
        testimonialsList.appendChild(createTestimonial(index));
        updateTestimonialNumbers();
    });

    // Hapus testimoni atau batalkan preview foto.
    testimonialsList.addEventListener('click', function (event) {
        const removeButton = event.target.closest('.remove-testimonial');
        if (removeButton) {
            const items = testimonialsList.querySelectorAll('.testimonial-item');
            if (items.length <= 1) {
                alert('Minimal harus ada satu testimoni.');
                return;
            }
            const item = removeButton.closest('.testimonial-item');
            const preview = item.querySelector('.testimonial-preview-image');
            if (preview && preview.dataset.objectUrl) URL.revokeObjectURL(preview.dataset.objectUrl);
            item.remove();
            updateTestimonialNumbers();
            return;
        }

        const clearButton = event.target.closest('.remove-testimonial-preview');
        if (clearButton) {
            const item = clearButton.closest('.testimonial-item');
            const input = item.querySelector('.testimonial-photo-input');
            const previewContainer = item.querySelector('.testimonial-preview-container');
            const preview = item.querySelector('.testimonial-preview-image');
            input.value = '';
            if (preview.dataset.objectUrl) URL.revokeObjectURL(preview.dataset.objectUrl);
            preview.removeAttribute('src');
            delete preview.dataset.objectUrl;
            previewContainer.classList.add('hidden');
        }
    });

    // Preview foto baru untuk setiap testimoni.
    testimonialsList.addEventListener('change', function (event) {
        if (!event.target.matches('.testimonial-photo-input')) return;
        const input = event.target;
        const item = input.closest('.testimonial-item');
        const previewContainer = item.querySelector('.testimonial-preview-container');
        const preview = item.querySelector('.testimonial-preview-image');
        const file = input.files[0];

        if (preview.dataset.objectUrl) URL.revokeObjectURL(preview.dataset.objectUrl);
        delete preview.dataset.objectUrl;

        if (file) {
            const objectUrl = URL.createObjectURL(file);
            preview.src = objectUrl;
            preview.dataset.objectUrl = objectUrl;
            previewContainer.classList.remove('hidden');

            // Foto baru menggantikan foto lama, jadi pilihan hapus dibatalkan.
            const removePhoto = item.querySelector('input[name$="[remove_photo]"]');
            if (removePhoto) removePhoto.checked = false;
        } else {
            preview.removeAttribute('src');
            previewContainer.classList.add('hidden');
        }
    });


    function updateGalleryNumbers() {
        galleryList.querySelectorAll('.gallery-item').forEach((item, index) => {
            item.querySelector('.gallery-number').textContent = `Foto ${index + 1}`;
            item.querySelectorAll('input').forEach(field => {
                const match = field.name.match(/\[(id|image|caption|alt|sort_order)\]$/);
                if (match) field.name = `gallery[${index}][${match[1]}]`;
            });
        });
    }

    function createGalleryItem(index) {
        const item = document.createElement('div');
        item.className = 'gallery-item rounded-xl border border-gray-200 p-4';
        item.innerHTML = `
            <div class="mb-3 flex items-center justify-between gap-2">
                <h3 class="gallery-number font-semibold text-gray-800">Foto ${index + 1}</h3>
                <button type="button" class="remove-gallery-item text-xs font-semibold text-red-600 hover:text-red-800">Hapus Foto</button>
            </div>
            <input type="hidden" name="gallery[${index}][id]" value="">
            <label class="mb-2 block text-sm font-semibold text-gray-700">Pilih Foto <span class="text-red-500">*</span></label>
            <input type="file" name="gallery[${index}][image]" accept="image/jpeg,image/png,image/webp" required class="gallery-image-input block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm file:mr-3 file:rounded-md file:border-0 file:bg-yellow-100 file:px-3 file:py-2 file:text-xs file:font-semibold">
            <div class="gallery-preview-container mt-3 hidden">
                <img src="" alt="Preview foto galeri" class="gallery-preview-image h-40 w-full rounded-lg border border-gray-200 object-cover">
                <button type="button" class="remove-gallery-preview mt-2 text-xs font-semibold text-red-600 hover:text-red-800">Batalkan foto baru</button>
            </div>
            <div class="mt-4">
                <label class="mb-2 block text-sm font-semibold text-gray-700">Keterangan Foto</label>
                <input type="text" name="gallery[${index}][caption]" maxlength="255" placeholder="Contoh: Kegiatan jamaah di Masjidil Haram" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm">
            </div>
            <div class="mt-3">
                <label class="mb-2 block text-sm font-semibold text-gray-700">Teks Alternatif (Alt)</label>
                <input type="text" name="gallery[${index}][alt]" maxlength="255" placeholder="Deskripsi singkat foto" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm">
            </div>
            <div class="mt-3">
                <label class="mb-2 block text-sm font-semibold text-gray-700">Urutan Foto</label>
                <input type="number" name="gallery[${index}][sort_order]" min="1" required value="${index + 1}" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm">
            </div>
        `;
        return item;
    }

    document.getElementById('add-gallery-item').addEventListener('click', function () {
        const items = galleryList.querySelectorAll('.gallery-item');
        if (items.length >= 20) {
            alert('Maksimal 20 foto dalam satu section galeri.');
            return;
        }
        galleryList.appendChild(createGalleryItem(items.length));
        updateGalleryNumbers();
    });

    galleryList.addEventListener('click', function (event) {
        const removeItem = event.target.closest('.remove-gallery-item');
        if (removeItem) {
            const items = galleryList.querySelectorAll('.gallery-item');
            if (items.length <= 1) {
                alert('Minimal harus ada satu foto galeri.');
                return;
            }
            const item = removeItem.closest('.gallery-item');
            const preview = item.querySelector('.gallery-preview-image');
            if (preview?.dataset.objectUrl) URL.revokeObjectURL(preview.dataset.objectUrl);
            item.remove();
            updateGalleryNumbers();
            return;
        }

        const clearPreview = event.target.closest('.remove-gallery-preview');
        if (clearPreview) {
            const item = clearPreview.closest('.gallery-item');
            const input = item.querySelector('.gallery-image-input');
            const preview = item.querySelector('.gallery-preview-image');
            input.value = '';
            if (preview.dataset.objectUrl) URL.revokeObjectURL(preview.dataset.objectUrl);
            preview.removeAttribute('src');
            delete preview.dataset.objectUrl;
            item.querySelector('.gallery-preview-container').classList.add('hidden');
        }
    });

    galleryList.addEventListener('change', function (event) {
        if (!event.target.matches('.gallery-image-input')) return;
        const input = event.target;
        const item = input.closest('.gallery-item');
        const preview = item.querySelector('.gallery-preview-image');
        const container = item.querySelector('.gallery-preview-container');
        if (preview.dataset.objectUrl) URL.revokeObjectURL(preview.dataset.objectUrl);
        delete preview.dataset.objectUrl;
        if (input.files[0]) {
            const objectUrl = URL.createObjectURL(input.files[0]);
            preview.src = objectUrl;
            preview.dataset.objectUrl = objectUrl;
            container.classList.remove('hidden');
        } else {
            preview.removeAttribute('src');
            container.classList.add('hidden');
        }
    });


    // PREVIEW GAMBAR BARU.
    const imageInput = document.getElementById('image');
    const imagePreviewContainer = document.getElementById('image-preview-container');
    const imagePreview = document.getElementById('image-preview');
    const removeNewImageButton = document.getElementById('remove-new-image');
    const removeExistingImage = document.getElementById('remove_image');

    let imagePreviewUrl = null;

    imageInput.addEventListener('change', function () {

        const file = this.files[0];

        if (imagePreviewUrl) {
            URL.revokeObjectURL(imagePreviewUrl);
            imagePreviewUrl = null;
        }

        if (file) {

            imagePreviewUrl = URL.createObjectURL(file);

            imagePreview.src = imagePreviewUrl;

            imagePreviewContainer.classList.remove('hidden');

            // Jika gambar baru dipilih, batalkan pilihan hapus gambar lama.
            if (removeExistingImage) {
                removeExistingImage.checked = false;
            }

        } else {

            imagePreviewContainer.classList.add('hidden');
            imagePreview.src = '';

        }

    });


    // Membatalkan pilihan gambar baru.
    removeNewImageButton.addEventListener('click', function () {

        imageInput.value = '';

        imagePreview.src = '';

        imagePreviewContainer.classList.add('hidden');

        if (imagePreviewUrl) {
            URL.revokeObjectURL(imagePreviewUrl);
            imagePreviewUrl = null;
        }

    });


    // Perbarui form ketika tipe section berubah.
    sectionType.addEventListener('change', updateFormVisibility);


    // Inisialisasi.
    updateFormVisibility();
    updateCardNumbers();
    updateStatisticNumbers();
    updateTestimonialNumbers();
    updateGalleryNumbers();

});
</script>

@endsection
