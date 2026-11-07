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

    /**
     * Menyiapkan data fasilitas.
     */
    private function prepareFacilities(?array $facilities): array
    {
        return array_values(array_filter(
            $facilities ?? [],
            fn ($facility) => filled($facility)
        ));
    }

    /**
     * Menampilkan daftar paket.
     */
    public function index()
    {
        $packages = Package::latest()->paginate(10);

        return view('admin.packages.index', compact('packages'));
    }

    /**
     * Menampilkan form tambah paket.
     */
    public function create()
    {
        return view('admin.packages.create');
    }

    /**
     * Menyimpan paket baru.
     */
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

            'is_featured' => ['nullable', 'boolean'],
            'is_visible' => ['nullable', 'boolean'],

            'status' => ['required', Rule::in(['draft', 'published'])],
        ]);

        $validated['slug'] = $this->generateUniqueSlug($validated['name']);

        $validated['facilities'] = $this->prepareFacilities(
            $validated['facilities'] ?? []
        );

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

    /**
     * Menampilkan form edit paket.
     */
    public function edit(Package $package)
    {
        return view('admin.packages.edit', compact('package'));
    }

    /**
     * Memperbarui paket.
     */
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

            'is_featured' => ['nullable', 'boolean'],
            'is_visible' => ['nullable', 'boolean'],

            'status' => ['required', Rule::in(['draft', 'published'])],
        ]);

        $validated['slug'] = $this->generateUniqueSlug(
            $validated['name'],
            $package->id
        );

        $validated['facilities'] = $this->prepareFacilities(
            $validated['facilities'] ?? []
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

    /**
     * Menghapus paket.
     */
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
