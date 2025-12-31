<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Penilaian extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'penilaian';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = ['id'];

    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class);
    }

    public function subkriteria()
    {
        return $this->belongsTo(Subkriteria::class);
    }
}