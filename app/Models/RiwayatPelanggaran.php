<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class RiwayatPelanggaran extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'riwayat_pelanggaran';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = ['id'];

    protected $casts = [
        'tanggal_kejadian' => 'date',
        'tanggal_input' => 'datetime',
        'tanggal_verifikasi' => 'datetime',
    ];

    /**
     * Relasi ke Santri
     */
    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }

    /**
     * Relasi ke JenisPelanggaran
     */
    public function jenisPelanggaran()
    {
        return $this->belongsTo(JenisPelanggaran::class);
    }

    /**
     * Relasi ke Periode
     */
    public function periode()
    {
        return $this->belongsTo(Periode::class);
    }

    /**
     * Relasi ke User yang membuat record
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relasi ke User yang verifikasi sanksi
     */
    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Scope untuk sanksi yang belum selesai
     */
    public function scopeBelumSelesai($query)
    {
        return $query->where('status_sanksi', 'belum_selesai');
    }

    /**
     * Scope untuk sanksi yang sudah selesai
     */
    public function scopeSudahSelesai($query)
    {
        return $query->where('status_sanksi', 'selesai');
    }

    /**
     * Scope untuk filter berdasarkan departemen
     */
    public function scopeByDepartemen($query, $departemenId)
    {
        return $query->whereHas('jenisPelanggaran', function ($q) use ($departemenId) {
            $q->where('departemen_id', $departemenId);
        });
    }

    /**
     * Scope untuk filter berdasarkan periode
     */
    public function scopeByPeriode($query, $periodeId)
    {
        return $query->where('periode_id', $periodeId);
    }
}
