<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PackageController extends Controller
{
    /**
     * Membuat slug yang unik.
     */
    private function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 1;

        while (
            Package::where('slug', $slug)
                ->when($ignoreId, function ($query) use ($ignoreId) {
                    $query->where('id', '!=', $ignoreId);
                })
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    public function index()
    {
        $packages = Package::latest()->paginate(10);

        return view('admin.packages.index', compact('packages'));
    }

    public function create()
    {
        return view('admin.packages.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'duration_days' => ['nullable', 'integer', 'min:1'],
            'departure_date' => ['nullable', 'date'],
            'airline' => ['nullable', 'string', 'max:255'],
            'hotel' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'max:2048'],
            'is_featured' => ['nullable', 'boolean'],
            'is_visible' => ['nullable', 'boolean'],
            'status' => ['required', Rule::in(['draft', 'published'])],
        ]);

        $validated['slug'] = $this->generateUniqueSlug($validated['name']);

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_visible'] = $request->boolean('is_visible');

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')
                ->store('packages', 'public');
        }

        unset($validated['image']);

        Package::create($validated);

        return redirect()
            ->route('admin.packages.index')
            ->with('success', 'Paket umroh berhasil ditambahkan.');
    }

    public function edit(Package $package)
    {
        return view('admin.packages.edit', compact('package'));
    }

    public function update(Request $request, Package $package)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'duration_days' => ['nullable', 'integer', 'min:1'],
            'departure_date' => ['nullable', 'date'],
            'airline' => ['nullable', 'string', 'max:255'],
            'hotel' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'max:2048'],
            'is_featured' => ['nullable', 'boolean'],
            'is_visible' => ['nullable', 'boolean'],
            'status' => ['required', Rule::in(['draft', 'published'])],
        ]);

        $validated['slug'] = $this->generateUniqueSlug(
            $validated['name'],
            $package->id
        );

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_visible'] = $request->boolean('is_visible');

        if ($request->hasFile('image')) {
            if ($package->image_path) {
                Storage::disk('public')->delete($package->image_path);
            }

            $validated['image_path'] = $request->file('image')
                ->store('packages', 'public');
        }

        unset($validated['image']);

        $package->update($validated);

        return redirect()
            ->route('admin.packages.index')
            ->with('success', 'Paket umroh berhasil diperbarui.');
    }

    public function destroy(Package $package)
    {
        if ($package->image_path) {
            Storage::disk('public')->delete($package->image_path);
        }

        $package->delete();

        return redirect()
            ->route('admin.packages.index')
            ->with('success', 'Paket umroh berhasil dihapus.');
    }
}
