<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\PublicPageController;
use App\Http\Controllers\PublicScheduleController;
use App\Http\Controllers\Karyawan\ProspectController;
use App\Http\Controllers\Karyawan\ProspectFollowUpController;
use App\Http\Controllers\Karyawan\JamaahController;
use App\Http\Controllers\Karyawan\JamaahDocumentController;
use App\Http\Controllers\PackageController as PublicPackageController;
use App\Models\User;
use App\Models\Page;
use App\Models\Package;
use Illuminate\Support\Facades\Route;

// HALAMAN BERANDA
Route::get('/', function () {

    $page = Page::where('slug', 'beranda')
        ->where('is_published', true)
        ->with(['sections' => function ($query) {
            $query->where('status', 'published')
                ->where('is_visible', true);
        }])
        ->firstOrFail();

    $packages = Package::where('status', 'published')
        ->where('is_visible', true)
        ->where('is_featured', true)
        ->latest()
        ->get();

    return view('welcome', compact('page', 'packages'));

});

// HALAMAN PAKET UMROH
Route::get('/paket-umroh', function () {

    $packages = Package::where('status', 'published')
        ->where('is_visible', true)
        ->latest()
        ->paginate(9);

    return view('packages.index', compact('packages'));

})->name('packages.index');

Route::get('/paket-umroh/{slug}', [PublicPackageController::class, 'show'])
    ->name('packages.show');

// HALAMAN JADWAL PUBLIK
Route::get('/jadwal', [PublicScheduleController::class, 'index'])
    ->name('schedules.public');

// PENGALIHAN DASHBOARD BERDASARKAN ROLE
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


// ADMIN
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        // MANAJEMEN PENGGUNA
        Route::get('/users', [UserController::class, 'index'])
            ->name('users.index');

        Route::get('/users/create', [UserController::class, 'create'])
            ->name('users.create');

        Route::post('/users', [UserController::class, 'store'])
            ->name('users.store');

        Route::get('/users/{user}/edit', [UserController::class, 'edit'])
            ->name('users.edit');

        Route::put('/users/{user}', [UserController::class, 'update'])
            ->name('users.update');
        
        Route::delete('/users/{user}', [UserController::class, 'destroy'])
            ->name('users.destroy');

        Route::delete('/pages/{page}', [PageController::class, 'destroy'])
            ->name('pages.destroy');

        // KELOLA HALAMAN
        Route::get('/pages', [PageController::class, 'index'])
            ->name('pages.index');

        Route::get('/pages/create', [PageController::class, 'create'])
            ->name('pages.create');

        Route::post('/pages', [PageController::class, 'store'])
            ->name('pages.store');

        Route::get('/pages/{page}/edit', [PageController::class, 'edit'])
            ->name('pages.edit');

        Route::put('/pages/{page}', [PageController::class, 'update'])
            ->name('pages.update');

        // KELOLA SECTION
        Route::get('/pages/{page}/sections', [PageController::class, 'sections'])
            ->name('pages.sections');

        Route::get('/pages/{page}/sections/create', [PageController::class, 'createSection'])
            ->name('pages.sections.create');

        Route::post('/pages/{page}/sections', [PageController::class, 'storeSection'])
            ->name('pages.sections.store');

        Route::get('/pages/{page}/sections/{section}/edit', [PageController::class, 'editSection'])
            ->name('pages.sections.edit');

        Route::put('/pages/{page}/sections/{section}', [PageController::class, 'updateSection'])
            ->name('pages.sections.update');

        Route::delete('/pages/{page}/sections/{section}', [PageController::class, 'destroySection'])
            ->name('pages.sections.destroy');

        Route::patch('/pages/{page}/sections/{section}/settings', [PageController::class, 'updateSectionSettings'])
            ->name('pages.sections.settings');

        Route::patch('/pages/{page}/sections/{section}/order', [PageController::class, 'updateSectionOrder'])
            ->name('pages.sections.order');


        // KELOLA PAKET UMROH
        Route::get('/packages', [PackageController::class, 'index'])
            ->name('packages.index');

        Route::get('/packages/create', [PackageController::class, 'create'])
            ->name('packages.create');

        Route::post('/packages', [PackageController::class, 'store'])
            ->name('packages.store');

        Route::get('/packages/{package}/edit', [PackageController::class, 'edit'])
            ->name('packages.edit');

        Route::put('/packages/{package}', [PackageController::class, 'update'])
            ->name('packages.update');

        Route::delete('/packages/{package}/images/{image}', [PackageController::class, 'destroyImage'])
            ->name('packages.images.destroy');

        Route::delete('/packages/{package}', [PackageController::class, 'destroy'])
            ->name('packages.destroy');


        // MANAJEMEN JADWAL KEBERANGKATAN
        Route::resource('schedules', ScheduleController::class)
            ->except(['show'])
            ->parameters([
                'schedules' => 'schedule',
            ]);

    });

Route::middleware(['auth', 'role:karyawan'])
    ->prefix('karyawan')
    ->name('karyawan.')
    ->group(function () {

        Route::get('/', function () {
            return view('dashboard.karyawan');
        })->name('dashboard');

        Route::resource('prospects', ProspectController::class);
        Route::post('/prospects/{prospect}/follow-ups', [ProspectFollowUpController::class, 'store'])->name('prospects.follow-ups.store');
        Route::post('/prospects/{prospect}/convert',[ProspectController::class, 'convertToJamaah'])->name('prospects.convert');
        Route::get('/jamaahs', [JamaahController::class, 'index'])->name('jamaahs.index');

        Route::prefix('documents')
            ->name('documents.')
            ->group(function () {

                Route::get('/', [JamaahDocumentController::class, 'index'])
                    ->name('index');

                Route::get('/{jamaah}', [JamaahDocumentController::class, 'show'])
                    ->name('show');

                Route::post('/{jamaah}/{document}/upload', [JamaahDocumentController::class, 'upload'])
                    ->name('upload');

                Route::patch('/{jamaah}/{document}/verify', [JamaahDocumentController::class, 'verify'])
                    ->name('verify');

                Route::get('/{jamaah}/{document}/download', [JamaahDocumentController::class, 'download'])
                    ->name('download');
            });
    });


// DASHBOARD MUTHAWIF
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


// DASHBOARD JAMAAH
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


// PROFILE PENGGUNA
Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

});

require __DIR__ . '/auth.php';

Route::get('/{slug}', [PublicPageController::class, 'show'])
    ->where('slug', '[A-Za-z0-9_-]+')
    ->name('pages.public.show');
