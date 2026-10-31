<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::withCount('sections')
            ->orderBy('id')
            ->get();

        return view('admin.pages.index', compact('pages'));
    }

    public function sections(Page $page)
    {
        $sections = $page->sections;

        return view('admin.pages.sections', compact('page', 'sections'));
    }
    
    public function createSection(Page $page)
    {
        return view('admin.pages.create-section', compact('page'));
    }
} 
