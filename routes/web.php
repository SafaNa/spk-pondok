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

    // Dashboard V2 Route (Dynamic)
    Route::get('/v2', [\App\Http\Controllers\DashboardController::class, 'indexV2'])->name('dashboard-v2');

    // Santri V2 Route (Dynamic)
    Route::get('/v2/santri', [\App\Http\Controllers\SantriController::class, 'indexV2'])->name('santri-v2');

    // Kriteria V2 Route (Dynamic)
    Route::get('/v2/kriteria', [\App\Http\Controllers\KriteriaController::class, 'indexV2'])->name('kriteria-v2');

    // Kriteria Form V2 Route (Dynamic)
    Route::get('/v2/kriteria/create', [\App\Http\Controllers\KriteriaController::class, 'createV2'])->name('kriteria-form-v2');

    // Kriteria Edit V2 Route (Dynamic)
    Route::get('/v2/kriteria/{id}/edit', [\App\Http\Controllers\KriteriaController::class, 'editV2'])->name('kriteria-edit-v2');

    // Periode V2 Routes (Dynamic)
    Route::get('/v2/periode', [\App\Http\Controllers\PeriodeController::class, 'indexV2'])->name('periode-v2');
    Route::post('/v2/periode', [\App\Http\Controllers\PeriodeController::class, 'store'])->name('periode-v2.store');
    Route::put('/v2/periode/{periode}', [\App\Http\Controllers\PeriodeController::class, 'update'])->name('periode-v2.update');
    Route::delete('/v2/periode/{periode}', [\App\Http\Controllers\PeriodeController::class, 'destroy'])->name('periode-v2.destroy');
    Route::patch('/v2/periode/{periode}/activate', [\App\Http\Controllers\PeriodeController::class, 'activate'])->name('periode-v2.activate');

    // Penilaian V2 Route (Dynamic)
    Route::get('/v2/penilaian', [\App\Http\Controllers\PenilaianController::class, 'indexV2'])->name('penilaian-v2');

    // Penilaian Form V2 Route (Static)
    Route::get('/v2/penilaian/create', function () {
        return view('penilaian-form-v2');
    })->name('penilaian-form-v2');

    // Rekomendasi V2 Route (Dynamic)
    Route::get('/v2/rekomendasi', [\App\Http\Controllers\PerhitunganController::class, 'rekomendasiV2'])->name('rekomendasi-v2');

    // Rekomendasi Detail V2 Route (Dynamic)
    Route::get('/v2/rekomendasi/{id}', [\App\Http\Controllers\PerhitunganController::class, 'hasilV2'])->name('rekomendasi-detail-v2');

    // Riwayat V2 Route (Dynamic)
    Route::get('/v2/riwayat', [\App\Http\Controllers\PerhitunganController::class, 'historyV2'])->name('riwayat-v2');

    // Sensitivitas V2 Route (Dynamic)
    Route::get('/v2/sensitivitas', [\App\Http\Controllers\PerhitunganController::class, 'sensitivitasV2'])->name('sensitivitas-v2');

    // Login V2 Route (Static)
    Route::get('/v2/login', function () {
        return view('login-v2');
    })->name('login-v2');

    // Ganti Password V2 Route (Static)
    Route::get('/v2/ganti-password', function () {
        return view('ganti-password-v2');
    })->name('ganti-password-v2');
});