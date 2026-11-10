<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Jamaah;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class JamaahController extends Controller
{
    /**
     * Menampilkan daftar Jamaah.
     */
    public function index(Request $request)
    {
        $query = Jamaah::with(['package', 'prospect'])
            ->orderByDesc('registration_date')
            ->orderByDesc('id');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $jamaahs = $query->paginate(10)->withQueryString();

        $totalJamaahs = Jamaah::count();

        $jamaahsWithPackage = Jamaah::whereNotNull('package_id')
            ->count();

        $jamaahsWithoutPackage = Jamaah::whereNull('package_id')
            ->count();

        return view('karyawan.jamaahs.index', compact(
            'jamaahs',
            'totalJamaahs',
            'jamaahsWithPackage',
            'jamaahsWithoutPackage'
        ));
    }

    /**
     * Menampilkan detail profil Jamaah.
     */
    public function show(Jamaah $jamaah)
    {
        $jamaah->load([
            'package',
            'prospect',
            'documents',
        ]);

        return view('karyawan.jamaahs.show', compact('jamaah'));
    }

    /**
     * Menampilkan formulir edit profil Jamaah.
     */
    public function edit(Jamaah $jamaah)
    {
        $packages = Package::orderBy('name')
            ->get(['id', 'name']);

        return view('karyawan.jamaahs.edit', compact(
            'jamaah',
            'packages'
        ));
    }

    /**
     * Memperbarui profil Jamaah.
     */
    public function update(Request $request, Jamaah $jamaah)
    {
        $validated = $request->validate([

            // Informasi dasar
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string'],

            // Registrasi
            'branch_office' => ['nullable', 'string', 'max:255'],
            'registration_number' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('jamaahs', 'registration_number')
                    ->ignore($jamaah->id),
            ],
            'registration_date' => ['required', 'date'],

            // Paket dan keberangkatan
            'package_id' => ['nullable', 'exists:packages,id'],
            'umrah_package_type' => [
                'nullable',
                Rule::in([
                    'reguler',
                    'vip',
                    'ramadhan',
                    'request',
                    'plus_turki',
                ]),
            ],
            'room_type' => [
                'nullable',
                Rule::in(['quad', 'triple', 'double', 'single']),
            ],
            'planned_departure_date' => ['nullable', 'date'],

            // Identitas
            'national_id' => ['nullable', 'string', 'max:30'],
            'birth_place' => ['nullable', 'string', 'max:100'],
            'birth_date' => ['nullable', 'date', 'before_or_equal:today'],
            'father_name' => ['nullable', 'string', 'max:255'],

            // Informasi pribadi
            'gender' => [
                'nullable',
                Rule::in(['laki_laki', 'perempuan']),
            ],
            'marital_status' => [
                'nullable',
                Rule::in([
                    'menikah',
                    'cerai',
                    'belum_menikah',
                ]),
            ],

            // Kontak tambahan
            'home_phone' => ['nullable', 'string', 'max:30'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:30'],

            // Riwayat dan latar belakang
            'umrah_experience' => [
                'nullable',
                Rule::in(['pertama', 'kedua', 'lebih_dari_2']),
            ],
            'education_level' => [
                'nullable',
                Rule::in([
                    'sd',
                    'smp',
                    'sma',
                    'diploma',
                    's1_s2_s3',
                ]),
            ],
            'occupation' => [
                'nullable',
                Rule::in([
                    'pns',
                    'swasta',
                    'lainnya',
                    'pelajar',
                    'rumah_tangga',
                ]),
            ],

            // Kesehatan
            'medical_history' => ['nullable', 'string'],
        ]);

        $jamaah->update($validated);

        return redirect()
            ->route('karyawan.jamaahs.show', $jamaah)
            ->with('success', 'Profil Jamaah berhasil diperbarui.');
    }
}
