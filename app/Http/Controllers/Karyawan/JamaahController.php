<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Jamaah;
use Illuminate\Http\Request;

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

        // Pencarian berdasarkan nama, telepon, atau email.
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan status.
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $jamaahs = $query->paginate(10)->withQueryString();

        // Ringkasan data Jamaah.
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
}
