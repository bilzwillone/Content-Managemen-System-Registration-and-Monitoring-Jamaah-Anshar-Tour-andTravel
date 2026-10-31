
<?php

use App\Http\Controllers\ProfileController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = auth()->user();

    return match ($user->role) {
        User::ROLE_ADMIN => redirect()->route('admin.dashboard'),
        User::ROLE_KARYAWAN => redirect()->route('karyawan.dashboard'),
        User::ROLE_MUTHAWIF => redirect()->route('muthawif.dashboard'),
        User::ROLE_JAMAAH => redirect()->route('jamaah.dashboard'),
        default => abort(403, 'Peran pengguna tidak dikenali.'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', function () { return view('admin.dashboard');})->name('dashboard');
        Route::get('/pages', [\App\Http\Controllers\Admin\PageController::class, 'index']) ->name('pages.index');
        Route::get('/pages/{page}/sections', [\App\Http\Controllers\Admin\PageController::class,'sections'])->name('pages.sections');
    });

// Dashboard Karyawan
Route::middleware(['auth', 'role:karyawan'])
    ->prefix('karyawan')
    ->name('karyawan.')
    ->group(function () {
        Route::get('/', function () {
            return view('dashboard.role', [
                'title' => 'Dashboard Karyawan Ansar Travel',
            ]);
        })->name('dashboard');
    });

// Dashboard Muthawif
Route::middleware(['auth', 'role:muthawif'])
    ->prefix('muthawif')
    ->name('muthawif.')
    ->group(function () {
        Route::get('/', function () {
            return view('dashboard.role', [
                'title' => 'Dashboard Muthawif Ansar Travel',
            ]);
        })->name('dashboard');
    });

// Dashboard Jamaah
Route::middleware(['auth', 'role:jamaah'])
    ->prefix('jamaah')
    ->name('jamaah.')
    ->group(function () {
        Route::get('/', function () {
            return view('dashboard.role', [
                'title' => 'Dashboard Jamaah Ansar Travel',
            ]);
        })->name('dashboard');
    });


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

require __DIR__.'/auth.php';
