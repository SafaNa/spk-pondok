<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Kriteria extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'kriteria';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = ['id'];

    public function subkriteria()
    {
        return $this->hasMany(Subkriteria::class);
    }

    public function penilaian()
    {
        return $this->hasMany(Penilaian::class);
    }
}