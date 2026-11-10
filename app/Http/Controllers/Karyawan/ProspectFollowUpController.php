<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Prospect;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProspectFollowUpController extends Controller
{
    public function store(Request $request, Prospect $prospect)
    {
        $validated = $request->validate([
            'channel' => [
                'required',
                Rule::in([
                    'whatsapp',
                    'phone',
                    'email',
                    'meeting',
                    'other',
                ]),
            ],
            'activity_at' => ['required', 'date'],
            'summary' => ['required', 'string'],
            'notes' => ['nullable', 'string'],
            'next_follow_up_at' => ['nullable', 'date'],
        ]);

        // Simpan riwayat aktivitas.
        $prospect->followUps()->create([
            'user_id' => auth()->id(),
            'channel' => $validated['channel'],
            'activity_at' => $validated['activity_at'],
            'summary' => $validated['summary'],
            'notes' => $validated['notes'] ?? null,
            'next_follow_up_at' => $validated['next_follow_up_at'] ?? null,
        ]);

        // Perbarui jadwal follow-up pada data calon jamaah.
        $prospect->update([
            'next_follow_up_at' => $validated['next_follow_up_at'] ?? null,
        ]);

        return redirect()
            ->route('karyawan.prospects.show', $prospect)
            ->with('success', 'Riwayat follow-up berhasil ditambahkan.');
    }
}
