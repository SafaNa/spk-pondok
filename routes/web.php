<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;

// Master Data Controllers
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

// Guardian Controllers
use App\Http\Controllers\Guardian\AuthController as GuardianAuthController;
use App\Http\Controllers\Guardian\DashboardController as GuardianDashboardController;
use App\Http\Controllers\Guardian\LicenseController as GuardianLicenseController;
use App\Http\Controllers\Guardian\ProfileController as GuardianProfileController;

// Finance & Violation Controllers
use App\Http\Controllers\Finance\SppPaymentController;
use App\Http\Controllers\Violation\ViolationController;
use App\Http\Controllers\Violation\ViolationTypeController;
use App\Http\Controllers\Violation\ViolationCategoryController;

// Licensing Controllers
use App\Http\Controllers\Licensing\LicenseController;
use App\Http\Controllers\Licensing\LaporanController;
use App\Http\Controllers\Licensing\NotifikasiController;
use App\Http\Controllers\Licensing\MemorizationController;
use App\Http\Controllers\Licensing\LeaveCategoryController;
use App\Models\Licensing\StudentMemorizationItem;

// Landing page — pilih role (admin / wali santri)
Route::get('/', function () {
    if (Auth::check()) return redirect()->route('admin.dashboard');
    if (Auth::guard('guardian')->check()) return redirect()->route('guardian.dashboard');
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
        Route::get('/login', [GuardianAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [GuardianAuthController::class, 'login'])->name('login.post');
    });
    Route::middleware('auth:guardian')->group(function () {
        Route::post('/logout', [GuardianAuthController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [GuardianDashboardController::class, 'index'])->name('dashboard');
        Route::get('/licenses', [GuardianLicenseController::class, 'index'])->name('licenses.index');
        Route::get('/licenses/create', [GuardianLicenseController::class, 'create'])->name('licenses.create');
        Route::post('/licenses', [GuardianLicenseController::class, 'store'])->name('licenses.store');
        Route::get('/profile', [GuardianProfileController::class, 'show'])->name('profile');
        Route::put('/profile', [GuardianProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [GuardianProfileController::class, 'updatePassword'])->name('profile.password');
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
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

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

    // User Management
    Route::resource('users', UserController::class);

    // Finance / Keuangan
    Route::resource('spp-payments', SppPaymentController::class);

    // Theme
    Route::get('/set-theme/{theme}', function ($theme) {
        if (in_array($theme, ['default', 'blue', 'purple'])) {
            session(['theme' => $theme]);
        }
        return back();
    })->name('theme.set');

    // Violation Routes
    Route::get('/violations/history/{student}', [ViolationController::class, 'history'])->name('violations.history');
    Route::post('/violations/{id}/verify-sanction', [ViolationController::class, 'verifySanction'])->name('violations.verify-sanction');
    Route::resource('violations', ViolationController::class);

    Route::resource('violation-types', ViolationTypeController::class);
    Route::resource('violation-categories', ViolationCategoryController::class);

    // Licensing Routes
    Route::get('/licenses', [LicenseController::class, 'index'])->name('licenses.index');

    // Individual License
    Route::get('/licenses/create', [LicenseController::class, 'create'])->name('licenses.create');
    Route::post('/licenses/store-individual', [LicenseController::class, 'storeIndividual'])->name('licenses.store-individual');
    Route::get('/licenses/{license}', [LicenseController::class, 'show'])->name('licenses.show');
    Route::get('/licenses/{license}/edit', [LicenseController::class, 'edit'])->name('licenses.edit');
    Route::put('/licenses/{license}', [LicenseController::class, 'update'])->name('licenses.update');
    Route::post('/licenses/{license}/approve', [LicenseController::class, 'approve'])->name('licenses.approve');
    Route::post('/licenses/{license}/force-approve', [LicenseController::class, 'forceApprove'])->name('licenses.force-approve');
    Route::post('/licenses/{license}/reject', [LicenseController::class, 'reject'])->name('licenses.reject');
    Route::delete('/licenses/{license}', [LicenseController::class, 'destroy'])->name('licenses.destroy');

    // Leave Categories (Master Data)
    Route::get('/leave-categories/{leaveCategory}/reasons', [LeaveCategoryController::class, 'reasons'])->name('leave-categories.reasons');
    Route::resource('leave-categories', LeaveCategoryController::class);

    // Licensing Officer: Laporan & Notifikasi
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');

    // Memorization Department Routes
    Route::resource('memorization', MemorizationController::class);
    Route::post('memorization-items/{item}/toggle', [MemorizationController::class, 'toggleItem'])->name('memorization-items.toggle');

});