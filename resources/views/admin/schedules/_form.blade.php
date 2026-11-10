
@php
    $schedule = $schedule ?? null;

    $departureValue = old(
        'departure_date',
        $schedule?->departure_date?->format('Y-m-d') ?? ''
    );

    $returnValue = old(
        'return_date',
        $schedule?->return_date?->format('Y-m-d') ?? ''
    );
@endphp

<div class="space-y-6">

    {{-- INFORMASI PERJALANAN --}}
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

        <div class="border-b border-gray-100 px-6 py-4">
            <h2 class="font-semibold text-gray-800">
                Informasi Perjalanan
            </h2>
            <p class="mt-1 text-xs text-gray-500">
                Masukkan informasi dasar jadwal keberangkatan.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-5 p-6 md:grid-cols-2">

            <div class="md:col-span-2">
                <label for="name" class="mb-2 block text-sm font-semibold text-gray-700">
                    Nama Paket Perjalanan <span class="text-red-500">*</span>
                </label>

                <input
                    type="text"
                    name="name"
                    id="name"
                    value="{{ old('name', $schedule?->name) }}"
                    placeholder="Contoh: Paket Umroh Reguler Desember 2026"
                    required
                    class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none focus:border-amber-400 focus:ring-2 focus:ring-amber-100"
                >

                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="type" class="mb-2 block text-sm font-semibold text-gray-700">
                    Jenis Perjalanan <span class="text-red-500">*</span>
                </label>

                <select
                    name="type"
                    id="type"
                    required
                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm outline-none focus:border-amber-400 focus:ring-2 focus:ring-amber-100"
                >
                    <option value="">Pilih Jenis Perjalanan</option>

                    <option value="umrah"
                        {{ old('type', $schedule?->type) === 'umrah' ? 'selected' : '' }}>
                        Umroh
                    </option>

                    <option value="tour"
                        {{ old('type', $schedule?->type) === 'tour' ? 'selected' : '' }}>
                        Tour & Wisata
                    </option>
                </select>

                @error('type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="airline" class="mb-2 block text-sm font-semibold text-gray-700">
                    Maskapai
                </label>

                <input
                    type="text"
                    name="airline"
                    id="airline"
                    value="{{ old('airline', $schedule?->airline) }}"
                    placeholder="Contoh: Saudi Airlines"
                    class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none focus:border-amber-400 focus:ring-2 focus:ring-amber-100"
                >

                @error('airline')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="departure_date" class="mb-2 block text-sm font-semibold text-gray-700">
                    Tanggal Keberangkatan <span class="text-red-500">*</span>
                </label>

                <input
                    type="date"
                    name="departure_date"
                    id="departure_date"
                    value="{{ $departureValue }}"
                    required
                    class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none focus:border-amber-400 focus:ring-2 focus:ring-amber-100"
                >

                @error('departure_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="return_date" class="mb-2 block text-sm font-semibold text-gray-700">
                    Tanggal Kepulangan <span class="text-red-500">*</span>
                </label>

                <input
                    type="date"
                    name="return_date"
                    id="return_date"
                    value="{{ $returnValue }}"
                    required
                    class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none focus:border-amber-400 focus:ring-2 focus:ring-amber-100"
                >

                @error('return_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

        </div>
    </div>


    {{-- KAPASITAS PESERTA --}}
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

        <div class="border-b border-gray-100 px-6 py-4">
            <h2 class="font-semibold text-gray-800">
                Kapasitas Peserta
            </h2>
            <p class="mt-1 text-xs text-gray-500">
                Atur jumlah kursi dan jumlah peserta yang sudah terdaftar.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-5 p-6 md:grid-cols-2">

            <div>
                <label for="total_seats" class="mb-2 block text-sm font-semibold text-gray-700">
                    Total Kursi <span class="text-red-500">*</span>
                </label>

                <input
                    type="number"
                    name="total_seats"
                    id="total_seats"
                    min="1"
                    value="{{ old('total_seats', $schedule?->total_seats ?? 30) }}"
                    required
                    class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none focus:border-amber-400 focus:ring-2 focus:ring-amber-100"
                >

                @error('total_seats')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="booked_seats" class="mb-2 block text-sm font-semibold text-gray-700">
                    Kursi Terisi
                </label>

                <input
                    type="number"
                    name="booked_seats"
                    id="booked_seats"
                    min="0"
                    value="{{ old('booked_seats', $schedule?->booked_seats ?? 0) }}"
                    class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none focus:border-amber-400 focus:ring-2 focus:ring-amber-100"
                >

                @error('booked_seats')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <div class="rounded-lg bg-amber-50 px-4 py-3">
                    <p class="text-xs leading-6 text-amber-800">
                        <strong>Informasi:</strong> Sisa kursi dihitung otomatis dari total kursi dikurangi kursi terisi. Status ketersediaan akan mengikuti jumlah kursi dan tanggal keberangkatan.
                    </p>
                </div>
            </div>

        </div>
    </div>


    {{-- DESKRIPSI --}}
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

        <div class="border-b border-gray-100 px-6 py-4">
            <h2 class="font-semibold text-gray-800">
                Deskripsi Perjalanan
            </h2>
            <p class="mt-1 text-xs text-gray-500">
                Informasi tambahan mengenai jadwal perjalanan.
            </p>
        </div>

        <div class="p-6">

            <label for="description" class="mb-2 block text-sm font-semibold text-gray-700">
                Deskripsi
            </label>

            <textarea
                name="description"
                id="description"
                rows="5"
                placeholder="Tuliskan informasi tambahan mengenai perjalanan..."
                class="w-full resize-y rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none focus:border-amber-400 focus:ring-2 focus:ring-amber-100"
            >{{ old('description', $schedule?->description) }}</textarea>

            @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror

        </div>
    </div>


    {{-- PENGATURAN PUBLIKASI --}}
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

        <div class="border-b border-gray-100 px-6 py-4">
            <h2 class="font-semibold text-gray-800">
                Pengaturan Publikasi
            </h2>
            <p class="mt-1 text-xs text-gray-500">
                Atur status dan urutan jadwal pada website.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-5 p-6 md:grid-cols-2">

            <div>
                <label for="status" class="mb-2 block text-sm font-semibold text-gray-700">
                    Status Publikasi <span class="text-red-500">*</span>
                </label>

                <select
                    name="status"
                    id="status"
                    required
                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm outline-none focus:border-amber-400 focus:ring-2 focus:ring-amber-100"
                >
                    <option value="draft"
                        {{ old('status', $schedule?->status ?? 'draft') === 'draft' ? 'selected' : '' }}>
                        Draft
                    </option>

                    <option value="published"
                        {{ old('status', $schedule?->status) === 'published' ? 'selected' : '' }}>
                        Published
                    </option>
                </select>

                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="sort_order" class="mb-2 block text-sm font-semibold text-gray-700">
                    Urutan Tampilan
                </label>

                <input
                    type="number"
                    name="sort_order"
                    id="sort_order"
                    min="0"
                    value="{{ old('sort_order', $schedule?->sort_order ?? ($nextSortOrder ?? 1)) }}"
                    class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none focus:border-amber-400 focus:ring-2 focus:ring-amber-100"
                >

                @error('sort_order')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">

                <label class="flex cursor-pointer items-start gap-3 rounded-lg border border-gray-200 p-4">

                    <input
                        type="checkbox"
                        name="is_visible"
                        value="1"
                        {{ old('is_visible', $schedule?->is_visible ?? true) ? 'checked' : '' }}
                        class="mt-1 h-4 w-4 rounded border-gray-300 text-amber-500 focus:ring-amber-400"
                    >

                    <span>
                        <span class="block text-sm font-semibold text-gray-800">
                            Tampilkan di Website
                        </span>

                        <span class="mt-1 block text-xs text-gray-500">
                            Aktifkan agar jadwal dapat dilihat oleh pengunjung website ketika statusnya Published.
                        </span>
                    </span>

                </label>

                @error('is_visible')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror

            </div>

        </div>
    </div>

</div>
