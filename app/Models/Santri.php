<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Santri extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'santri';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = ['id'];
    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function penilaian()
    {
        return $this->hasMany(Penilaian::class);
    }

    /**
     * Relasi ke RiwayatPelanggaran
     */
    public function riwayatPelanggaran()
    {
        return $this->hasMany(\App\Models\RiwayatPelanggaran::class);
    }

    // Add this method to calculate the final score
    public function hitungNilaiAkhir()
    {
        $totalBobot = Kriteria::sum('bobot');
        $nilaiAkhir = 0;

        foreach ($this->penilaian as $penilaian) {
            $kriteria = $penilaian->kriteria;
            $bobotTernormalisasi = $kriteria->bobot / $totalBobot;

            if ($kriteria->jenis == 'benefit') {
                $max = $kriteria->subkriteria->max('nilai');
                $utility = $penilaian->nilai / max($max, 1); // Avoid division by zero
            } else {
                $min = $kriteria->subkriteria->min('nilai');
                $utility = $min / max($penilaian->nilai, 1); // Avoid division by zero
            }

            $nilaiAkhir += $utility * $bobotTernormalisasi;
        }

        $this->update(['nilai_akhir' => $nilaiAkhir]);
        return $nilaiAkhir;
    }

    /**
     * Check if santri has pending sanctions
     */
    public function hasPendingSanctions($periodeId = null)
    {
        $query = $this->riwayatPelanggaran()->where('status_sanksi', 'belum_selesai');

        if ($periodeId) {
            $query->where('periode_id', $periodeId);
        }

        return $query->exists();
    }

    /**
     * Get SPP score for SAW calculation
     * Lunas = 1.0, Belum Lunas = 0.5, Menunggak = 0.0
     */
    public function getSppScore()
    {
        $scores = [
            'lunas' => 1.0,
            'belum_lunas' => 0.5,
            'menunggak' => 0.0,
        ];

        return $scores[$this->status_spp] ?? 0;
    }

    /**
     * Get Hafalan score for SAW calculation
     * Lengkap = 1.0, Belum Lengkap = progress ratio
     */
    public function getHafalanScore()
    {
        if ($this->status_hafalan === 'lengkap') {
            return 1.0;
        }

        if ($this->target_hafalan > 0) {
            return $this->jumlah_hafalan_tercapai / $this->target_hafalan;
        }

        return 0;
    }

    /**
     * Get Violation score for SAW calculation
     * Based on total bobot poin (COST criterion: lower is better)
     */
    public function getViolationScore($periodeId)
    {
        $violations = $this->riwayatPelanggaran()
            ->where('periode_id', $periodeId)
            ->with('jenisPelanggaran.kategoriPelanggaran')
            ->get();

        $totalBobotPoin = $violations->sum(function ($violation) {
            return $violation->jenisPelanggaran->kategoriPelanggaran->bobot_poin ?? 0;
        });

        // COST criterion: Higher violations = Lower score
        // If no violations, score = 1.0 (perfect)
        if ($totalBobotPoin == 0) {
            return 1.0;
        }

        // Normalize: 1 / (1 + bobot poin)
        return 1 / (1 + $totalBobotPoin);
    }
}