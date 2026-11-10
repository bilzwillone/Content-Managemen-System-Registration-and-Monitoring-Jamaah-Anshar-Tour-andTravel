<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\PageSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PageController extends Controller
{
    // =========================================================
    // MANAJEMEN HALAMAN
    // =========================================================

    public function index()
    {
        $pages = Page::withCount('sections')
            ->orderBy('id')
            ->get();

        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.pages.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_published' => ['nullable', 'boolean'],
            'show_in_navbar' => ['nullable', 'boolean'],
            'nav_order' => ['nullable', 'integer', 'min:0'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
        ]);

        $baseSlug = Str::slug($validated['slug'] ?: $validated['title']);

        if ($baseSlug === '') {
            $baseSlug = 'halaman';
        }

        $slug = $baseSlug;
        $counter = 1;

        while (Page::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        $validated['slug'] = $slug;
        $validated['is_published'] = $request->boolean('is_published');
        $validated['show_in_navbar'] = $request->boolean('show_in_navbar');
        $validated['nav_order'] = $request->filled('nav_order')
            ? (int) $validated['nav_order']
            : ((Page::max('nav_order') ?? 0) + 1);

        Page::create($validated);

        return redirect()
            ->route('admin.pages.index')
            ->with('success', 'Halaman website berhasil ditambahkan.');
    }

    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_published' => ['nullable', 'boolean'],
            'show_in_navbar' => ['nullable', 'boolean'],
            'nav_order' => ['nullable', 'integer', 'min:0'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
        ]);

        $baseSlug = Str::slug($validated['slug'] ?: $validated['title']);

        if ($baseSlug === '') {
            $baseSlug = 'halaman';
        }

        $slug = $baseSlug;
        $counter = 1;

        while (
            Page::where('slug', $slug)
                ->where('id', '!=', $page->id)
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        $validated['slug'] = $slug;
        $validated['is_published'] = $request->boolean('is_published');
        $validated['show_in_navbar'] = $request->boolean('show_in_navbar');
        $validated['nav_order'] = $request->filled('nav_order')
            ? (int) $validated['nav_order']
            : $page->nav_order;

        $page->update($validated);

        return redirect()
            ->route('admin.pages.index')
            ->with('success', 'Halaman website berhasil diperbarui.');
    }

    // =========================================================
    // MANAJEMEN SECTION
    // =========================================================

    public function sections(Page $page)
    {
        $sections = $page->sections()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('admin.pages.sections', compact('page', 'sections'));
    }

    public function createSection(Page $page)
    {
        // Sarankan nomor urutan berikutnya yang belum terpakai.
        $nextSortOrder = ((int) $page->sections()->max('sort_order')) + 1;

        return view('admin.pages.create-section', compact('page', 'nextSortOrder'));
    }

    // =========================================================
    // VALIDASI SECTION
    // =========================================================

    /**
     * Nomor urutan harus unik di dalam halaman yang sama.
     * Saat edit, nomor milik section yang sedang diedit tetap diizinkan.
     */
    private function sectionValidationRules(
        Request $request,
        Page $page,
        ?PageSection $section = null
    ): array {
        $sortOrderRule = Rule::unique('page_sections', 'sort_order')
            ->where(fn ($query) => $query->where('page_id', $page->id));

        if ($section) {
            $sortOrderRule->ignore($section->id);
        }

        $rules = [
            'section_type' => ['required', 'string', 'max:100'],
            'title' => ['nullable', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
            'button_text' => ['nullable', 'string', 'max:255'],
            'button_url' => ['nullable', 'string', 'max:2048'],
            'remove_image' => ['nullable', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:1', $sortOrderRule],
        ];

        // Card: Mengapa Memilih dan Layanan.
        if (in_array($request->input('section_type'), [
            'why_choose_us',
            'services',
        ], true)) {
            $rules['cards'] = ['required', 'array', 'min:1'];
            $rules['cards.*.title'] = ['required', 'string', 'max:255'];
            $rules['cards.*.description'] = ['required', 'string', 'max:1000'];

            if ($request->input('section_type') === 'why_choose_us') {
                $rules['cards.*.icon'] = ['nullable', 'string', 'max:30'];
            }
        }

        // Statistik.
        if ($request->input('section_type') === 'statistics') {
            $rules['statistics'] = ['required', 'array', 'min:1'];
            $rules['statistics.*.value'] = ['required', 'string', 'max:50'];
            $rules['statistics.*.label'] = ['required', 'string', 'max:255'];
        }

        // Testimoni.
        if ($request->input('section_type') === 'testimonials') {
            $rules['testimonials'] = ['required', 'array', 'min:1'];
            $rules['testimonials.*.id'] = ['nullable', 'string', 'max:36'];
            $rules['testimonials.*.name'] = ['required', 'string', 'max:255'];
            $rules['testimonials.*.origin'] = ['nullable', 'string', 'max:255'];
            $rules['testimonials.*.rating'] = ['required', 'integer', 'min:1', 'max:5'];
            $rules['testimonials.*.message'] = ['required', 'string', 'max:2000'];
            $rules['testimonials.*.photo'] = [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ];
            $rules['testimonials.*.remove_photo'] = ['nullable', 'boolean'];
        }



        // Galeri: maksimal 20 foto dalam satu section.
        if ($request->input('section_type') === 'gallery') {
            $rules['gallery'] = ['required', 'array', 'min:1', 'max:20'];
            $rules['gallery.*.id'] = ['nullable', 'string', 'max:36'];
            $rules['gallery.*.caption'] = ['nullable', 'string', 'max:255'];
            $rules['gallery.*.alt'] = ['nullable', 'string', 'max:255'];
            $rules['gallery.*.sort_order'] = ['required', 'integer', 'min:1', 'max:999'];
            $rules['gallery.*.image'] = [
                $section ? 'nullable' : 'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:4096',
            ];
        }


        // Kontak perusahaan.
        if ($request->input('section_type') === 'contact') {
            $rules['contact'] = ['required', 'array'];
            $rules['contact.whatsapp'] = ['nullable', 'string', 'max:40'];
            $rules['contact.phone'] = ['nullable', 'string', 'max:40'];
            $rules['contact.email'] = ['nullable', 'email', 'max:255'];
            $rules['contact.address'] = ['nullable', 'string', 'max:2000'];
            $rules['contact.office_hours'] = ['nullable', 'string', 'max:500'];
            $rules['contact.maps_url'] = ['nullable', 'url', 'max:2048'];
            $rules['contact.website'] = ['nullable', 'url', 'max:2048'];
            $rules['contact.instagram'] = ['nullable', 'url', 'max:2048'];
            $rules['contact.facebook'] = ['nullable', 'url', 'max:2048'];
        }

        return $rules;
    }

    // =========================================================
    // MENYIAPKAN KONTEN SECTION
    // =========================================================

    private function prepareSectionContent(array $validated): ?array
    {
        if (in_array($validated['section_type'], [
            'why_choose_us',
            'services',
        ], true)) {
            return [
                'description' => $validated['content'] ?? '',
                'cards' => array_values($validated['cards']),
            ];
        }

        if ($validated['section_type'] === 'statistics') {
            return [
                'items' => array_values($validated['statistics']),
            ];
        }


        if ($validated['section_type'] === 'contact') {
            $contact = array_filter(
                $validated['contact'] ?? [],
                fn ($value) => filled($value)
            );

            return [
                'description' => $validated['content'] ?? '',
                'contact' => $contact,
            ];
        }

        return !empty($validated['content'])
            ? ['description' => $validated['content']]
            : null;
    }

    // =========================================================
    // MENYIAPKAN DATA TESTIMONI DAN FOTO
    // =========================================================

    private function prepareTestimonialsContent(
        Request $request,
        array $validated,
        ?PageSection $section = null
    ): array {
        $existingItems = $section
            ? data_get($section->content, 'items', [])
            : [];

        $existingById = [];

        foreach ($existingItems as $existingItem) {
            if (!empty($existingItem['id'])) {
                $existingById[$existingItem['id']] = $existingItem;
            }
        }

        $items = [];
        $retainedIds = [];

        foreach ($validated['testimonials'] as $index => $item) {
            $existing = null;
            $itemId = $item['id'] ?? null;

            if (
                $itemId &&
                isset($existingById[$itemId]) &&
                !in_array($itemId, $retainedIds, true)
            ) {
                $existing = $existingById[$itemId];
                $retainedIds[] = $itemId;
            }

            $photoPath = $existing['photo_path'] ?? null;
            $photo = $request->file("testimonials.$index.photo");

            if ($photo) {
                $newPhotoPath = $photo->store('testimonials', 'public');

                if ($photoPath) {
                    Storage::disk('public')->delete($photoPath);
                }

                $photoPath = $newPhotoPath;
            } elseif ($request->boolean("testimonials.$index.remove_photo")) {
                if ($photoPath) {
                    Storage::disk('public')->delete($photoPath);
                }

                $photoPath = null;
            }

            $items[] = [
                'id' => $existing['id'] ?? (string) Str::uuid(),
                'name' => $item['name'],
                'origin' => $item['origin'] ?? null,
                'rating' => (int) $item['rating'],
                'message' => $item['message'],
                'photo_path' => $photoPath,
            ];
        }

        // Hapus foto data lama jika testimoni tersebut dihapus dari form.
        foreach ($existingItems as $existingItem) {
            $existingId = $existingItem['id'] ?? null;

            if ($existingId && !in_array($existingId, $retainedIds, true)) {
                if (!empty($existingItem['photo_path'])) {
                    Storage::disk('public')->delete($existingItem['photo_path']);
                }
            }
        }

        return [
            'description' => $validated['content'] ?? '',
            'items' => $items,
        ];
    }

    private function deleteTestimonialPhotos(array $items): void
    {
        foreach ($items as $item) {
            if (!empty($item['photo_path'])) {
                Storage::disk('public')->delete($item['photo_path']);
            }
        }
    }

    private function ensureGalleryItemsHaveImages(
        Request $request,
        array $validated,
        ?PageSection $section = null
    ): void {
        $existingItems = ($section && $section->section_type === 'gallery')
            ? data_get($section->content, 'items', [])
            : [];

        $existingById = [];
        foreach ($existingItems as $existingItem) {
            if (!empty($existingItem['id'])) {
                $existingById[$existingItem['id']] = $existingItem;
            }
        }

        foreach ($validated['gallery'] as $index => $item) {
            $itemId = $item['id'] ?? null;
            $hasExistingImage = $itemId
                && isset($existingById[$itemId])
                && !empty($existingById[$itemId]['image_path']);
            $hasNewUpload = $request->hasFile("gallery.$index.image");

            if (!$hasExistingImage && !$hasNewUpload) {
                throw ValidationException::withMessages([
                    "gallery.$index.image" => 'Pilih foto untuk setiap item galeri yang baru.',
                ]);
            }
        }
    }

    private function prepareGalleryContent(
        Request $request,
        array $validated,
        ?PageSection $section = null
    ): array {
        $existingItems = ($section && $section->section_type === 'gallery')
            ? data_get($section->content, 'items', [])
            : [];

        $existingById = [];
        foreach ($existingItems as $existingItem) {
            if (!empty($existingItem['id'])) {
                $existingById[$existingItem['id']] = $existingItem;
            }
        }

        $items = [];
        $retainedIds = [];

        foreach ($validated['gallery'] as $index => $item) {
            $itemId = $item['id'] ?? null;
            $existing = null;

            if ($itemId && isset($existingById[$itemId]) && !in_array($itemId, $retainedIds, true)) {
                $existing = $existingById[$itemId];
                $retainedIds[] = $itemId;
            }

            $imagePath = $existing['image_path'] ?? null;
            $uploadedImage = $request->file("gallery.$index.image");

            if ($uploadedImage) {
                $newImagePath = $uploadedImage->store('galleries', 'public');
                if ($imagePath) {
                    Storage::disk('public')->delete($imagePath);
                }
                $imagePath = $newImagePath;
            }

            $items[] = [
                'id' => $existing['id'] ?? (string) Str::uuid(),
                'image_path' => $imagePath,
                'caption' => $item['caption'] ?? null,
                'alt' => $item['alt'] ?? ($item['caption'] ?? 'Foto galeri Ansar Travel'),
                'sort_order' => (int) ($item['sort_order'] ?? ($index + 1)),
            ];
        }

        // Foto item lama yang sudah dihapus dari form ikut dihapus dari storage.
        foreach ($existingItems as $existingItem) {
            $existingId = $existingItem['id'] ?? null;
            if ($existingId && !in_array($existingId, $retainedIds, true)) {
                if (!empty($existingItem['image_path'])) {
                    Storage::disk('public')->delete($existingItem['image_path']);
                }
            }
        }

        usort($items, fn ($a, $b) => $a['sort_order'] <=> $b['sort_order']);

        return [
            'description' => $validated['content'] ?? '',
            'items' => $items,
        ];
    }

    private function deleteGalleryPhotos(array $items): void
    {
        foreach ($items as $item) {
            if (!empty($item['image_path'])) {
                Storage::disk('public')->delete($item['image_path']);
            }
        }
    }

    // =========================================================
    // MENYIMPAN SECTION BARU
    // =========================================================

    public function storeSection(Request $request, Page $page)
    {
        $validated = $request->validate(
            $this->sectionValidationRules($request, $page),
            [
                'sort_order.unique' => 'Nomor urutan tersebut sudah digunakan oleh section lain pada halaman ini. Gunakan nomor urutan yang berbeda.',
            ]
        );

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('page-sections', 'public');
        }

        if ($validated['section_type'] === 'gallery') {
            $this->ensureGalleryItemsHaveImages($request, $validated);
            $content = $this->prepareGalleryContent($request, $validated);
        } elseif ($validated['section_type'] === 'testimonials') {
            $content = $this->prepareTestimonialsContent($request, $validated);
        } else {
            $content = $this->prepareSectionContent($validated);
        }

        $page->sections()->create([
            'section_type' => $validated['section_type'],
            'title' => $validated['title'] ?? null,
            'subtitle' => $validated['subtitle'] ?? null,
            'content' => $content,
            'image_path' => $imagePath,
            'button_text' => $validated['button_text'] ?? null,
            'button_url' => $validated['button_url'] ?? null,
            'sort_order' => $validated['sort_order'],
            'status' => 'draft',
            'is_visible' => true,
        ]);

        return redirect()
            ->route('admin.pages.sections', $page->id)
            ->with('success', 'Section berhasil ditambahkan.');
    }

    // =========================================================
    // FORM EDIT SECTION
    // =========================================================

    public function editSection(Page $page, PageSection $section)
    {
        abort_unless($section->page_id === $page->id, 404);

        return view('admin.pages.edit-section', compact('page', 'section'));
    }

    // =========================================================
    // MEMPERBARUI SECTION
    // =========================================================

    public function updateSection(
        Request $request,
        Page $page,
        PageSection $section
    ) {
        abort_unless($section->page_id === $page->id, 404);

        $validated = $request->validate(
            $this->sectionValidationRules($request, $page, $section),
            [
                'sort_order.unique' => 'Nomor urutan tersebut sudah digunakan oleh section lain pada halaman ini. Gunakan nomor urutan yang berbeda.',
            ]
        );

        $oldSectionType = $section->section_type;
        $oldImagePath = $section->image_path;
        $newImagePath = $oldImagePath;

        if ($request->hasFile('image')) {
            $newImagePath = $request->file('image')->store('page-sections', 'public');

            if ($oldImagePath) {
                Storage::disk('public')->delete($oldImagePath);
            }
        } elseif ($request->boolean('remove_image')) {
            if ($oldImagePath) {
                Storage::disk('public')->delete($oldImagePath);
            }

            $newImagePath = null;
        }

        if ($validated['section_type'] === 'gallery') {
            $this->ensureGalleryItemsHaveImages($request, $validated, $section);
            $content = $this->prepareGalleryContent($request, $validated, $section);
            if ($oldSectionType === 'testimonials') {
                $this->deleteTestimonialPhotos(data_get($section->content, 'items', []));
            }
        } elseif ($validated['section_type'] === 'testimonials') {
            $content = $this->prepareTestimonialsContent($request, $validated, $section);
            if ($oldSectionType === 'gallery') {
                $this->deleteGalleryPhotos(data_get($section->content, 'items', []));
            }
        } else {
            $content = $this->prepareSectionContent($validated);
            if ($oldSectionType === 'testimonials') {
                $this->deleteTestimonialPhotos(data_get($section->content, 'items', []));
            } elseif ($oldSectionType === 'gallery') {
                $this->deleteGalleryPhotos(data_get($section->content, 'items', []));
            }
        }

        $section->update([
            'section_type' => $validated['section_type'],
            'title' => $validated['title'] ?? null,
            'subtitle' => $validated['subtitle'] ?? null,
            'content' => $content,
            'image_path' => $newImagePath,
            'button_text' => $validated['button_text'] ?? null,
            'button_url' => $validated['button_url'] ?? null,
            'sort_order' => $validated['sort_order'],
        ]);

        return redirect()
            ->route('admin.pages.sections', $page->id)
            ->with('success', 'Section berhasil diperbarui.');
    }

    // =========================================================
    // MENGHAPUS SECTION
    // =========================================================

    public function destroySection(Page $page, PageSection $section)
    {
        abort_unless($section->page_id === $page->id, 404);

        if ($section->image_path) {
            Storage::disk('public')->delete($section->image_path);
        }

        if ($section->section_type === 'testimonials') {
            $this->deleteTestimonialPhotos(data_get($section->content, 'items', []));
        } elseif ($section->section_type === 'gallery') {
            $this->deleteGalleryPhotos(data_get($section->content, 'items', []));
        }

        $section->delete();

        return redirect()
            ->route('admin.pages.sections', $page->id)
            ->with('success', 'Section berhasil dihapus.');
    }

    // =========================================================
    // STATUS DAN VISIBILITAS
    // =========================================================

    public function updateSectionSettings(
        Request $request,
        Page $page,
        PageSection $section
    ) {
        abort_unless($section->page_id === $page->id, 404);

        $validated = $request->validate([
            'status' => ['required', Rule::in(['draft', 'published'])],
            'is_visible' => ['required', 'boolean'],
        ]);

        $section->update([
            'status' => $validated['status'],
            'is_visible' => $validated['is_visible'],
        ]);

        return redirect()
            ->route('admin.pages.sections', $page->id)
            ->with('success', 'Status dan visibilitas section berhasil diperbarui.');
    }

    // =========================================================
    // MENGATUR URUTAN SECTION
    // =========================================================

    public function updateSectionOrder(
        Request $request,
        Page $page,
        PageSection $section
    ) {
        abort_unless($section->page_id === $page->id, 404);

        $sortOrderRule = Rule::unique('page_sections', 'sort_order')
            ->where(fn ($query) => $query->where('page_id', $page->id))
            ->ignore($section->id);

        $validated = $request->validate(
            [
                'sort_order' => ['required', 'integer', 'min:1', $sortOrderRule],
            ],
            [
                'sort_order.unique' => 'Nomor urutan tersebut sudah digunakan oleh section lain pada halaman ini. Gunakan nomor urutan yang berbeda.',
            ]
        );

        $section->update([
            'sort_order' => $validated['sort_order'],
        ]);

        return redirect()
            ->route('admin.pages.sections', $page->id)
            ->with('success', 'Urutan section berhasil diperbarui.');
    }
}
