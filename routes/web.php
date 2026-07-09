<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

// Master Data Controllers
// Master Data Imports
// Master Data Imports
use App\Http\Controllers\Master\StudentController;
use App\Http\Controllers\Master\AcademicYearController;
use App\Http\Controllers\Master\PeriodController;
use App\Http\Controllers\Master\RoomController;
use App\Http\Controllers\Master\RayonController;
use App\Http\Controllers\Master\EducationLevelController;
use App\Http\Controllers\Master\DepartmentController;
use App\Http\Controllers\Master\MemorizationTypeController;
use App\Http\Controllers\Master\RegionController;
use App\Http\Controllers\Master\GuardianController;

// Assessment & Other Imports


// Landing page — pilih role (admin / wali santri)
Route::get('/', function () {
    if (auth()->check()) return redirect()->route('admin.dashboard');
    if (auth()->guard('guardian')->check()) return redirect()->route('guardian.dashboard');
    return view('landing');
})->name('landing');

// Admin Auth Routes
Route::middleware('guest')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

// Guardian Routes
Route::prefix('guardian')->name('guardian.')->group(function () {
    Route::middleware('guest:guardian')->group(function () {
        Route::get('/login', [\App\Http\Controllers\Guardian\AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [\App\Http\Controllers\Guardian\AuthController::class, 'login'])->name('login.post');
    });
    Route::middleware('auth:guardian')->group(function () {
        Route::post('/logout', [\App\Http\Controllers\Guardian\AuthController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [\App\Http\Controllers\Guardian\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/licenses', [\App\Http\Controllers\Guardian\LicenseController::class, 'index'])->name('licenses.index');
        Route::get('/licenses/create', [\App\Http\Controllers\Guardian\LicenseController::class, 'create'])->name('licenses.create');
        Route::post('/licenses', [\App\Http\Controllers\Guardian\LicenseController::class, 'store'])->name('licenses.store');
        Route::get('/profile', [\App\Http\Controllers\Guardian\ProfileController::class, 'show'])->name('profile');
        Route::put('/profile', [\App\Http\Controllers\Guardian\ProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [\App\Http\Controllers\Guardian\ProfileController::class, 'updatePassword'])->name('profile.password');
    });
});

// Protected Admin Routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/password/change', [AuthController::class, 'showChangePasswordForm'])->name('password.change');
    Route::post('/password/change', [AuthController::class, 'changePassword'])->name('password.update');

    // Region Routes
    Route::get('/regions/cities', [RegionController::class, 'cities'])->name('regions.cities');
    Route::get('/regions/districts', [RegionController::class, 'districts'])->name('regions.districts');
    Route::get('/regions/villages', [RegionController::class, 'villages'])->name('regions.villages');
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Master Data: Academic Years & Periods
    Route::post('/academic-years/{academic_year}/toggle-status', [AcademicYearController::class, 'toggleStatus'])->name('academic-years.toggle-status');
    Route::resource('academic-years', AcademicYearController::class);
    Route::post('/periods/{period}/activate', [PeriodController::class, 'activate'])->name('periods.activate');
    Route::resource('periods', PeriodController::class);

    // Master Data: Education (Jenjang) & Rooms
    Route::resource('education-levels', EducationLevelController::class);
    Route::resource('rayons', RayonController::class);
    Route::resource('rooms', RoomController::class);
    Route::resource('departments', DepartmentController::class);
    Route::resource('memorization-types', MemorizationTypeController::class);

    // Students
    // Route::get('students/template', [StudentController::class, 'downloadTemplate'])->name('students.template');
    // Route::get('students/export', [StudentController::class, 'export'])->name('students.export');
    // Route::post('/students/import', [StudentController::class, 'import'])->name('students.import');
    Route::resource('students', StudentController::class);
    Route::get('guardians/search-students', [GuardianController::class, 'searchStudents'])->name('guardians.search-students');
    Route::resource('guardians', GuardianController::class);

    // User Management (Licensing & Finance)
    Route::resource('users', \App\Http\Controllers\UserController::class);
    // Legacy mapping for views if they still point to 'santri' (Optional, but better to fix views)
    // Route::resource('santri', StudentController::class);

    // Assessments / Penilaian
    // Route::resource('assessments', AssessmentController::class);

    // Finance / Keuangan
    Route::resource('spp-payments', \App\Http\Controllers\Finance\SppPaymentController::class);

    // Theme
    Route::get('/set-theme/{theme}', function ($theme) {
        if (in_array($theme, ['default', 'blue', 'purple'])) {
            session(['theme' => $theme]);
        }
        return back();
    })->name('theme.set');

    // Violation Routes
    Route::get('/violations/history/{student}', [\App\Http\Controllers\Violation\ViolationController::class, 'history'])->name('violations.history');
    Route::post('/violations/{id}/verify-sanction', [\App\Http\Controllers\Violation\ViolationController::class, 'verifySanction'])->name('violations.verify-sanction');
    Route::resource('violations', \App\Http\Controllers\Violation\ViolationController::class);

    Route::resource('violation-types', \App\Http\Controllers\Violation\ViolationTypeController::class);
    Route::resource('violation-categories', \App\Http\Controllers\Violation\ViolationCategoryController::class);

    // Licensing Routes
    Route::get('/licenses', [\App\Http\Controllers\Licensing\LicenseController::class, 'index'])->name('licenses.index');

    // Individual License
    Route::get('/licenses/create', [\App\Http\Controllers\Licensing\LicenseController::class, 'create'])->name('licenses.create');
    Route::post('/licenses/store-individual', [\App\Http\Controllers\Licensing\LicenseController::class, 'storeIndividual'])->name('licenses.store-individual');
    Route::get('/licenses/{license}', [\App\Http\Controllers\Licensing\LicenseController::class, 'show'])->name('licenses.show');
    Route::get('/licenses/{license}/edit', [\App\Http\Controllers\Licensing\LicenseController::class, 'edit'])->name('licenses.edit');
    Route::put('/licenses/{license}', [\App\Http\Controllers\Licensing\LicenseController::class, 'update'])->name('licenses.update');
    Route::post('/licenses/{license}/approve', [\App\Http\Controllers\Licensing\LicenseController::class, 'approve'])->name('licenses.approve');
    Route::post('/licenses/{license}/reject', [\App\Http\Controllers\Licensing\LicenseController::class, 'reject'])->name('licenses.reject');


    // Licensing Officer: Laporan & Notifikasi
    Route::get('/laporan', [\App\Http\Controllers\Licensing\LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/notifikasi', [\App\Http\Controllers\Licensing\NotifikasiController::class, 'index'])->name('notifikasi.index');

    // Memorization Department Routes
    Route::get('/memorization/licenses', [\App\Http\Controllers\Licensing\MemorizationController::class, 'index'])->name('memorization.index');
    Route::post('/memorization/licenses/{license}/update-check', [\App\Http\Controllers\Licensing\MemorizationController::class, 'update'])->name('memorization.update');

});