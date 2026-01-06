<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Departemen extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'departemen';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = ['id'];

    /**
     * Relasi ke JenisPelanggaran
     * Satu departemen memiliki banyak jenis pelanggaran
     */
    public function jenisPelanggaran()
    {
        return $this->hasMany(JenisPelanggaran::class);
    }

    /**
     * Relasi ke User
     * Satu departemen memiliki banyak pengurus
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
