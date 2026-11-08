<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\PackageImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PackageController extends Controller
{

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

    private function prepareFacilities(?array $facilities): array
    {
        return array_values(array_filter(
            $facilities ?? [],
            fn ($facility) => filled($facility)
        ));
    }

    private function storeGalleryImages(Package $package, array $images): void
    {
        foreach ($images as $index => $image) {
            if (!$image || !$image->isValid()) {
                continue;
            }

            $path = $image->store('packages/gallery', 'public');

            $package->images()->create([
                'image_path' => $path,
                'sort_order' => $package->images()->count(),
            ]);
        }
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

            'facilities' => ['nullable', 'array'],
            'facilities.*' => ['nullable', 'string', 'max:255'],

            'price' => ['nullable', 'numeric', 'min:0'],
            'duration_days' => ['nullable', 'integer', 'min:1'],
            'departure_date' => ['nullable', 'date'],
            'airline' => ['nullable', 'string', 'max:255'],
            'hotel' => ['nullable', 'string', 'max:255'],
            'hotel_makkah' => ['nullable', 'string', 'max:255'],
            'hotel_madinah' => ['nullable', 'string', 'max:255'],
            'flight_note' => ['nullable', 'string'],

            'image' => ['nullable', 'image', 'max:2048'],

            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'max:4096'],

            'is_featured' => ['nullable', 'boolean'],
            'is_visible' => ['nullable', 'boolean'],
            'status' => ['required', Rule::in(['draft', 'published'])],
        ]);

        $validated['slug'] = $this->generateUniqueSlug($validated['name']);
        $validated['facilities'] = $this->prepareFacilities($validated['facilities'] ?? []);
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_visible'] = $request->boolean('is_visible');

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('packages', 'public');
        }

        unset($validated['image'], $validated['images']);

        $package = Package::create($validated);

        $this->storeGalleryImages($package, $request->file('images', []));

        return redirect()->route('admin.packages.index')
            ->with('success', 'Paket umroh berhasil ditambahkan.');
    }

    public function edit(Package $package)
    {
        $package->load('images');

        return view('admin.packages.edit', compact('package'));
    }

    public function update(Request $request, Package $package)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],

            'facilities' => ['nullable', 'array'],
            'facilities.*' => ['nullable', 'string', 'max:255'],

            'price' => ['nullable', 'numeric', 'min:0'],
            'duration_days' => ['nullable', 'integer', 'min:1'],
            'departure_date' => ['nullable', 'date'],
            'airline' => ['nullable', 'string', 'max:255'],
            'hotel' => ['nullable', 'string', 'max:255'],
            'hotel_makkah' => ['nullable', 'string', 'max:255'],
            'hotel_madinah' => ['nullable', 'string', 'max:255'],
            'flight_note' => ['nullable', 'string'],

            'image' => ['nullable', 'image', 'max:2048'],

            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'max:4096'],

            'is_featured' => ['nullable', 'boolean'],
            'is_visible' => ['nullable', 'boolean'],
            'status' => ['required', Rule::in(['draft', 'published'])],
        ]);

        $validated['slug'] = $this->generateUniqueSlug($validated['name'], $package->id);
        $validated['facilities'] = $this->prepareFacilities($validated['facilities'] ?? []);
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_visible'] = $request->boolean('is_visible');

        if ($request->hasFile('image')) {
            if ($package->image_path) {
                Storage::disk('public')->delete($package->image_path);
            }

            $validated['image_path'] = $request->file('image')->store('packages', 'public');
        }

        unset($validated['image'], $validated['images']);

        $package->update($validated);

        // Foto lama tetap dipertahankan, foto baru ditambahkan.
        $this->storeGalleryImages($package, $request->file('images', []));

        return redirect()->route('admin.packages.index')
            ->with('success', 'Paket umroh berhasil diperbarui.');
    }

    public function destroy(Package $package)
    {
        foreach ($package->images as $galleryImage) {
            if ($galleryImage->image_path) {
                Storage::disk('public')->delete($galleryImage->image_path);
            }
        }

        if ($package->image_path) {
            Storage::disk('public')->delete($package->image_path);
        }

        $package->delete();

        return redirect()->route('admin.packages.index')
            ->with('success', 'Paket umroh berhasil dihapus.');
    }

    public function destroyImage(Package $package, PackageImage $image)
    {
        if ($image->package_id !== $package->id) {
            abort(404);
        }

        if ($image->image_path) {
            Storage::disk('public')->delete($image->image_path);
        }

        $image->delete();

        return redirect()
            ->route('admin.packages.edit', $package->id)
            ->with('success', 'Foto galeri berhasil dihapus.');
    }


}
