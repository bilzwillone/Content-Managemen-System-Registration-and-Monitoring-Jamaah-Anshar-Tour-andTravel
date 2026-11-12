
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
use App\Http\Controllers\Karyawan\PaymentController;
use App\Http\Controllers\Karyawan\JamaahGroupController;
use App\Http\Controllers\Karyawan\DepartureChecklistController;
use App\Http\Controllers\Karyawan\DepartureRoomAssignmentController;
use App\Http\Controllers\Karyawan\DeparturePreparationController;
use App\Http\Controllers\Muthawif\MuthawifController;
use App\Http\Controllers\Muthawif\MuthawifActivityController;
use App\Http\Controllers\Muthawif\MuthawifActivityReportController;
use App\Http\Controllers\Jamaah\JamaahPortalController;
use App\Http\Controllers\Jamaah\JamaahPaymentController;
use App\Http\Controllers\PackageController as PublicPackageController;
use App\Http\Controllers\MidtransWebhookController;
use App\Models\User;
use App\Models\Page;
use App\Models\Package;
use Illuminate\Support\Facades\Route;

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

Route::get('/paket-umroh', function () {

    $packages = Package::where('status', 'published')
        ->where('is_visible', true)
        ->latest()
        ->paginate(9);

    return view('packages.index', compact('packages'));

})->name('packages.index');

Route::get('/paket-umroh/{slug}', [PublicPackageController::class, 'show'])
    ->name('packages.show');


Route::get('/jadwal', [PublicScheduleController::class, 'index'])
    ->name('schedules.public');

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

        Route::get('/', function () {
            return view('admin.dashboard');
        })->name('dashboard');

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

        Route::delete('/pages/{page}', [PageController::class, 'destroy'])
            ->name('pages.destroy');

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

        Route::post(
            '/prospects/{prospect}/follow-ups',
            [ProspectFollowUpController::class, 'store']
        )->name('prospects.follow-ups.store');

        Route::post(
            '/prospects/{prospect}/convert',
            [ProspectController::class, 'convertToJamaah']
        )->name('prospects.convert');

        Route::get('/jamaahs', [JamaahController::class, 'index'])
            ->name('jamaahs.index');

        Route::get('/jamaahs/{jamaah}/edit', [JamaahController::class, 'edit'])
            ->name('jamaahs.edit');

        Route::put('/jamaahs/{jamaah}', [JamaahController::class, 'update'])
            ->name('jamaahs.update');

        Route::get('/jamaahs/{jamaah}', [JamaahController::class, 'show'])
            ->name('jamaahs.show');
     
        Route::prefix('departures')->name('departures.')->group(function () {

            Route::get('/', [DeparturePreparationController::class, 'index'])
                ->name('index');

            Route::get('/create', [DeparturePreparationController::class, 'create'])
                ->name('create');

            Route::post('/', [DeparturePreparationController::class, 'store'])
                ->name('store');

            Route::get('/{preparation}', [DeparturePreparationController::class, 'show'])
                ->name('show');

            Route::put('/{preparation}', [DeparturePreparationController::class, 'update'])
                ->name('update');

            Route::post('/{preparation}/rooms', [
                DepartureRoomAssignmentController::class,
                'store'
            ])->name('rooms.store');

            Route::put('/{preparation}/rooms/{roomAssignment}', [
                DepartureRoomAssignmentController::class,
                'update'
            ])->name('rooms.update');

            Route::delete('/{preparation}/rooms/{roomAssignment}', [
                DepartureRoomAssignmentController::class,
                'destroy'
            ])->name('rooms.destroy');

            Route::post('/{preparation}/checklists', [
                DepartureChecklistController::class,
                'store'
            ])->name('checklists.store');

            Route::put('/{preparation}/checklists/{checklist}', [
                DepartureChecklistController::class,
                'update'
            ])->name('checklists.update');

            Route::delete('/{preparation}/checklists/{checklist}', [
                DepartureChecklistController::class,
                'destroy'
            ])->name('checklists.destroy');

        });
        
        Route::resource('groups', JamaahGroupController::class)
            ->only([
                'index',
                'create',
                'store',
                'show',
                'update',
                'destroy',
            ]);

        Route::post('/groups/{group}/members', [
            JamaahGroupController::class,
            'addMember'
        ])->name('groups.members.store');

        Route::delete('/groups/{group}/members/{member}', [
            JamaahGroupController::class,
            'removeMember'
        ])->name('groups.members.destroy');

        Route::put('/groups/{group}/muthawifs', [
            JamaahGroupController::class,
            'assignMuthawifs'
        ])->name('groups.muthawifs.update');

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

            Route::prefix('payments')->name('payments.')->group(function () {
                Route::get('/', [PaymentController::class, 'index'])
                    ->name('index');

                Route::get('/history', [PaymentController::class, 'history'])
                    ->name('history');
                    
                Route::post('/{jamaah}/manual', [PaymentController::class, 'storeManual'])
                    ->name('manual');

            });
    });

Route::prefix('muthawif')
    ->name('muthawif.')
    ->middleware(['auth', 'role:muthawif'])
    ->group(function () {

        Route::get('/dashboard', [
            MuthawifController::class,
            'dashboard'
        ])->name('dashboard');

        Route::get('/groups', [
            MuthawifController::class,
            'groups'
        ])->name('groups.index');

        Route::get('/groups/{group}', [
            MuthawifController::class,
            'showGroup'
        ])->name('groups.show');

        Route::get('/activities', [MuthawifActivityController::class, 'index'])
            ->name('activities.index');

        Route::post('/activities', [MuthawifActivityController::class, 'store'])
            ->name('activities.store');

        Route::get('/activities/{activity}', [MuthawifActivityController::class, 'show'])
            ->name('activities.show');

        Route::patch('/activities/{activity}/participants/{participant}', [MuthawifActivityController::class, 'updateParticipant'])
            ->name('activities.participants.update');

        Route::get('/reports', [MuthawifActivityReportController::class, 'index'])
            ->name('reports.index');

        Route::get('/reports/activities/{activity}/create', [MuthawifActivityReportController::class, 'create'])
            ->name('reports.create');

        Route::post('/reports/activities/{activity}', [MuthawifActivityReportController::class, 'store'])
            ->name('reports.store');

        Route::get('/reports/{report}', [MuthawifActivityReportController::class, 'show'])
            ->name('reports.show');

        Route::put('/reports/{report}', [MuthawifActivityReportController::class, 'update'])
            ->name('reports.update');

        Route::patch('/reports/{report}/submit', [MuthawifActivityReportController::class, 'submit'])
            ->name('reports.submit');

    });

Route::prefix('jamaah')->name('jamaah.')->middleware(['auth', 'verified', 'role:jamaah'])->group(function () {
    Route::get('/dashboard', [JamaahPortalController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [JamaahPortalController::class, 'profile'])->name('profile');
    Route::put('/profile', [JamaahPortalController::class, 'updateProfile'])->name('profile.update');
    Route::get('/documents', [JamaahPortalController::class, 'documents'])->name('documents');
    Route::post('/documents/{document}/upload', [JamaahPortalController::class, 'uploadDocument'])->name('documents.upload');
    Route::get('/payments', [JamaahPortalController::class, 'payments'])->name('payments');
    Route::get('/travel', [JamaahPortalController::class, 'travel'])->name('travel');
    Route::post('/payments', [JamaahPaymentController::class, 'store'])->name('payments.store');
    
});

Route::post('/midtrans/notification', [
    MidtransWebhookController::class,
    'handle'
])->name('midtrans.notification');

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
