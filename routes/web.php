<?php

use App\Http\Controllers\SantriController;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\SubkriteriaController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\PerhitunganController;
use Illuminate\Support\Facades\Route;

// Dashboard
Route::get('/', function () {
    $totalSantri = \App\Models\Santri::count();
    $totalKriteria = \App\Models\Kriteria::count();
    $totalPenilaian = \App\Models\Penilaian::count();

    return view('dashboard', compact('totalSantri', 'totalKriteria', 'totalPenilaian'));
})->name('dashboard');

// Santri Routes
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

// Perhitungan SMART

// Perhitungan Routes
Route::prefix('perhitungan')->group(function () {
    Route::get('/', [PerhitunganController::class, 'index'])->name('perhitungan.index');
    Route::post('/hitung', [PerhitunganController::class, 'hitung'])->name('perhitungan.hitung');
    Route::get('/hasil/{santri}', [PerhitunganController::class, 'hasil'])->name('perhitungan.hasil');
    Route::get('/rekomendasi', [PerhitunganController::class, 'rekomendasi'])->name('perhitungan.rekomendasi');
});

// Theme Switcher Route
Route::get('/set-theme/{theme}', function ($theme) {
    if (in_array($theme, ['default', 'blue', 'purple'])) {
        session(['theme' => $theme]);
    }
    return back();
})->name('theme.set');