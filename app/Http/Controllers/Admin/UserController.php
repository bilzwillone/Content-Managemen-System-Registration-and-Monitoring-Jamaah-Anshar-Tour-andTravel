<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

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

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $allowedRoles = [
            User::ROLE_ADMIN,
            User::ROLE_KARYAWAN,
            User::ROLE_MUTHAWIF,
            User::ROLE_JAMAAH,
        ];

        if ($request->filled('role') && in_array($request->role, $allowedRoles, true)) {
            $query->where('role', $request->role);
        }

        $users = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

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

    /**
     * Menampilkan formulir tambah pengguna.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Menyimpan akun pengguna baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', 'in:admin,karyawan,muthawif,jamaah'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ], [
            'name.required' => 'Nama pengguna wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email tersebut sudah digunakan.',
            'role.required' => 'Role pengguna wajib dipilih.',
            'role.in' => 'Role pengguna tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user = new User();

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];
        $user->password = $validated['password'];

        $user->save();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Akun pengguna berhasil ditambahkan.');
    }

    /**
     * Menampilkan formulir edit pengguna.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Memperbarui data pengguna.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],

            'role' => [
                'required',
                'in:admin,karyawan,muthawif,jamaah',
            ],

            'password' => [
                'nullable',
                'confirmed',
                Password::defaults(),
            ],
        ], [
            'name.required' => 'Nama pengguna wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email tersebut sudah digunakan.',
            'role.required' => 'Role pengguna wajib dipilih.',
            'role.in' => 'Role pengguna tidak valid.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        if (
            $user->id === $request->user()->id &&
            $validated['role'] !== User::ROLE_ADMIN
        ) {
            return back()
                ->withInput($request->except(['password', 'password_confirmation']))
                ->withErrors([
                    'role' => 'Role akun Admin yang sedang digunakan tidak dapat diubah menjadi role lain.',
                ]);
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];

        if (!empty($validated['password'])) {
            $user->password = $validated['password'];
        }

        $user->save();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    /**
     * Menghapus akun pengguna.
     */
    public function destroy(Request $request, User $user)
    {
        // Admin tidak boleh menghapus akun sendiri.
        if ($user->id === $request->user()->id) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'Anda tidak dapat menghapus akun yang sedang digunakan.');
        }

        // Mencegah penghapusan Admin terakhir.
        if ($user->role === User::ROLE_ADMIN) {
            $adminCount = User::where('role', User::ROLE_ADMIN)->count();

            if ($adminCount <= 1) {
                return redirect()
                    ->route('admin.users.index')
                    ->with('error', 'Akun Admin terakhir tidak dapat dihapus.');
            }
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Akun pengguna berhasil dihapus.');
    }
}
