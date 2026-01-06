<?php

use App\Http\Controllers\SantriController;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\SubkriteriaController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\PerhitunganController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;

// Auth Routes
// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Change Password Routes
Route::middleware('auth')->group(function () {
    Route::get('/change-password', [AuthController::class, 'showChangePasswordForm'])->name('password.change');
    Route::put('/change-password', [AuthController::class, 'changePassword'])->name('password.update');
});

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('home');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Santri Routes
    Route::get('santri/template', [SantriController::class, 'downloadTemplate'])->name('santri.template');
    Route::get('santri/export', [SantriController::class, 'export'])->name('santri.export');
    // Santri Routes
    Route::post('/santri/import', [SantriController::class, 'import'])->name('santri.import');
    Route::resource('santri', SantriController::class);

    // Kriteria Routes
    Route::resource('kriteria', KriteriaController::class);

    // Subkriteria Routes
    Route::prefix('kriteria/{kriteria}')->group(function () {
        Route::get('/subkriteria', [SubkriteriaController::class, 'index'])->name('kriteria.subkriteria.index');
        Route::get('/subkriteria/create', [SubkriteriaController::class, 'create'])->name('kriteria.subkriteria.create');
        Route::post('/subkriteria', [SubkriteriaController::class, 'store'])->name('kriteria.subkriteria.store');
        Route::get('/subkriteria/{subkriteria}', [SubkriteriaController::class, 'show'])->name('kriteria.subkriteria.show');
        Route::get('/subkriteria/{subkriteria}/edit', [SubkriteriaController::class, 'edit'])->name('kriteria.subkriteria.edit');
        Route::put('/subkriteria/{subkriteria}', [SubkriteriaController::class, 'update'])->name('kriteria.subkriteria.update');
        Route::delete('/subkriteria/{subkriteria}', [SubkriteriaController::class, 'destroy'])->name('kriteria.subkriteria.destroy');
    });

    // Penilaian Routes
    Route::resource('penilaian', PenilaianController::class);

    // Perhitungan Routes (Admin & Pengurus Perizinan)
    Route::middleware(['role:admin,pengurus_perizinan'])->prefix('perhitungan')->group(function () {
        Route::get('/', [PerhitunganController::class, 'index'])->name('perhitungan.index');
        Route::post('/hitung', [PerhitunganController::class, 'hitung'])->name('perhitungan.hitung');
        Route::delete('/delete/{santri}', [PerhitunganController::class, 'destroy'])->name('perhitungan.destroy');
        Route::get('/hasil/{santri}', [PerhitunganController::class, 'hasil'])->name('perhitungan.hasil');
        Route::get('/rekomendasi', [PerhitunganController::class, 'rekomendasi'])->name('perhitungan.rekomendasi');
        Route::get('/history', [PerhitunganController::class, 'history'])->name('perhitungan.history');
        Route::get('/cetak', [PerhitunganController::class, 'cetak'])->name('perhitungan.cetak');
        Route::post('/recalculate-batch', [PerhitunganController::class, 'recalculateBatch'])->name('perhitungan.recalculateBatch');
    });

    // Period Management Routes
    Route::post('/periode/{periode}/activate', [App\Http\Controllers\PeriodeController::class, 'activate'])->name('periode.activate');
    Route::resource('periode', App\Http\Controllers\PeriodeController::class);

    // Department Management Routes (Admin only)
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('departemen', App\Http\Controllers\DepartemenController::class)->parameters(['departemen' => 'departemen']);

        // These nested routes might be deprecated if we move completely to top-level, but keeping for now
        Route::prefix('departemen/{departemen}')->name('departemen.')->group(function () {
            Route::resource('jenis-pelanggaran', App\Http\Controllers\JenisPelanggaranController::class);
        });
    });

    // Master Data Pelanggaran Routes (Admin & Pengurus)
    Route::middleware(['role:admin,pengurus_departemen'])->group(function () {
        Route::resource('kategori-pelanggaran', App\Http\Controllers\KategoriPelanggaranController::class);
        Route::resource('jenis-pelanggaran', App\Http\Controllers\JenisPelanggaranController::class);
    });

    // Violation Management Routes (Admin, Pengurus Departemen & Pengurus Perizinan)
    Route::middleware(['role:admin,pengurus_departemen,pengurus_perizinan'])->prefix('pelanggaran')->group(function () {
        Route::get('/', [App\Http\Controllers\PelanggaranController::class, 'index'])->name('pelanggaran.index');
        Route::get('/create', [App\Http\Controllers\PelanggaranController::class, 'create'])->name('pelanggaran.create');
        Route::post('/', [App\Http\Controllers\PelanggaranController::class, 'store'])->name('pelanggaran.store');

        // Specific ID permissions handled in Controller
        Route::get('/{id}/edit', [App\Http\Controllers\PelanggaranController::class, 'edit'])->name('pelanggaran.edit');
        Route::put('/{id}', [App\Http\Controllers\PelanggaranController::class, 'update'])->name('pelanggaran.update');
        Route::delete('/{id}', [App\Http\Controllers\PelanggaranController::class, 'destroy'])->name('pelanggaran.destroy');

        Route::get('/{id}', [App\Http\Controllers\PelanggaranController::class, 'show'])->name('pelanggaran.show');
        Route::post('/{id}/verify', [App\Http\Controllers\PelanggaranController::class, 'verifySanksi'])->name('pelanggaran.verify');
    });

    // Santri Violation History (accessible by all authenticated users)
    Route::get('/santri/{santri}/riwayat-pelanggaran', [App\Http\Controllers\PelanggaranController::class, 'riwayat'])
        ->name('santri.riwayat-pelanggaran');

    // Sensitivity Analysis
    Route::get('/sensitivity', [App\Http\Controllers\SensitivityController::class, 'index'])->name('sensitivity.index');
    Route::post('/sensitivity/analyze', [App\Http\Controllers\SensitivityController::class, 'analyze'])->name('sensitivity.analyze');

    // Theme Switcher Route
    Route::get('/set-theme/{theme}', function ($theme) {
        if (in_array($theme, ['default', 'blue', 'purple'])) {
            session(['theme' => $theme]);
        }
        return back();
    })->name('theme.set');


});