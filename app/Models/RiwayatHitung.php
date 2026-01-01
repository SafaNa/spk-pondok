<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class RiwayatHitung extends Model
{
    use HasUuids;

    protected $table = 'riwayat_hitung';

    protected $fillable = [
        'santri_id',
        'periode_id',
        'nilai_akhir'
    ];

    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }

    public function periode()
    {
        return $this->belongsTo(Periode::class);
    }
}
