<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Santri extends Model
{
    use HasFactory;

    protected $table = 'santri';
    protected $guarded = ['id'];
    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function penilaian()
    {
        return $this->hasMany(Penilaian::class);
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
}