<?php

namespace App\Http\Controllers\Muthawif;

use App\Http\Controllers\Controller;
use App\Models\JamaahGroup;
use App\Models\MuthawifActivity;
use App\Models\MuthawifActivityJamaah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class MuthawifActivityController extends Controller
{
    private const ACTIVITY_TYPES = [
        'persiapan_ibadah',
        'bimbingan_ibadah',
        'istirahat',
        'evaluasi_kelompok',
        'lainnya',
    ];

    private const STATUSES = [
        'belum_dilaksanakan',
        'sedang_berlangsung',
        'selesai_dilaksanakan',
    ];

    /** Daftar kegiatan hanya untuk kelompok yang ditugaskan kepada akun login. */
    public function index()
    {
        $groups = $this->assignedGroups()
            ->with('schedule')
            ->orderBy('name')
            ->get();

        $activities = MuthawifActivity::query()
            ->whereHas('group.muthawifAssignments', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->with(['group.schedule'])
            ->withCount('participants')
            ->withCount([
                'participants as completed_count' => fn ($query) => $query->where('status', 'selesai_dilaksanakan'),
                'participants as in_progress_count' => fn ($query) => $query->where('status', 'sedang_berlangsung'),
            ])
            ->orderByDesc('activity_date')
            ->orderByDesc('start_time')
            ->paginate(10);

        return view('muthawif.activities.index', compact('groups', 'activities'));
    }

    /** Membuat kegiatan dan snapshot peserta dari anggota aktif kelompok. */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'jamaah_group_id' => ['required', 'integer', 'exists:jamaah_groups,id'],
            'activity_type' => ['required', Rule::in(self::ACTIVITY_TYPES)],
            'activity_name' => ['required', 'string', 'max:180'],
            'activity_date' => ['required', 'date'],
            'start_time' => ['nullable', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i', 'after_or_equal:start_time'],
            'location' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $group = $this->assignedGroups()->whereKey($validated['jamaah_group_id'])->firstOrFail();
        $activeMembers = $group->memberRecords()->where('status', 'aktif')->get();

        if ($activeMembers->isEmpty()) {
            throw ValidationException::withMessages([
                'jamaah_group_id' => 'Kelompok ini belum memiliki anggota jamaah aktif.',
            ]);
        }

        $activity = DB::transaction(function () use ($group, $validated, $activeMembers) {
            $activity = MuthawifActivity::create([
                'jamaah_group_id' => $group->id,
                'activity_type' => $validated['activity_type'],
                'activity_name' => $validated['activity_name'],
                'activity_date' => $validated['activity_date'],
                'start_time' => $validated['start_time'] ?? null,
                'end_time' => $validated['end_time'] ?? null,
                'location' => $validated['location'] ?? null,
                'description' => $validated['description'] ?? null,
                'created_by' => auth()->id(),
            ]);

            $activity->participants()->createMany(
                $activeMembers->map(fn ($member) => [
                    'jamaah_id' => $member->jamaah_id,
                    'status' => 'belum_dilaksanakan',
                ])->all()
            );

            return $activity;
        });

        return redirect()
            ->route('muthawif.activities.show', $activity)
            ->with('success', 'Kegiatan berhasil dibuat dan daftar jamaah aktif telah ditambahkan.');
    }

    /** Detail kegiatan; sinkronkan anggota aktif baru tanpa menimpa status lama. */
    public function show(MuthawifActivity $activity)
    {
        $this->ensureActivityAssigned($activity);

        $group = $activity->group()->firstOrFail();
        $activeMembers = $group->memberRecords()->where('status', 'aktif')->get();

        foreach ($activeMembers as $member) {
            $activity->participants()->firstOrCreate(
                ['jamaah_id' => $member->jamaah_id],
                ['status' => 'belum_dilaksanakan']
            );
        }

        $activity->load([
            'group.schedule',
            'participants.jamaah',
            'participants.updater',
        ]);

        $summary = [
            'total' => $activity->participants->count(),
            'belum' => $activity->participants->where('status', 'belum_dilaksanakan')->count(),
            'proses' => $activity->participants->where('status', 'sedang_berlangsung')->count(),
            'selesai' => $activity->participants->where('status', 'selesai_dilaksanakan')->count(),
        ];

        return view('muthawif.activities.show', compact('activity', 'summary'));
    }

    /** Memperbarui status dan catatan pendampingan satu jamaah. */
    public function updateParticipant(
        Request $request,
        MuthawifActivity $activity,
        MuthawifActivityJamaah $participant
    ) {
        $this->ensureActivityAssigned($activity);

        $participant = $activity->participants()
            ->whereKey($participant->id)
            ->firstOrFail();

        $validated = $request->validate([
            'status' => ['required', Rule::in(self::STATUSES)],
            'assistance_notes' => ['nullable', 'string', 'max:5000'],
        ]);

        $participant->update([
            'status' => $validated['status'],
            'assistance_notes' => $validated['assistance_notes'] ?? null,
            'updated_by' => auth()->id(),
        ]);

        return back()->with('success', 'Status kegiatan jamaah berhasil diperbarui.');
    }

    private function assignedGroups()
    {
        return JamaahGroup::query()->whereHas('muthawifAssignments', function ($query) {
            $query->where('user_id', auth()->id());
        });
    }

    private function ensureActivityAssigned(MuthawifActivity $activity): void
    {
        $isAssigned = $activity->group()
            ->whereHas('muthawifAssignments', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->exists();

        abort_unless($isAssigned, 403, 'Anda tidak memiliki akses ke kegiatan kelompok ini.');
    }
}
