<?php

namespace App\Http\Controllers\Muthawif;

use App\Http\Controllers\Controller;
use App\Models\MuthawifActivity;
use App\Models\MuthawifActivityReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class MuthawifActivityReportController extends Controller
{
    /**
     * Menampilkan aktivitas yang perlu dilaporkan dan riwayat laporan.
     */
    public function index()
    {
        $assigned = fn ($query) => $query->whereHas('group.muthawifAssignments', function ($assignment) {
            $assignment->where('user_id', auth()->id());
        });

        $activities = MuthawifActivity::query()
            ->whereHas('group.muthawifAssignments', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->with(['group.schedule', 'report'])
            ->withCount('participants')
            ->orderByDesc('activity_date')
            ->orderByDesc('start_time')
            ->paginate(10, ['*'], 'activities_page');

        $reports = MuthawifActivityReport::query()
            ->whereHas('activity.group.muthawifAssignments', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->with(['activity.group.schedule', 'creator'])
            ->orderByDesc('updated_at')
            ->paginate(10, ['*'], 'reports_page');

        $summary = [
            'activities' => MuthawifActivity::whereHas('group.muthawifAssignments', function ($query) {
                $query->where('user_id', auth()->id());
            })->count(),
            'reports' => MuthawifActivityReport::whereHas('activity.group.muthawifAssignments', function ($query) {
                $query->where('user_id', auth()->id());
            })->count(),
            'drafts' => MuthawifActivityReport::where('status', 'draft')
                ->whereHas('activity.group.muthawifAssignments', function ($query) {
                    $query->where('user_id', auth()->id());
                })->count(),
            'submitted' => MuthawifActivityReport::where('status', 'submitted')
                ->whereHas('activity.group.muthawifAssignments', function ($query) {
                    $query->where('user_id', auth()->id());
                })->count(),
        ];

        return view('muthawif.reports.index', compact('activities', 'reports', 'summary'));
    }

    /** Menampilkan form laporan untuk aktivitas yang menjadi tanggung jawab user. */
    public function create(MuthawifActivity $activity)
    {
        $this->ensureActivityAssigned($activity);

        $activity->load(['group.schedule', 'participants.jamaah']);

        if ($activity->report) {
            return redirect()
                ->route('muthawif.reports.show', $activity->report)
                ->with('info', 'Laporan untuk kegiatan ini sudah dibuat.');
        }

        return view('muthawif.reports.create', compact('activity'));
    }

    /** Menyimpan laporan baru sebagai draft atau langsung mengirimkannya. */
    public function store(Request $request, MuthawifActivity $activity)
    {
        $this->ensureActivityAssigned($activity);

        if ($activity->report()->exists()) {
            return redirect()
                ->route('muthawif.reports.index')
                ->with('info', 'Laporan untuk kegiatan ini sudah tersedia.');
        }

        $action = $request->input('action', 'draft');
        $isSubmitting = $action === 'submit';

        if (!in_array($action, ['draft', 'submit'], true)) {
            throw ValidationException::withMessages([
                'action' => 'Pilihan penyimpanan laporan tidak valid.',
            ]);
        }

        $validated = $this->validateReport($request, $isSubmitting);

        if ($isSubmitting) {
            $this->validateAttendanceTotal($activity, $validated);
        }

        $report = DB::transaction(function () use ($activity, $validated, $isSubmitting) {
            $lockedActivity = MuthawifActivity::whereKey($activity->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedActivity->report()->exists()) {
                throw ValidationException::withMessages([
                    'report' => 'Laporan untuk kegiatan ini sudah dibuat.',
                ]);
            }

            return MuthawifActivityReport::create([
                'muthawif_activity_id' => $lockedActivity->id,
                'present_count' => $validated['present_count'] ?? null,
                'absent_count' => $validated['absent_count'] ?? null,
                'implementation_notes' => $validated['implementation_notes'] ?? null,
                'issues' => $validated['issues'] ?? null,
                'follow_up' => $validated['follow_up'] ?? null,
                'status' => $isSubmitting ? 'submitted' : 'draft',
                'submitted_at' => $isSubmitting ? now() : null,
                'created_by' => auth()->id(),
            ]);
        });

        return redirect()
            ->route('muthawif.reports.show', $report)
            ->with('success', $isSubmitting
                ? 'Laporan pelaksanaan berhasil dikirim.'
                : 'Laporan berhasil disimpan sebagai draft.');
    }

    /** Menampilkan riwayat dan detail satu laporan. */
    public function show(MuthawifActivityReport $report)
    {
        $this->ensureReportAssigned($report);

        $report->load([
            'activity.group.schedule',
            'activity.participants.jamaah',
            'creator',
        ]);

        return view('muthawif.reports.show', compact('report'));
    }

    /** Memperbarui laporan yang masih berstatus draft. */
    public function update(Request $request, MuthawifActivityReport $report)
    {
        $this->ensureReportAssigned($report);
        abort_if($report->status !== 'draft', 403, 'Laporan yang sudah dikirim tidak dapat diubah.');

        $validated = $this->validateReport($request, false);

        $report->update([
            'present_count' => $validated['present_count'] ?? null,
            'absent_count' => $validated['absent_count'] ?? null,
            'implementation_notes' => $validated['implementation_notes'] ?? null,
            'issues' => $validated['issues'] ?? null,
            'follow_up' => $validated['follow_up'] ?? null,
        ]);

        return redirect()
            ->route('muthawif.reports.show', $report)
            ->with('success', 'Draft laporan berhasil diperbarui.');
    }

    /** Memvalidasi kelengkapan dan mengunci laporan sebagai terkirim. */
    public function submit(MuthawifActivityReport $report)
    {
        $this->ensureReportAssigned($report);
        abort_if($report->status !== 'draft', 403, 'Laporan ini sudah dikirim.');

        $validated = [
            'present_count' => $report->present_count,
            'absent_count' => $report->absent_count,
            'implementation_notes' => $report->implementation_notes,
        ];

        $validator = validator($validated, [
            'present_count' => ['required', 'integer', 'min:0'],
            'absent_count' => ['required', 'integer', 'min:0'],
            'implementation_notes' => ['required', 'string'],
        ]);
        $validator->validate();

        $this->validateAttendanceTotal($report->activity, $validated);

        $report->update([
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        return redirect()
            ->route('muthawif.reports.show', $report)
            ->with('success', 'Laporan pelaksanaan berhasil dikirim.');
    }

    private function validateReport(Request $request, bool $submitting): array
    {
        return $request->validate([
            'present_count' => [$submitting ? 'required' : 'nullable', 'integer', 'min:0'],
            'absent_count' => [$submitting ? 'required' : 'nullable', 'integer', 'min:0'],
            'implementation_notes' => [$submitting ? 'required' : 'nullable', 'string'],
            'issues' => ['nullable', 'string'],
            'follow_up' => ['nullable', 'string'],
        ]);
    }

    private function validateAttendanceTotal(MuthawifActivity $activity, array $validated): void
    {
        $totalParticipants = $activity->participants()->count();
        $present = (int) ($validated['present_count'] ?? 0);
        $absent = (int) ($validated['absent_count'] ?? 0);

        if ($totalParticipants < 1) {
            throw ValidationException::withMessages([
                'present_count' => 'Aktivitas ini belum memiliki peserta jamaah.',
            ]);
        }

        if (($present + $absent) !== $totalParticipants) {
            throw ValidationException::withMessages([
                'present_count' => "Jumlah hadir dan tidak hadir harus sama dengan total peserta aktivitas ({$totalParticipants} jamaah).",
            ]);
        }
    }

    private function ensureActivityAssigned(MuthawifActivity $activity): void
    {
        $isAssigned = $activity->group()
            ->whereHas('muthawifAssignments', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->exists();

        abort_unless($isAssigned, 403, 'Anda tidak memiliki akses ke aktivitas kelompok ini.');
    }

    private function ensureReportAssigned(MuthawifActivityReport $report): void
    {
        $isAssigned = $report->activity()
            ->whereHas('group.muthawifAssignments', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->exists();

        abort_unless($isAssigned, 403, 'Anda tidak memiliki akses ke laporan kelompok ini.');
    }
}
