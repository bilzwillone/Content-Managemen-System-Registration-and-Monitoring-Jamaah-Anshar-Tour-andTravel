<?php

namespace App\Http\Controllers\Muthawif;

use App\Http\Controllers\Controller;
use App\Models\JamaahGroup;
use Illuminate\Http\Request;

class MuthawifController extends Controller
{
    /**
     * Menampilkan dashboard berdasarkan kelompok yang ditugaskan
     * kepada akun Muthawif yang sedang login.
     */
    public function dashboard()
    {
        $groups = $this->assignedGroupsQuery()
            ->orderBy('id')
            ->get();

        $totalGroups = $groups->count();
        $totalJamaah = $groups->sum(fn ($group) => $group->members->count());

        $upcomingGroups = $groups
            ->filter(function ($group) {
                return $group->schedule?->departure_date
                    && $group->schedule->departure_date->greaterThanOrEqualTo(today());
            })
            ->sortBy(fn ($group) => $group->schedule?->departure_date)
            ->take(5)
            ->values();

        return view('muthawif.dashboard', compact(
            'groups',
            'totalGroups',
            'totalJamaah',
            'upcomingGroups'
        ));
    }

    /**
     * Menampilkan seluruh kelompok yang menjadi tanggung jawab Muthawif.
     */
    public function groups()
    {
        $groups = $this->assignedGroupsQuery()
            ->orderBy('id')
            ->paginate(10);

        return view('muthawif.groups.index', compact('groups'));
    }

    /**
     * Menampilkan detail kelompok, anggota, penugasan Muthawif,
     * dan informasi jadwal/persiapan keberangkatan.
     */
    public function showGroup(JamaahGroup $group)
    {
        // Ambil ulang kelompok melalui query yang sudah dibatasi
        // berdasarkan penugasan akun Muthawif yang sedang login.
        $group = $this->assignedGroupsQuery()
            ->whereKey($group->getKey())
            ->firstOrFail();

        return view('muthawif.groups.show', compact('group'));
    }

    /**
     * Query pusat untuk memastikan Muthawif hanya melihat kelompok
     * yang memang ditugaskan kepadanya, baik sebagai utama maupun pendamping.
     */
    private function assignedGroupsQuery()
    {
        return JamaahGroup::query()
            ->whereHas('muthawifs', function ($query) {
                $query->where('users.id', auth()->id());
            })
            ->with([
                'schedule.departurePreparation',
                'members' => function ($query) {
                    $query->where('jamaah_group_members.status', 'aktif')
                        ->orderBy('jamaahs.name');
                },
                'muthawifs',
            ]);
    }
}
