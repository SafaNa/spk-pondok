<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class KategoriPelanggaran extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'kategori_pelanggaran';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = ['id'];

    /**
     * Relasi ke JenisPelanggaran
     * Satu kategori memiliki banyak jenis pelanggaran
     */
    public function jenisPelanggaran()
    {
        return $this->hasMany(JenisPelanggaran::class);
    }
}
