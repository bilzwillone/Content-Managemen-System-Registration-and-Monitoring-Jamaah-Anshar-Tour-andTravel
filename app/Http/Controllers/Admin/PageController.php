<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\PageSection;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PageController extends Controller
{
    // Menampilkan daftar halaman
    public function index()
    {
        $pages = Page::withCount('sections')
            ->orderBy('id')
            ->get();

        return view('admin.pages.index', compact('pages'));
    }

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
            'section_type' => 'required|string|max:100',
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'sort_order' => 'required|integer|min:0',
        ];

        // Card untuk Mengapa Memilih dan Layanan
        if (in_array($request->input('section_type'), [
            'why_choose_us',
            'services',
        ])) {
            $rules['cards'] = 'required|array|min:1';
            $rules['cards.*.title'] = 'required|string|max:255';
            $rules['cards.*.description'] = 'required|string|max:1000';

            // Ikon hanya digunakan pada why_choose_us
            if ($request->input('section_type') === 'why_choose_us') {
                $rules['cards.*.icon'] = 'nullable|string|max:30';
            }
        }

        // Statistik
        if ($request->input('section_type') === 'statistics') {
            $rules['statistics'] = 'required|array|min:1';
            $rules['statistics.*.value'] = 'required|string|max:50';
            $rules['statistics.*.label'] = 'required|string|max:255';
        }

        return $rules;
    }

    // Menyiapkan format konten
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

    // Menyimpan section baru
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

    // Menghapus section
    public function destroySection(Page $page, PageSection $section)
    {
        abort_unless($section->page_id === $page->id, 404);

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
