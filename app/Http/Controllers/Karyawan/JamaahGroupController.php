<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Jamaah;
use App\Models\JamaahGroup;
use App\Models\JamaahGroupMember;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class JamaahGroupController extends Controller
{
    public function index()
    {
        $groups = JamaahGroup::with(['schedule', 'muthawifs'])
            ->withCount(['members', 'muthawifs'])
            ->latest()
            ->paginate(15);

        return view('karyawan.groups.index', compact('groups'));
    }

    public function create()
    {
        $schedules = Schedule::orderBy('departure_date')
            ->get();

        return view('karyawan.groups.create', compact('schedules'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'schedule_id' => [
                'required',
                'exists:schedules,id',
            ],
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'notes' => [
                'nullable',
                'string',
            ],
        ]);

        do {
            $groupCode = 'ANS-GRP-' . now()->format('Ymd')
                . '-' . Str::upper(Str::random(5));
        } while (
            JamaahGroup::where('group_code', $groupCode)->exists()
        );

        DB::transaction(function () use ($validated, $groupCode) {
            JamaahGroup::create([
                'schedule_id' => $validated['schedule_id'],
                'name' => $validated['name'],
                'group_code' => $groupCode,
                'status' => 'persiapan',
                'notes' => $validated['notes'] ?? null,
                'created_by' => auth()->id(),
            ]);
        });

        return redirect()
            ->route('karyawan.groups.index')
            ->with('success', 'Kelompok jamaah berhasil dibuat.');
    }

    public function show(JamaahGroup $group)
    {
        $group->load([
            'schedule',
            'members',
            'memberRecords.adder',
            'muthawifAssignments.muthawif',
            'muthawifAssignments.assigner',
            'creator',
        ]);

        $availableJamaahs = Jamaah::whereDoesntHave(
            'groups',
            function ($query) use ($group) {
                $query->where('schedule_id', $group->schedule_id);
            }
        )
            ->orderBy('name')
            ->get(['id', 'name', 'phone']);

        $availableMuthawifs = User::where('role', 'muthawif')
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return view('karyawan.groups.show', compact(
            'group',
            'availableJamaahs',
            'availableMuthawifs'
        ));
    }

    public function update(Request $request, JamaahGroup $group)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'status' => [
                'required',
                Rule::in([
                    'persiapan',
                    'siap_berangkat',
                    'berangkat',
                    'selesai',
                    'dibatalkan',
                ]),
            ],
            'notes' => [
                'nullable',
                'string',
            ],
        ]);

        $group->update($validated);

        return back()->with(
            'success',
            'Informasi kelompok berhasil diperbarui.'
        );
    }

    public function destroy(JamaahGroup $group)
    {
        if (in_array($group->status, ['berangkat', 'selesai'], true)) {
            return back()->withErrors([
                'group' => 'Kelompok yang sudah berangkat atau selesai tidak dapat dihapus karena merupakan bagian dari riwayat perjalanan.',
            ]);
        }

        DB::transaction(function () use ($group) {

            $schedule = Schedule::whereKey($group->schedule_id)
                ->lockForUpdate()
                ->firstOrFail();

            $memberCount = $group->memberRecords()->count();

            $group->delete();

            $schedule->booked_seats = max(
                0,
                (int) $schedule->booked_seats - $memberCount
            );

            $schedule->save();
        });

        return redirect()
            ->route('karyawan.groups.index')
            ->with('success', 'Kelompok jamaah berhasil dihapus.');
    }

    public function addMember(Request $request, JamaahGroup $group)
    {
        $validated = $request->validate([
            'jamaah_id' => [
                'required',
                'exists:jamaahs,id',
            ],
            'notes' => [
                'nullable',
                'string',
            ],
        ]);

        DB::transaction(function () use ($group, $validated) {

            $schedule = Schedule::whereKey($group->schedule_id)
                ->lockForUpdate()
                ->firstOrFail();

            $alreadyAssigned = JamaahGroupMember::where(
                'jamaah_id',
                $validated['jamaah_id']
            )
                ->whereHas('group', function ($query) use ($schedule) {
                    $query->where('schedule_id', $schedule->id);
                })
                ->exists();

            if ($alreadyAssigned) {
                throw ValidationException::withMessages([
                    'jamaah_id' => 'Jamaah sudah tergabung dalam kelompok pada jadwal keberangkatan ini.',
                ]);
            }

            if ($schedule->available_seats <= 0) {
                throw ValidationException::withMessages([
                    'jamaah_id' => 'Kapasitas kursi pada jadwal keberangkatan ini sudah penuh.',
                ]);
            }

            $group->memberRecords()->create([
                'jamaah_id' => $validated['jamaah_id'],
                'status' => 'aktif',
                'notes' => $validated['notes'] ?? null,
                'added_by' => auth()->id(),
            ]);

            $schedule->increment('booked_seats');
        });

        return back()->with(
            'success',
            'Jamaah berhasil ditambahkan ke kelompok.'
        );
    }

    public function removeMember(
        JamaahGroup $group,
        JamaahGroupMember $member
    ) {
        DB::transaction(function () use ($group, $member) {

            // Kunci jadwal agar pembaruan kapasitas tetap konsisten.
            $schedule = Schedule::whereKey($group->schedule_id)
                ->lockForUpdate()
                ->firstOrFail();

            // Pastikan anggota memang berasal dari kelompok ini.
            $memberRecord = $group->memberRecords()
                ->whereKey($member->id)
                ->firstOrFail();

            // Hapus keanggotaan jamaah.
            $memberRecord->delete();

            // Kurangi jumlah kursi yang terisi.
            $schedule->booked_seats = max(
                0,
                (int) $schedule->booked_seats - 1
            );

            $schedule->save();
        });

        return back()->with(
            'success',
            'Jamaah berhasil dikeluarkan dari kelompok.'
        );
    }


    public function assignMuthawifs(
        Request $request,
        JamaahGroup $group
    ) {
        $validated = $request->validate([
            'muthawif_ids' => [
                'required',
                'array',
                'size:2',
            ],
            'muthawif_ids.*' => [
                'required',
                'integer',
                'distinct',
                'exists:users,id',
            ],
        ]);

        $muthawifIds = $validated['muthawif_ids'];

        $validMuthawifCount = User::whereIn('id', $muthawifIds)
            ->where('role', 'muthawif')
            ->count();

        if ($validMuthawifCount !== 2) {
            return back()->withErrors([
                'muthawif_ids' => 'Pilih dua pengguna dengan role Muthawif yang valid.',
            ]);
        }

        DB::transaction(function () use ($group, $muthawifIds) {
            $group->muthawifAssignments()->delete();

            foreach (['utama', 'pendamping'] as $index => $type) {
                $group->muthawifAssignments()->create([
                    'user_id' => $muthawifIds[$index],
                    'assignment_type' => $type,
                    'assigned_by' => auth()->id(),
                ]);
            }
        });

        return back()->with(
            'success',
            'Dua Muthawif berhasil ditugaskan ke kelompok.'
        );
    }
}
