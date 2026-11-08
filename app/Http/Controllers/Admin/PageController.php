<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\PageSection;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PageController extends Controller
{

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
            $slug = $baseSlug;
            $counter = 1;

            while (Page::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }

            $validated['slug'] = $slug;
            $validated['is_published'] = $request->boolean('is_published');
            $validated['show_in_navbar'] = $request->boolean('show_in_navbar');
            $validated['nav_order'] = $validated['nav_order']
                ?? ((Page::max('nav_order') ?? 0) + 1);

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
        return view('admin.pages.create-section', compact('page'));
    }

    private function sectionValidationRules(Request $request): array
    {
        $rules = [
            'section_type' => 'required|string|max:100',
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'sort_order' => 'required|integer|min:0',
        ];

        if (in_array($request->input('section_type'), [
            'why_choose_us',
            'services',
        ])) {
            $rules['cards'] = 'required|array|min:1';
            $rules['cards.*.title'] = 'required|string|max:255';
            $rules['cards.*.description'] = 'required|string|max:1000';

            if ($request->input('section_type') === 'why_choose_us') {
                $rules['cards.*.icon'] = 'nullable|string|max:30';
            }
        }

        if ($request->input('section_type') === 'statistics') {
            $rules['statistics'] = 'required|array|min:1';
            $rules['statistics.*.value'] = 'required|string|max:50';
            $rules['statistics.*.label'] = 'required|string|max:255';
        }

        return $rules;
    }

    private function prepareSectionContent(array $validated): ?array
    {
        if (in_array($validated['section_type'], [
            'why_choose_us',
            'services',
        ])) {
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

        return !empty($validated['content'])
            ? ['description' => $validated['content']]
            : null;
    }

    public function storeSection(Request $request, Page $page)
    {
        $validated = $request->validate(
            $this->sectionValidationRules($request)
        );

        $page->sections()->create([
            'section_type' => $validated['section_type'],
            'title' => $validated['title'] ?? null,
            'subtitle' => $validated['subtitle'] ?? null,
            'content' => $this->prepareSectionContent($validated),
            'sort_order' => $validated['sort_order'],
            'status' => 'draft',
            'is_visible' => true,
        ]);

        return redirect()
            ->route('admin.pages.sections', $page->id)
            ->with('success', 'Section berhasil ditambahkan.');
    }

    public function editSection(Page $page, PageSection $section)
    {
        abort_unless($section->page_id === $page->id, 404);

        return view('admin.pages.edit-section', compact('page', 'section'));
    }

    public function updateSection(
        Request $request,
        Page $page,
        PageSection $section
    ) {
        abort_unless($section->page_id === $page->id, 404);

        $validated = $request->validate(
            $this->sectionValidationRules($request)
        );

        $section->update([
            'section_type' => $validated['section_type'],
            'title' => $validated['title'] ?? null,
            'subtitle' => $validated['subtitle'] ?? null,
            'content' => $this->prepareSectionContent($validated),
            'sort_order' => $validated['sort_order'],
        ]);

        return redirect()
            ->route('admin.pages.sections', $page->id)
            ->with('success', 'Section berhasil diperbarui.');
    }

    public function destroySection(Page $page, PageSection $section)
    {
        abort_unless($section->page_id === $page->id, 404);

        $section->delete();

        return redirect()
            ->route('admin.pages.sections', $page->id)
            ->with('success', 'Section berhasil dihapus.');
    }

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

    public function updateSectionOrder(
        Request $request,
        Page $page,
        PageSection $section
    ) {
        abort_unless($section->page_id === $page->id, 404);

        $validated = $request->validate([
            'sort_order' => 'required|integer|min:1',
        ]);

        $section->update([
            'sort_order' => $validated['sort_order'],
        ]);

        return redirect()
            ->route('admin.pages.sections', $page->id)
            ->with('success', 'Urutan section berhasil diperbarui.');
    }
}
