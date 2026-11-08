<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\PageSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PageController extends Controller
{
    // =========================================================
    // MANAJEMEN HALAMAN
    // =========================================================

    // Menampilkan daftar halaman
    public function index()
    {
        $pages = Page::withCount('sections')
            ->orderBy('id')
            ->get();

        return view('admin.pages.index', compact('pages'));
    }

    // Form tambah halaman
    public function create()
    {
        return view('admin.pages.create');
    }

    // Menyimpan halaman baru
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

    // Form edit halaman
    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    // Memperbarui halaman
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

    // Menampilkan daftar section
    public function sections(Page $page)
    {
        $sections = $page->sections()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('admin.pages.sections', compact('page', 'sections'));
    }

    // Form tambah section
    public function createSection(Page $page)
    {
        return view('admin.pages.create-section', compact('page'));
    }

    // Aturan validasi section
    private function sectionValidationRules(Request $request): array
    {
        $rules = [
            'section_type' => ['required', 'string', 'max:100'],
            'title' => ['nullable', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'content' => ['nullable', 'string'],

            // Gambar section
            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            // Tombol section
            'button_text' => ['nullable', 'string', 'max:255'],
            'button_url' => ['nullable', 'string', 'max:2048'],

            // Menghapus gambar lama ketika edit
            'remove_image' => ['nullable', 'boolean'],

            'sort_order' => ['required', 'integer', 'min:0'],
        ];

        // Validasi card: Mengapa Memilih dan Layanan
        if (in_array($request->input('section_type'), [
            'why_choose_us',
            'services',
        ])) {
            $rules['cards'] = ['required', 'array', 'min:1'];
            $rules['cards.*.title'] = ['required', 'string', 'max:255'];
            $rules['cards.*.description'] = ['required', 'string', 'max:1000'];

            if ($request->input('section_type') === 'why_choose_us') {
                $rules['cards.*.icon'] = ['nullable', 'string', 'max:30'];
            }
        }

        // Validasi statistik
        if ($request->input('section_type') === 'statistics') {
            $rules['statistics'] = ['required', 'array', 'min:1'];
            $rules['statistics.*.value'] = ['required', 'string', 'max:50'];
            $rules['statistics.*.label'] = ['required', 'string', 'max:255'];
        }

        return $rules;
    }

    // Menyiapkan format konten section
    private function prepareSectionContent(array $validated): ?array
    {
        // Konten card
        if (in_array($validated['section_type'], [
            'why_choose_us',
            'services',
        ])) {
            return [
                'description' => $validated['content'] ?? '',
                'cards' => array_values($validated['cards']),
            ];
        }

        // Konten statistik
        if ($validated['section_type'] === 'statistics') {
            return [
                'items' => array_values($validated['statistics']),
            ];
        }

        // Konten umum, termasuk Hero dan Tentang Kami
        return !empty($validated['content'])
            ? ['description' => $validated['content']]
            : null;
    }

    // Menyimpan section baru
    public function storeSection(Request $request, Page $page)
    {
        $validated = $request->validate(
            $this->sectionValidationRules($request)
        );

        // Upload gambar apabila tersedia
        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')
                ->store('page-sections', 'public');
        }

        $page->sections()->create([
            'section_type' => $validated['section_type'],
            'title' => $validated['title'] ?? null,
            'subtitle' => $validated['subtitle'] ?? null,
            'content' => $this->prepareSectionContent($validated),

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

    // Form edit section
    public function editSection(Page $page, PageSection $section)
    {
        abort_unless($section->page_id === $page->id, 404);

        return view('admin.pages.edit-section', compact('page', 'section'));
    }

    // Memperbarui section
    public function updateSection(
        Request $request,
        Page $page,
        PageSection $section
    ) {
        abort_unless($section->page_id === $page->id, 404);

        $validated = $request->validate(
            $this->sectionValidationRules($request)
        );

        $oldImagePath = $section->image_path;
        $newImagePath = $oldImagePath;

        // Jika admin mengunggah gambar baru
        if ($request->hasFile('image')) {
            $newImagePath = $request->file('image')
                ->store('page-sections', 'public');

            // Hapus gambar lama setelah gambar baru tersimpan
            if ($oldImagePath) {
                Storage::disk('public')->delete($oldImagePath);
            }
        }

        // Jika admin memilih untuk menghapus gambar
        elseif ($request->boolean('remove_image')) {
            if ($oldImagePath) {
                Storage::disk('public')->delete($oldImagePath);
            }

            $newImagePath = null;
        }

        $section->update([
            'section_type' => $validated['section_type'],
            'title' => $validated['title'] ?? null,
            'subtitle' => $validated['subtitle'] ?? null,
            'content' => $this->prepareSectionContent($validated),

            'image_path' => $newImagePath,
            'button_text' => $validated['button_text'] ?? null,
            'button_url' => $validated['button_url'] ?? null,

            'sort_order' => $validated['sort_order'],
        ]);

        return redirect()
            ->route('admin.pages.sections', $page->id)
            ->with('success', 'Section berhasil diperbarui.');
    }

    // Menghapus section
    public function destroySection(Page $page, PageSection $section)
    {
        abort_unless($section->page_id === $page->id, 404);

        // Hapus gambar section dari storage
        if ($section->image_path) {
            Storage::disk('public')->delete($section->image_path);
        }

        $section->delete();

        return redirect()
            ->route('admin.pages.sections', $page->id)
            ->with('success', 'Section berhasil dihapus.');
    }

    // Memperbarui status dan visibilitas
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

    // Memperbarui urutan section
    public function updateSectionOrder(
        Request $request,
        Page $page,
        PageSection $section
    ) {
        abort_unless($section->page_id === $page->id, 404);

        $validated = $request->validate([
            'sort_order' => ['required', 'integer', 'min:1'],
        ]);

        $section->update([
            'sort_order' => $validated['sort_order'],
        ]);

        return redirect()
            ->route('admin.pages.sections', $page->id)
            ->with('success', 'Urutan section berhasil diperbarui.');
    }
}
