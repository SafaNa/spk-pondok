<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Models\Master\Student;
use App\Models\Master\Rayon;

class Room extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = ['id'];

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function rayon()
    {
        return $this->belongsTo(Rayon::class);
    }
}
