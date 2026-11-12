<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Prospect;
use App\Models\User;
use App\Models\Jamaah;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProspectController extends Controller
{
    public function index(Request $request)
    {
        $query = Prospect::with(['package', 'pic'])
            ->withCount('followUps')
            ->latest();

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

        $prospects = $query->paginate(10)->withQueryString();
        $totalProspects = Prospect::count();
        $newProspects = Prospect::where('status', 'baru')->count();
        $interestedProspects = Prospect::where('status', 'tertarik')->count();
        $registeredProspects = Prospect::where('status', 'mendaftar')->count();

        return view('karyawan.prospects.index', compact(
            'prospects',
            'totalProspects',
            'newProspects',
            'interestedProspects',
            'registeredProspects'
        ));
    }

    public function create()
    {
        $packages = Package::where('is_visible', true)
            ->where('status', 'published')
            ->orderBy('name')
            ->get(['id', 'name']);

        $employees = User::where('role', User::ROLE_KARYAWAN)
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('karyawan.prospects.create', compact('packages', 'employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string'],
            'package_id' => ['nullable', 'exists:packages,id'],
            'status' => [
                'required',
                Rule::in(['baru', 'dihubungi', 'follow_up', 'tertarik', 'mendaftar', 'tidak_tertarik']),
            ],
            'pic_id' => [
                'nullable',
                Rule::exists('users', 'id')->where('role', User::ROLE_KARYAWAN),
            ],
            'next_follow_up_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        $validated['pic_id'] = $validated['pic_id'] ?? auth()->id();
        $validated['created_by'] = auth()->id();

        Prospect::create($validated);

        return redirect()
            ->route('karyawan.prospects.index')
            ->with('success', 'Data calon jamaah berhasil ditambahkan.');
    }

    public function show(Prospect $prospect)
    {
        $prospect->load(['package', 'pic', 'creator', 'followUps.user']);

        return view('karyawan.prospects.show', compact('prospect'));
    }

    public function edit(Prospect $prospect)
    {
        $packages = Package::where('is_visible', true)
            ->where('status', 'published')
            ->orderBy('name')
            ->get(['id', 'name']);

        $employees = User::where('role', User::ROLE_KARYAWAN)
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('karyawan.prospects.edit', compact('prospect', 'packages', 'employees'));
    }

    public function update(Request $request, Prospect $prospect)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string'],
            'package_id' => ['nullable', 'exists:packages,id'],
            'status' => [
                'required',
                Rule::in(['baru', 'dihubungi', 'follow_up', 'tertarik', 'mendaftar', 'tidak_tertarik']),
            ],
            'pic_id' => [
                'nullable',
                Rule::exists('users', 'id')->where('role', User::ROLE_KARYAWAN),
            ],
            'next_follow_up_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        $prospect->update($validated);

        return redirect()
            ->route('karyawan.prospects.index')
            ->with('success', 'Data calon jamaah berhasil diperbarui.');
    }

    public function destroy(Prospect $prospect)
    {
        $prospect->delete();

        return redirect()
            ->route('karyawan.prospects.index')
            ->with('success', 'Data calon jamaah berhasil dihapus.');
    }

    public function convertToJamaah(Prospect $prospect)
    {
        if ($prospect->status !== 'mendaftar') {
            return back()->with('error', 'Calon jamaah harus berstatus Mendaftar untuk dikonversi.');
        }

        if ($prospect->jamaah()->exists()) {
            return back()->with('error', 'Calon jamaah ini sudah terdaftar sebagai Jamaah.');
        }

        DB::transaction(function () use ($prospect) {
            $lockedProspect = Prospect::whereKey($prospect->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedProspect->status !== 'mendaftar') {
                abort(422, 'Status calon jamaah belum memenuhi syarat konversi.');
            }

            if ($lockedProspect->jamaah()->exists()) {
                abort(422, 'Calon jamaah sudah dikonversi sebelumnya.');
            }

            // Ambil harga paket sebagai snapshot tagihan pada saat konversi.
            $lockedProspect->loadMissing('package');
            $packagePrice = $lockedProspect->package?->price;
            $totalBill = $packagePrice !== null && (float) $packagePrice > 0
                ? (float) $packagePrice
                : null;

            $jamaah = $lockedProspect->jamaah()->create([
                'package_id' => $lockedProspect->package_id,
                'total_bill' => $totalBill,
                'name' => $lockedProspect->name,
                'phone' => $lockedProspect->phone,
                'email' => $lockedProspect->email,
                'address' => $lockedProspect->address,
                'registration_date' => today(),
                'status' => 'terdaftar',
            ]);

            $registrationNumber = 'ANS-'
                . now()->format('Y') . '-'
                . str_pad($jamaah->id, 6, '0', STR_PAD_LEFT);

            $jamaah->update([
                'registration_number' => $registrationNumber,
            ]);

            // Checklist hanya dibuat dengan enam identitas dokumen baku.
            $requiredDocuments = [
                'ktp' => 'KTP',
                'kartu_keluarga' => 'Kartu Keluarga (KK)',
                'pas_foto' => 'Pas Foto',
                'paspor' => 'Paspor',
                'vaksin_meningitis' => 'Surat Vaksin Meningitis',
                'surat_polio' => 'Surat Polio',
            ];

            foreach ($requiredDocuments as $type => $name) {
                $jamaah->documents()->firstOrCreate(
                    ['document_type' => $type],
                    [
                        'document_name' => $name,
                        'status' => 'belum_diupload',
                    ]
                );
            }
        });

        return redirect()
            ->route('karyawan.prospects.show', $prospect)
            ->with('success', 'Calon jamaah berhasil dikonversi menjadi data Jamaah.');
    }
}
