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
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
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

    // Perhitungan Routes
    Route::prefix('perhitungan')->group(function () {
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