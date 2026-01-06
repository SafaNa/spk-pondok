<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class JenisPelanggaran extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'jenis_pelanggaran';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = ['id'];

    /**
     * Relasi ke Departemen
     * Setiap jenis pelanggaran dimiliki oleh satu departemen
     */
    public function departemen()
    {
        return $this->belongsTo(Departemen::class);
    }

    /**
     * Relasi ke KategoriPelanggaran
     * Setiap jenis pelanggaran memiliki satu kategori
     */
    public function kategoriPelanggaran()
    {
        return $this->belongsTo(KategoriPelanggaran::class);
    }

    /**
     * Relasi ke RiwayatPelanggaran
     * Satu jenis pelanggaran dapat dicatat berkali-kali
     */
    public function riwayatPelanggaran()
    {
        return $this->hasMany(RiwayatPelanggaran::class);
    }

    /**
     * Scope untuk filter jenis pelanggaran aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk filter berdasarkan departemen
     */
    public function scopeByDepartemen($query, $departemenId)
    {
        return $query->where('departemen_id', $departemenId);
    }
}
