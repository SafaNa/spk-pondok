<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subkriteria extends Model
{
    use HasFactory;

    protected $table = 'subkriteria';
    protected $guarded = ['id'];

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class);
    }

    public function penilaian()
    {
        return $this->hasMany(Penilaian::class);
    }
}