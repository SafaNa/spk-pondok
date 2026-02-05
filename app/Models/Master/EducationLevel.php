<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class EducationLevel extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = ['id'];

    public function studentsFormal()
    {
        return $this->hasMany(Student::class, 'formal_education_level_id');
    }

    public function studentsReligious()
    {
        return $this->hasMany(Student::class, 'religious_education_level_id');
    }
}
