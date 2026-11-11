<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\DeparturePreparation;
use App\Models\DepartureRoomAssignment;
use App\Models\JamaahGroupMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class DepartureRoomAssignmentController extends Controller
{
    /**
     * Menyimpan pembagian kamar jamaah.
     */
    public function store(
        Request $request,
        DeparturePreparation $preparation
    ) {
        $validated = $request->validate([
            'jamaah_id' => 'required|exists:jamaahs,id',
            'city' => 'required|in:makkah,madinah',
            'room_number' => 'required|string|max:50',
            'room_type' => 'required|in:quad,triple,double,single',
            'notes' => 'nullable|string',
        ]);

        // Pastikan jamaah tergabung dalam kelompok
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

            // Periksa apakah jamaah sudah memiliki kamar
            // pada lokasi yang sama.
            $alreadyAssigned = $lockedPreparation
                ->roomAssignments()
                ->where('jamaah_id', $validated['jamaah_id'])
                ->where('city', $validated['city'])
                ->exists();

            if ($alreadyAssigned) {
                throw ValidationException::withMessages([
                    'jamaah_id' => 'Jamaah sudah memiliki penempatan kamar pada lokasi tersebut.',
                ]);
            }

            // Periksa kapasitas kamar.
            $this->checkRoomCapacity(
                $lockedPreparation,
                $validated['city'],
                $validated['room_number'],
                $validated['room_type']
            );

            // Simpan pembagian kamar.
            $lockedPreparation->roomAssignments()->create([
                'jamaah_id' => $validated['jamaah_id'],
                'city' => $validated['city'],
                'room_number' => $validated['room_number'],
                'room_type' => $validated['room_type'],
                'notes' => $validated['notes'] ?? null,
                'assigned_by' => auth()->id(),
            ]);
        });

        return back()->with(
            'success',
            'Pembagian kamar jamaah berhasil disimpan.'
        );
    }

    /**
     * Memperbarui pembagian kamar jamaah.
     */
    public function update(
        Request $request,
        DeparturePreparation $preparation,
        DepartureRoomAssignment $roomAssignment
    ) {
        $validated = $request->validate([
            'jamaah_id' => 'required|exists:jamaahs,id',
            'city' => 'required|in:makkah,madinah',
            'room_number' => 'required|string|max:50',
            'room_type' => 'required|in:quad,triple,double,single',
            'notes' => 'nullable|string',
        ]);

        // Pastikan data kamar benar-benar milik persiapan ini.
        $preparation->roomAssignments()
            ->whereKey($roomAssignment->id)
            ->firstOrFail();

        $this->ensureJamaahBelongsToPreparation(
            $preparation,
            $validated['jamaah_id']
        );

        DB::transaction(function () use (
            $preparation,
            $roomAssignment,
            $validated
        ) {
            $lockedPreparation = DeparturePreparation::whereKey(
                $preparation->id
            )
                ->lockForUpdate()
                ->firstOrFail();

            $lockedAssignment = $lockedPreparation
                ->roomAssignments()
                ->whereKey($roomAssignment->id)
                ->firstOrFail();

            // Cegah jamaah memiliki dua kamar pada lokasi yang sama.
            $alreadyAssigned = $lockedPreparation
                ->roomAssignments()
                ->where('jamaah_id', $validated['jamaah_id'])
                ->where('city', $validated['city'])
                ->where('id', '!=', $lockedAssignment->id)
                ->exists();

            if ($alreadyAssigned) {
                throw ValidationException::withMessages([
                    'jamaah_id' => 'Jamaah sudah memiliki penempatan kamar pada lokasi tersebut.',
                ]);
            }

            // Periksa kapasitas kamar.
            $this->checkRoomCapacity(
                $lockedPreparation,
                $validated['city'],
                $validated['room_number'],
                $validated['room_type'],
                $lockedAssignment->id
            );

            $lockedAssignment->update([
                'jamaah_id' => $validated['jamaah_id'],
                'city' => $validated['city'],
                'room_number' => $validated['room_number'],
                'room_type' => $validated['room_type'],
                'notes' => $validated['notes'] ?? null,
            ]);
        });

        return back()->with(
            'success',
            'Pembagian kamar jamaah berhasil diperbarui.'
        );
    }

    /**
     * Menghapus pembagian kamar jamaah.
     */
    public function destroy(
        DeparturePreparation $preparation,
        DepartureRoomAssignment $roomAssignment
    ) {
        $preparation->roomAssignments()
            ->whereKey($roomAssignment->id)
            ->firstOrFail()
            ->delete();

        return back()->with(
            'success',
            'Penempatan kamar jamaah berhasil dihapus.'
        );
    }

    /**
     * Memastikan jamaah merupakan anggota kelompok
     * pada jadwal persiapan yang dipilih.
     */
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

    /**
     * Memeriksa kapasitas dan konsistensi tipe kamar.
     */
    private function checkRoomCapacity(
        DeparturePreparation $preparation,
        string $city,
        string $roomNumber,
        string $roomType,
        ?int $excludeId = null
    ): void {
        $roomCapacities = [
            'quad' => 4,
            'triple' => 3,
            'double' => 2,
            'single' => 1,
        ];

        $assignments = $preparation->roomAssignments()
            ->where('city', $city)
            ->where('room_number', $roomNumber)
            ->when($excludeId, function ($query) use ($excludeId) {
                $query->where('id', '!=', $excludeId);
            })
            ->lockForUpdate()
            ->get();

        // Semua penghuni kamar harus menggunakan tipe kamar yang sama.
        $differentType = $assignments->contains(
            fn ($assignment) => $assignment->room_type !== $roomType
        );

        if ($differentType) {
            throw ValidationException::withMessages([
                'room_type' => 'Tipe kamar tidak sesuai dengan penempatan jamaah lain pada nomor kamar tersebut.',
            ]);
        }

        $capacity = $roomCapacities[$roomType];
        
        if ($assignments->count() >= $capacity) {
            throw ValidationException::withMessages([
                'room_number' => "Kamar {$roomNumber} sudah mencapai kapasitas {$capacity} orang.",
            ]);
        }
    }
}
