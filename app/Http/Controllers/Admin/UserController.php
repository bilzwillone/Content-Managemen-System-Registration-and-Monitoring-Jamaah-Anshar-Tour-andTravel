<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Menampilkan daftar seluruh pengguna.
     */
    public function index(Request $request)
    {
        $query = User::query()
            ->select([
                'id',
                'name',
                'email',
                'role',
                'created_at',
            ]);

        // Pencarian berdasarkan nama atau email.
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan role.
        $allowedRoles = [
            User::ROLE_ADMIN,
            User::ROLE_KARYAWAN,
            User::ROLE_MUTHAWIF,
            User::ROLE_JAMAAH,
        ];

        if ($request->filled('role') && in_array($request->role, $allowedRoles, true)) {
            $query->where('role', $request->role);
        }

        // Daftar pengguna dengan pagination.
        $users = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // Ringkasan jumlah pengguna berdasarkan role.
        $totalUsers = User::count();

        $roleCounts = User::query()
            ->selectRaw('role, COUNT(*) as total')
            ->groupBy('role')
            ->pluck('total', 'role');

        return view('admin.users.index', compact(
            'users',
            'totalUsers',
            'roleCounts'
        ));
    }
}
