<?php

namespace App\Http\Controllers;

use App\Models\Package;

class PackageController extends Controller
{
    public function show(string $slug)
    {
        $package = Package::where('slug', $slug)
            ->where('status', 'published')
            ->where('is_visible', true)
            ->firstOrFail();

        return view('packages.show', compact('package'));
    }
}
