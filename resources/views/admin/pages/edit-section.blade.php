
@extends('admin.layouts.app')

@section('title', 'Edit Section')
@section('page-heading', 'Edit Section')

@section('content')

@php
    $existingCards = old('cards', $section->content['cards'] ?? []);

    if (empty($existingCards)) {
        $existingCards = [
            ['icon' => '', 'title' => '', 'description' => '']
        ];
    }

    $existingStatistics = old('statistics', $section->content['items'] ?? []);

    if (empty($existingStatistics)) {
        $existingStatistics = [
            ['value' => '', 'label' => '']
        ];
    }
@endphp

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">
        Edit Section {{ $page->title }}
    </h1>

    <p class="mt-2 text-sm text-gray-500">
        Perbarui informasi dan konten section halaman {{ $page->title }}.
    </p>
</div>

<div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">

    <form action="{{ route('admin.pages.sections.update', [$page->id, $section->id]) }}"
          method="POST">

        @csrf
        @method('PUT')

        {{-- TIPE SECTION --}}
        <div class="mb-5">
            <label class="mb-2 block text-sm font-semibold text-gray-700">
                Tipe Section
            </label>

            <select name="section_type" id="section_type"
                    class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm">

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

                    <option value="{{ $value }}"
                        {{ old('section_type', $section->section_type) === $value ? 'selected' : '' }}>
                        {{ $label }}
                    </option>

                @endforeach
            </select>

            @error('section_type')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- JUDUL SECTION --}}
        <div class="mb-5">
            <label class="mb-2 block text-sm font-semibold text-gray-700">
                Judul Section
            </label>

            <input type="text"
                   name="title"
                   value="{{ old('title', $section->title) }}"
                   class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm">

            @error('title')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- SUBJUDUL --}}
        <div class="mb-5">
            <label class="mb-2 block text-sm font-semibold text-gray-700">
                Subjudul
            </label>

            <input type="text"
                   name="subtitle"
                   value="{{ old('subtitle', $section->subtitle) }}"
                   class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm">

            @error('subtitle')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- DESKRIPSI UMUM --}}
        <div class="mb-5" id="description-container">
            <label class="mb-2 block text-sm font-semibold text-gray-700">
                Konten / Deskripsi Pengantar
            </label>

            <textarea name="content"
                      rows="5"
                      class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm">{{ old('content', $section->content['description'] ?? '') }}</textarea>

            <p class="mt-1 text-xs text-gray-500">
                Deskripsi pengantar yang ditampilkan pada section.
            </p>

            @error('content')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- FORM CARD --}}
        <div id="cards-container" class="mb-6 hidden">

            <div class="mb-4 flex items-center justify-between">
                <div>
                    <h3 id="cards-heading" class="font-bold text-gray-800">
                        Daftar Card
                    </h3>

                    <p id="cards-description" class="text-xs text-gray-500">
                        Atur card yang ditampilkan pada section.
                    </p>
                </div>

                <button type="button"
                        id="add-card"
                        class="rounded-lg bg-amber-400 px-4 py-2 text-sm font-semibold text-black hover:bg-amber-500">
                    + Tambah Card
                </button>
            </div>

            <div id="cards-list" class="space-y-4">

                @foreach ($existingCards as $index => $card)
                    <div class="card-item rounded-xl border border-gray-200 p-5">

                        <div class="mb-4 flex items-center justify-between">
                            <h4 class="card-number font-semibold text-gray-800">
                                Card {{ $loop->iteration }}
                            </h4>

                            <button type="button"
                                    class="remove-card text-sm font-semibold text-red-600 hover:text-red-800">
                                Hapus Card
                            </button>
                        </div>

                        {{-- IKON KHUSUS WHY CHOOSE US --}}
                        @if (old('section_type', $section->section_type) === 'why_choose_us')
                            <div class="icon-field mb-4">
                                <label class="mb-2 block text-sm font-semibold text-gray-700">
                                    Ikon (Emoji)
                                </label>

                                <input type="text"
                                       name="cards[{{ $index }}][icon]"
                                       value="{{ $card['icon'] ?? '' }}"
                                       placeholder="Contoh: 🛡️"
                                       class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm">
                            </div>
                        @endif

                        <div class="mb-4">
                            <label class="mb-2 block text-sm font-semibold text-gray-700">
                                Judul Card
                            </label>

                            <input type="text"
                                   name="cards[{{ $index }}][title]"
                                   value="{{ $card['title'] ?? '' }}"
                                   class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm">
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-700">
                                Deskripsi Card
                            </label>

                            <textarea name="cards[{{ $index }}][description]"
                                      rows="3"
                                      class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm">{{ $card['description'] ?? '' }}</textarea>
                        </div>

                    </div>
                @endforeach

            </div>

            @error('cards')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- FORM STATISTIK --}}
        <div id="statistics-container" class="mb-6 hidden">

            <div class="mb-4 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-gray-800">
                        Daftar Statistik
                    </h3>

                    <p class="text-xs text-gray-500">
                        Atur angka dan keterangan yang ditampilkan di Beranda.
                    </p>
                </div>

                <button type="button"
                        id="add-statistic"
                        class="rounded-lg bg-amber-400 px-4 py-2 text-sm font-semibold text-black hover:bg-amber-500">
                    + Tambah Statistik
                </button>
            </div>

            <div id="statistics-list" class="space-y-4">

                @foreach ($existingStatistics as $index => $statistic)
                    <div class="statistic-item rounded-xl border border-gray-200 p-5">

                        <div class="mb-4 flex items-center justify-between">
                            <h4 class="statistic-number font-semibold text-gray-800">
                                Statistik {{ $loop->iteration }}
                            </h4>

                            <button type="button"
                                    class="remove-statistic text-sm font-semibold text-red-600 hover:text-red-800">
                                Hapus Statistik
                            </button>
                        </div>

                        <div class="mb-4">
                            <label class="mb-2 block text-sm font-semibold text-gray-700">
                                Nilai Statistik
                            </label>

                            <input type="text"
                                   name="statistics[{{ $index }}][value]"
                                   value="{{ $statistic['value'] ?? '' }}"
                                   placeholder="Contoh: 1000+"
                                   class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm">

                            @error("statistics.$index.value")
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-700">
                                Label Statistik
                            </label>

                            <input type="text"
                                   name="statistics[{{ $index }}][label]"
                                   value="{{ $statistic['label'] ?? '' }}"
                                   placeholder="Contoh: Jamaah Terlayani"
                                   class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm">

                            @error("statistics.$index.label")
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                @endforeach

            </div>

            @error('statistics')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- URUTAN SECTION --}}
        <div class="mb-6">
            <label class="mb-2 block text-sm font-semibold text-gray-700">
                Urutan Section
            </label>

            <input type="number"
                   name="sort_order"
                   min="0"
                   value="{{ old('sort_order', $section->sort_order) }}"
                   class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm">

            @error('sort_order')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- TOMBOL --}}
        <div class="flex flex-wrap gap-3">

            <button type="submit"
                    class="rounded-lg bg-[#FFB22C] px-5 py-3 text-sm font-semibold text-black hover:opacity-80">
                Simpan Perubahan
            </button>

            <a href="{{ route('admin.pages.sections', $page->id) }}"
               class="rounded-lg bg-gray-200 px-5 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-300">
                Batal
            </a>

        </div>

    </form>
</div>

{{-- SCRIPT FORM DINAMIS --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const sectionType = document.getElementById('section_type');

    const descriptionContainer = document.getElementById('description-container');
    const cardsContainer = document.getElementById('cards-container');
    const statisticsContainer = document.getElementById('statistics-container');

    const cardsList = document.getElementById('cards-list');
    const statisticsList = document.getElementById('statistics-list');

    const cardsHeading = document.getElementById('cards-heading');
    const cardsDescription = document.getElementById('cards-description');

    // Menyesuaikan tampilan berdasarkan tipe section
    function updateFormVisibility() {
        const type = sectionType.value;

        const isCardSection = ['why_choose_us', 'services'].includes(type);
        const isWhyChooseUs = type === 'why_choose_us';

        cardsContainer.classList.toggle('hidden', !isCardSection);

        statisticsContainer.classList.toggle(
            'hidden',
            type !== 'statistics'
        );

        descriptionContainer.classList.toggle(
            'hidden',
            type === 'statistics'
        );

        if (isWhyChooseUs) {
            cardsHeading.textContent = 'Daftar Card Alasan';
            cardsDescription.textContent =
                'Tambahkan alasan memilih Ansar Travel.';
        } else {
            cardsHeading.textContent = 'Daftar Card Layanan';
            cardsDescription.textContent =
                'Tambahkan layanan yang disediakan Ansar Travel.';
        }

        // Ikon hanya diperlukan untuk why_choose_us
        syncCardIconFields(isWhyChooseUs);
    }

    // Menampilkan atau menghilangkan input ikon
    function syncCardIconFields(showIcon) {
        const cards = cardsList.querySelectorAll('.card-item');

        cards.forEach(card => {
            let iconField = card.querySelector('.icon-field');

            if (showIcon && !iconField) {
                iconField = document.createElement('div');
                iconField.className = 'icon-field mb-4';

                iconField.innerHTML = `
                    <label class="mb-2 block text-sm font-semibold text-gray-700">
                        Ikon (Emoji)
                    </label>

                    <input type="text"
                           name="cards[0][icon]"
                           placeholder="Contoh: 🛡️"
                           class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm">
                `;

                card.insertBefore(iconField, card.querySelector('.mb-4:nth-of-type(2)'));
            }

            if (!showIcon && iconField) {
                iconField.remove();
            }
        });

        updateCardNumbers();
    }

    // Memperbarui nomor dan index card
    function updateCardNumbers() {
        const cards = cardsList.querySelectorAll('.card-item');

        cards.forEach((card, index) => {
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

    // Memperbarui nomor dan index statistik
    function updateStatisticNumbers() {
        const statistics = statisticsList.querySelectorAll('.statistic-item');

        statistics.forEach((item, index) => {
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

    // Membuat card baru
    function createCard(index) {
        const card = document.createElement('div');

        card.className = 'card-item rounded-xl border border-gray-200 p-5';

        const iconField = sectionType.value === 'why_choose_us'
            ? `
                <div class="icon-field mb-4">
                    <label class="mb-2 block text-sm font-semibold text-gray-700">
                        Ikon (Emoji)
                    </label>

                    <input type="text"
                           name="cards[${index}][icon]"
                           placeholder="Contoh: 🛡️"
                           class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm">
                </div>
            `
            : '';

        card.innerHTML = `
            <div class="mb-4 flex items-center justify-between">
                <h4 class="card-number font-semibold text-gray-800">
                    Card ${index + 1}
                </h4>

                <button type="button"
                        class="remove-card text-sm font-semibold text-red-600">
                    Hapus Card
                </button>
            </div>

            ${iconField}

            <div class="mb-4">
                <label class="mb-2 block text-sm font-semibold text-gray-700">
                    Judul Card
                </label>

                <input type="text"
                       name="cards[${index}][title]"
                       class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm">
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-gray-700">
                    Deskripsi Card
                </label>

                <textarea name="cards[${index}][description]"
                          rows="3"
                          class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm"></textarea>
            </div>
        `;

        return card;
    }

    // Membuat statistik baru
    function createStatistic(index) {
        const item = document.createElement('div');

        item.className = 'statistic-item rounded-xl border border-gray-200 p-5';

        item.innerHTML = `
            <div class="mb-4 flex items-center justify-between">
                <h4 class="statistic-number font-semibold text-gray-800">
                    Statistik ${index + 1}
                </h4>

                <button type="button"
                        class="remove-statistic text-sm font-semibold text-red-600">
                    Hapus Statistik
                </button>
            </div>

            <div class="mb-4">
                <label class="mb-2 block text-sm font-semibold text-gray-700">
                    Nilai Statistik
                </label>

                <input type="text"
                       name="statistics[${index}][value]"
                       placeholder="Contoh: 1000+"
                       class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm">
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-gray-700">
                    Label Statistik
                </label>

                <input type="text"
                       name="statistics[${index}][label]"
                       placeholder="Contoh: Jamaah Terlayani"
                       class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm">
            </div>
        `;

        return item;
    }

    // Tambah card
    document.getElementById('add-card').addEventListener('click', function () {
        const index = cardsList.querySelectorAll('.card-item').length;

        cardsList.appendChild(createCard(index));
        updateCardNumbers();
    });

    // Hapus card
    cardsList.addEventListener('click', function (event) {
        if (event.target.classList.contains('remove-card')) {
            const cards = cardsList.querySelectorAll('.card-item');

            if (cards.length <= 1) {
                alert('Minimal harus ada satu card.');
                return;
            }

            event.target.closest('.card-item').remove();
            updateCardNumbers();
        }
    });

    // Tambah statistik
    document.getElementById('add-statistic').addEventListener('click', function () {
        const index = statisticsList.querySelectorAll('.statistic-item').length;

        statisticsList.appendChild(createStatistic(index));
        updateStatisticNumbers();
    });

    // Hapus statistik
    statisticsList.addEventListener('click', function (event) {
        if (event.target.classList.contains('remove-statistic')) {
            const items = statisticsList.querySelectorAll('.statistic-item');

            if (items.length <= 1) {
                alert('Minimal harus ada satu statistik.');
                return;
            }

            event.target.closest('.statistic-item').remove();
            updateStatisticNumbers();
        }
    });

    sectionType.addEventListener('change', updateFormVisibility);

    updateFormVisibility();
    updateCardNumbers();
    updateStatisticNumbers();

});
</script>

@endsection
