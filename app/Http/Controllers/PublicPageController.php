<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Package;

class PublicPageController extends Controller
{
    public function show(string $slug)
    {
        $page = Page::query()
            ->where('slug', $slug)
            ->where('is_published', true)
            ->with([
                'sections' => function ($query) {
                    $query->where('status', 'published')
                        ->where('is_visible', true)
                        ->orderBy('sort_order');
                }
            ])
            ->firstOrFail();

        $packages = collect();

        if ($page->sections->contains('section_type', 'packages')) {
            $packages = Package::query()
                ->where('status', 'published')
                ->where('is_visible', true)
                ->latest()
                ->get();
        }

        return view('pages.show', compact('page', 'packages'));
    }
}
