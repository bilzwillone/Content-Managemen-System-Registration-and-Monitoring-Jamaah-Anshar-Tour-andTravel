<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\DeparturePreparation;
use App\Models\JamaahGroupMember;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class DeparturePreparationController extends Controller
{
    public function index()
    {
        $schedules = Schedule::with('departurePreparation')
            ->withCount('groups')
            ->orderBy('departure_date')
            ->paginate(15);

        return view('karyawan.departures.index', compact('schedules'));
    }

    public function create()
    {
        $schedules = Schedule::withCount('groups')
            ->whereDoesntHave('departurePreparation')
            ->orderBy('departure_date')
            ->get();

        return view('karyawan.departures.create', compact('schedules'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'schedule_id' => ['required', 'integer', 'exists:schedules,id'],
            'notes' => ['nullable', 'string'],
        ]);

        $preparation = DB::transaction(function () use ($validated) {
            // Mengunci jadwal agar tidak dibuatkan persiapan ganda.
            $schedule = Schedule::whereKey($validated['schedule_id'])
                ->lockForUpdate()
                ->firstOrFail();

            if ($schedule->departurePreparation()->exists()) {
                throw ValidationException::withMessages([
                    'schedule_id' => 'Jadwal keberangkatan ini sudah memiliki data persiapan.',
                ]);
            }

            return DeparturePreparation::create([
                'schedule_id' => $schedule->id,
                'status' => 'draft',
                'notes' => $validated['notes'] ?? null,
                'created_by' => auth()->id(),
            ]);
        });

        return redirect()
            ->route('karyawan.departures.show', $preparation)
            ->with('success', 'Data persiapan keberangkatan berhasil dibuat.');
    }

    /**
     * Menampilkan detail persiapan keberangkatan.
     */
    public function show(DeparturePreparation $preparation)
    {
        $preparation->load([
            'schedule.groups.members',
            'schedule.groups.muthawifs',
            'schedule.groups.muthawifAssignments.muthawif',
            'schedule.groups.muthawifAssignments.assigner',
            'roomAssignments.jamaah',
            'roomAssignments.assigner',
            'checklists.jamaah',
            'checklists.checker',
            'creator',
        ]);

        return view('karyawan.departures.show', compact('preparation'));
    }

    public function update(Request $request, DeparturePreparation $preparation)
    {
        $validated = $request->validate([
            // Informasi penerbangan keberangkatan.
            'departure_flight_number' => 'nullable|string|max:100',
            'departure_airport' => 'nullable|string|max:255',
            'arrival_airport' => 'nullable|string|max:255',
            'departure_at' => 'nullable|date',
            'arrival_at' => 'nullable|date|after_or_equal:departure_at',

            // Informasi penerbangan kepulangan.
            'return_flight_number' => 'nullable|string|max:100',
            'return_departure_at' => 'nullable|date',
            'return_arrival_at' => 'nullable|date|after_or_equal:return_departure_at',

            // Hotel Makkah.
            'makkah_hotel_name' => 'nullable|string|max:255',
            'makkah_hotel_address' => 'nullable|string',
            'makkah_check_in' => 'nullable|date',
            'makkah_check_out' => 'nullable|date|after_or_equal:makkah_check_in',

            // Hotel Madinah.
            'madinah_hotel_name' => 'nullable|string|max:255',
            'madinah_hotel_address' => 'nullable|string',
            'madinah_check_in' => 'nullable|date',
            'madinah_check_out' => 'nullable|date|after_or_equal:madinah_check_in',

            // Transportasi dan perlengkapan.
            'transportation_details' => 'nullable|string',
            'equipment_notes' => 'nullable|string',

            // Manasik.
            'manasik_at' => 'nullable|date',
            'manasik_location' => 'nullable|string|max:255',
            'manasik_notes' => 'nullable|string',

            // Briefing.
            'briefing_at' => 'nullable|date',
            'briefing_location' => 'nullable|string|max:255',
            'briefing_notes' => 'nullable|string',

            // Status dan catatan.
            'status' => [
                'required',
                'in:draft,proses,siap_berangkat,selesai',
            ],
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($preparation, $validated) {
            $lockedPreparation = DeparturePreparation::whereKey($preparation->id)
                ->lockForUpdate()
                ->firstOrFail();

            $previousStatus = $lockedPreparation->status;
            $requestedStatus = $validated['status'];

            $lockedPreparation->fill($validated);

            if ($requestedStatus === 'siap_berangkat') {
                $this->assertReadyForDeparture($lockedPreparation);
            }

            if ($requestedStatus === 'selesai' && $previousStatus !== 'siap_berangkat') {
                throw ValidationException::withMessages([
                    'status' => 'Persiapan hanya dapat dinyatakan selesai setelah statusnya Siap Berangkat.',
                ]);
            }

            $lockedPreparation->save();
        });

        return redirect()
            ->route('karyawan.departures.show', $preparation)
            ->with('success', 'Informasi persiapan keberangkatan berhasil diperbarui.');
    }

    private function assertReadyForDeparture(DeparturePreparation $preparation): void
    {
        $preparation->load([
            'schedule.groups.members',
            'schedule.groups.muthawifAssignments',
        ]);

        $schedule = $preparation->schedule;
        $issues = [];

        if (!$schedule) {
            throw ValidationException::withMessages([
                'status' => 'Jadwal keberangkatan tidak ditemukan.',
            ]);
        }

        $groups = $schedule->groups;

        if ($groups->isEmpty()) {
            $issues[] = 'Belum ada kelompok jamaah untuk jadwal keberangkatan ini.';
        }

        $activeJamaahs = collect();

        foreach ($groups as $group) {
            $groupMembers = $group->members->filter(
                fn ($member) => ($member->pivot->status ?? null) === 'aktif'
            );

            if ($groupMembers->isEmpty()) {
                $issues[] = "Kelompok {$group->name} belum memiliki jamaah aktif.";
            }

            $activeJamaahs = $activeJamaahs->concat($groupMembers);

            $muthawifCount = $group->muthawifAssignments->count();

            if ($muthawifCount !== 2) {
                $issues[] = "Kelompok {$group->name} belum memiliki tepat dua Muthawif (saat ini {$muthawifCount}/2).";
            }
        }

        $activeJamaahs = $activeJamaahs->unique('id')->values();

        if ($activeJamaahs->isEmpty()) {
            $issues[] = 'Belum ada jamaah aktif dalam manifest.';
        }

        // Periksa kelengkapan informasi operasional utama.
        $requiredPreparationFields = [
            'departure_flight_number' => 'Nomor penerbangan keberangkatan',
            'departure_airport' => 'Bandara keberangkatan',
            'arrival_airport' => 'Bandara kedatangan',
            'departure_at' => 'Waktu keberangkatan',
            'arrival_at' => 'Waktu kedatangan',
            'return_flight_number' => 'Nomor penerbangan kepulangan',
            'return_departure_at' => 'Waktu keberangkatan pulang',
            'return_arrival_at' => 'Waktu kedatangan pulang',
            'makkah_hotel_name' => 'Nama hotel Makkah',
            'makkah_check_in' => 'Check-in hotel Makkah',
            'makkah_check_out' => 'Check-out hotel Makkah',
            'madinah_hotel_name' => 'Nama hotel Madinah',
            'madinah_check_in' => 'Check-in hotel Madinah',
            'madinah_check_out' => 'Check-out hotel Madinah',
            'transportation_details' => 'Informasi transportasi',
            'equipment_notes' => 'Informasi perlengkapan',
            'manasik_at' => 'Jadwal manasik',
            'manasik_location' => 'Lokasi manasik',
            'briefing_at' => 'Jadwal briefing',
            'briefing_location' => 'Lokasi briefing',
        ];

        foreach ($requiredPreparationFields as $field => $label) {
            if (blank($preparation->{$field})) {
                $issues[] = "{$label} belum diisi.";
            }
        }

        // Kelompokkan room list berdasarkan jamaah.
        $roomCitiesByJamaah = $preparation->roomAssignments()
            ->get(['jamaah_id', 'city'])
            ->groupBy('jamaah_id')
            ->map(fn ($rooms) => $rooms->pluck('city')->unique()->values());

        // Kelompokkan checklist wajib berdasarkan jamaah.
        $requiredChecklistsByJamaah = $preparation->checklists()
            ->where('is_required', true)
            ->get(['jamaah_id', 'item_name', 'status'])
            ->groupBy('jamaah_id');

        foreach ($activeJamaahs as $jamaah) {
            $roomCities = $roomCitiesByJamaah->get($jamaah->id, collect());

            if (!$roomCities->contains('makkah')) {
                $issues[] = "{$jamaah->name} belum mendapatkan kamar di Makkah.";
            }

            if (!$roomCities->contains('madinah')) {
                $issues[] = "{$jamaah->name} belum mendapatkan kamar di Madinah.";
            }

            $jamaahChecklists = $requiredChecklistsByJamaah->get($jamaah->id, collect());

            if ($jamaahChecklists->isEmpty()) {
                $issues[] = "{$jamaah->name} belum memiliki item checklist wajib.";
                continue;
            }

            $unfinishedItems = $jamaahChecklists->where('status', '!=', 'selesai');

            if ($unfinishedItems->isNotEmpty()) {
                $issues[] = "{$jamaah->name} masih memiliki {$unfinishedItems->count()} item checklist wajib yang belum selesai.";
            }
        }

        if (!empty($issues)) {
            throw ValidationException::withMessages([
                'status' => "Persiapan belum dapat ditetapkan sebagai Siap Berangkat:\n- " . implode("\n- ", $issues),
            ]);
        }
    }
}
