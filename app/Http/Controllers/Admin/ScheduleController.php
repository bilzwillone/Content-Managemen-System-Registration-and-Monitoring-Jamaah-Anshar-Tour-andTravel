<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $query = Schedule::query();

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();

            $query->where(function ($builder) use ($search) {
                $builder->where('name', 'like', "%{$search}%")
                    ->orWhere('airline', 'like', "%{$search}%");
            });
        }

        if (
            $request->filled('type') &&
            in_array($request->type, ['umrah', 'tour'], true)
        ) {
            $query->where('type', $request->type);
        }

        if (
            $request->filled('status') &&
            in_array($request->status, ['draft', 'published'], true)
        ) {
            $query->where('status', $request->status);
        }

        $schedules = $query
            ->orderBy('sort_order')
            ->orderBy('departure_date')
            ->paginate(10)
            ->withQueryString();

        $totalSchedules = Schedule::count();

        $publishedSchedules = Schedule::where('status', 'published')
            ->where('is_visible', true)
            ->count();

        $upcomingSchedules = Schedule::where('status', 'published')
            ->where('is_visible', true)
            ->whereDate('departure_date', '>=', today())
            ->count();

        return view('admin.schedules.index', compact(
            'schedules',
            'totalSchedules',
            'publishedSchedules',
            'upcomingSchedules'
        ));
    }

    public function create()
    {
        $nextSortOrder = ((int) Schedule::max('sort_order')) + 1;

        return view('admin.schedules.create', compact('nextSortOrder'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate(
            $this->rules(),
            $this->messages()
        );

        $validated['booked_seats'] = $validated['booked_seats'] ?? 0;

        $validated['sort_order'] = $validated['sort_order']
            ?? ((int) Schedule::max('sort_order') + 1);

        $validated['is_visible'] = $request->boolean('is_visible');

        Schedule::create($validated);

        return redirect()
            ->route('admin.schedules.index')
            ->with('success', 'Jadwal keberangkatan berhasil ditambahkan.');
    }

    public function edit(Schedule $schedule)
    {
        return view('admin.schedules.edit', compact('schedule'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        $validated = $request->validate(
            $this->rules(),
            $this->messages()
        );

        $validated['booked_seats'] = $validated['booked_seats'] ?? 0;

        $validated['is_visible'] = $request->boolean('is_visible');

        $schedule->update($validated);

        return redirect()
            ->route('admin.schedules.index')
            ->with('success', 'Jadwal keberangkatan berhasil diperbarui.');
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();

        return redirect()
            ->route('admin.schedules.index')
            ->with('success', 'Jadwal keberangkatan berhasil dihapus.');
    }

    private function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],

            'type' => [
                'required',
                Rule::in(['umrah', 'tour']),
            ],

            'departure_date' => ['required', 'date'],

            'return_date' => [
                'required',
                'date',
                'after_or_equal:departure_date',
            ],

            'airline' => ['nullable', 'string', 'max:255'],

            'total_seats' => [
                'required',
                'integer',
                'min:1',
                'max:100000',
            ],

            'booked_seats' => [
                'nullable',
                'integer',
                'min:0',
                'lte:total_seats',
            ],

            'description' => ['nullable', 'string', 'max:5000'],

            'sort_order' => ['nullable', 'integer', 'min:0'],

            'status' => [
                'required',
                Rule::in(['draft', 'published']),
            ],

            'is_visible' => ['nullable', 'boolean'],
        ];
    }

    private function messages(): array
    {
        return [
            'return_date.after_or_equal' =>
                'Tanggal pulang tidak boleh sebelum tanggal keberangkatan.',

            'booked_seats.lte' =>
                'Jumlah kursi terisi tidak boleh melebihi total kursi.',

            'total_seats.min' =>
                'Total kursi minimal 1.',
        ];
    }
}
