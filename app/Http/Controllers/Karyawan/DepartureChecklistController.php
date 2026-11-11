<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\DepartureChecklist;
use App\Models\DeparturePreparation;
use App\Models\JamaahGroupMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class DepartureChecklistController extends Controller
{
    /**
     * Menambahkan item checklist kesiapan jamaah.
     */
    public function store(
        Request $request,
        DeparturePreparation $preparation
    ) {
        $validated = $request->validate([
            'jamaah_id' => [
                'required',
                'exists:jamaahs,id',
            ],
            'category' => [
                'required',
                Rule::in([
                    'administrasi',
                    'perlengkapan',
                    'manasik',
                    'briefing',
                    'final_checking',
                ]),
            ],
            'item_name' => [
                'required',
                'string',
                'max:255',
            ],
            'is_required' => [
                'sometimes',
                'boolean',
            ],
            'status' => [
                'required',
                Rule::in(['belum', 'proses', 'selesai']),
            ],
            'notes' => [
                'nullable',
                'string',
            ],
        ]);

        // Pastikan jamaah sudah tergabung dalam kelompok
        // pada jadwal keberangkatan yang sama.
        $this->ensureJamaahBelongsToPreparation(
            $preparation,
            $validated['jamaah_id']
        );

        DB::transaction(function () use ($preparation, $validated) {

            $lockedPreparation = DeparturePreparation::whereKey(
                $preparation->id
            )
                ->lockForUpdate()
                ->firstOrFail();

            // Cegah item checklist yang sama dibuat dua kali.
            $alreadyExists = $lockedPreparation->checklists()
                ->where('jamaah_id', $validated['jamaah_id'])
                ->where('item_name', $validated['item_name'])
                ->exists();

            if ($alreadyExists) {
                throw ValidationException::withMessages([
                    'item_name' => 'Item checklist tersebut sudah tersedia untuk jamaah ini.',
                ]);
            }

            $status = $validated['status'];

            $lockedPreparation->checklists()->create([
                'jamaah_id' => $validated['jamaah_id'],
                'category' => $validated['category'],
                'item_name' => $validated['item_name'],
                'is_required' => $validated['is_required'] ?? true,
                'status' => $status,
                'notes' => $validated['notes'] ?? null,
                'checked_by' => $status === 'selesai'
                    ? auth()->id()
                    : null,
                'checked_at' => $status === 'selesai'
                    ? now()
                    : null,
            ]);
        });

        return back()->with(
            'success',
            'Item checklist berhasil ditambahkan.'
        );
    }

    public function generateStandardChecklists(
        DeparturePreparation $preparation
    ) {
        $templates = [
            [
                'category' => 'perlengkapan',
                'item_name' => 'Koper jamaah telah diterima',
            ],
            [
                'category' => 'perlengkapan',
                'item_name' => 'Seragam perjalanan telah diterima',
            ],
            [
                'category' => 'perlengkapan',
                'item_name' => 'Perlengkapan ibadah telah diterima',
            ],
            [
                'category' => 'manasik',
                'item_name' => 'Kehadiran jamaah dalam kegiatan manasik',
            ],
            [
                'category' => 'briefing',
                'item_name' => 'Kehadiran jamaah dalam kegiatan briefing',
            ],
            [
                'category' => 'final_checking',
                'item_name' => 'Kesesuaian data jamaah dengan manifest',
            ],
            [
                'category' => 'final_checking',
                'item_name' => 'Pemeriksaan akhir perlengkapan jamaah',
            ],
        ];

        $createdCount = DB::transaction(function () use (
            $preparation,
            $templates
        ) {
            $lockedPreparation = DeparturePreparation::whereKey(
                $preparation->id
            )
                ->lockForUpdate()
                ->firstOrFail();

            $jamaahIds = JamaahGroupMember::where('status', 'aktif')
                ->whereHas('group', function ($query) use ($preparation) {
                    $query->where(
                        'schedule_id',
                        $preparation->schedule_id
                    );
                })
                ->distinct()
                ->pluck('jamaah_id');

            if ($jamaahIds->isEmpty()) {
                throw ValidationException::withMessages([
                    'checklist' => 'Belum ada jamaah aktif dalam kelompok pada jadwal keberangkatan ini.',
                ]);
            }

            $created = 0;

            foreach ($jamaahIds as $jamaahId) {
                foreach ($templates as $template) {

                    // Tidak membuat ulang item yang sudah tersedia.
                    $checklist = $lockedPreparation->checklists()
                        ->firstOrCreate(
                            [
                                'jamaah_id' => $jamaahId,
                                'item_name' => $template['item_name'],
                            ],
                            [
                                'category' => $template['category'],
                                'is_required' => true,
                                'status' => 'belum',
                            ]
                        );

                    if ($checklist->wasRecentlyCreated) {
                        $created++;
                    }
                }
            }

            return $created;
        });

        return back()->with(
            'success',
            $createdCount > 0
                ? "{$createdCount} item checklist standar berhasil dibuat."
                : 'Seluruh checklist standar sudah tersedia. Tidak ada data duplikat yang dibuat.'
        );
    }

    public function update(
        Request $request,
        DeparturePreparation $preparation,
        DepartureChecklist $checklist
    ) {
        $validated = $request->validate([
            'jamaah_id' => [
                'required',
                'exists:jamaahs,id',
            ],
            'category' => [
                'required',
                Rule::in([
                    'administrasi',
                    'perlengkapan',
                    'manasik',
                    'briefing',
                    'final_checking',
                ]),
            ],
            'item_name' => [
                'required',
                'string',
                'max:255',
            ],
            'is_required' => [
                'sometimes',
                'boolean',
            ],
            'status' => [
                'required',
                Rule::in(['belum', 'proses', 'selesai']),
            ],
            'notes' => [
                'nullable',
                'string',
            ],
        ]);

        $preparation->checklists()
            ->whereKey($checklist->id)
            ->firstOrFail();

        $this->ensureJamaahBelongsToPreparation(
            $preparation,
            $validated['jamaah_id']
        );

        DB::transaction(function () use (
            $preparation,
            $checklist,
            $validated
        ) {
            $lockedPreparation = DeparturePreparation::whereKey(
                $preparation->id
            )
                ->lockForUpdate()
                ->firstOrFail();

            $lockedChecklist = $lockedPreparation->checklists()
                ->whereKey($checklist->id)
                ->firstOrFail();

            $duplicate = $lockedPreparation->checklists()
                ->where('jamaah_id', $validated['jamaah_id'])
                ->where('item_name', $validated['item_name'])
                ->where('id', '!=', $lockedChecklist->id)
                ->exists();

            if ($duplicate) {
                throw ValidationException::withMessages([
                    'item_name' => 'Item checklist tersebut sudah tersedia untuk jamaah ini.',
                ]);
            }

            $status = $validated['status'];

            $lockedChecklist->update([
                'jamaah_id' => $validated['jamaah_id'],
                'category' => $validated['category'],
                'item_name' => $validated['item_name'],
                'is_required' => $validated['is_required'] ?? false,
                'status' => $status,
                'notes' => $validated['notes'] ?? null,
                'checked_by' => $status === 'selesai'
                    ? auth()->id()
                    : null,
                'checked_at' => $status === 'selesai'
                    ? (
                        $lockedChecklist->checked_at ?? now()
                    )
                    : null,
            ]);
        });

        return back()->with(
            'success',
            'Checklist kesiapan jamaah berhasil diperbarui.'
        );
    }

    public function destroy(
        DeparturePreparation $preparation,
        DepartureChecklist $checklist
    ) {
        $preparation->checklists()
            ->whereKey($checklist->id)
            ->firstOrFail()
            ->delete();

        return back()->with(
            'success',
            'Item checklist berhasil dihapus.'
        );
    }

    private function ensureJamaahBelongsToPreparation(
        DeparturePreparation $preparation,
        int $jamaahId
    ): void {
        $isMember = JamaahGroupMember::where('jamaah_id', $jamaahId)
            ->where('status', 'aktif')
            ->whereHas('group', function ($query) use ($preparation) {
                $query->where(
                    'schedule_id',
                    $preparation->schedule_id
                );
            })
            ->exists();

        if (!$isMember) {
            throw ValidationException::withMessages([
                'jamaah_id' => 'Jamaah tidak tergabung dalam kelompok pada jadwal keberangkatan ini.',
            ]);
        }
    }
}
