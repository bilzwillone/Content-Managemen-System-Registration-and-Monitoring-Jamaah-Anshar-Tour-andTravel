<?php

namespace App\Http\Controllers;

use App\Models\Schedule;

class PublicScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::query()
            ->where('status', 'published')
            ->where('is_visible', true)
            ->orderBy('sort_order')
            ->orderBy('departure_date')
            ->get();

        $umrahSchedules = $schedules
            ->where('type', 'umrah')
            ->values();

        $tourSchedules = $schedules
            ->where('type', 'tour')
            ->values();

        return view('schedules.index', compact(
            'umrahSchedules',
            'tourSchedules'
        ));
    }
}
