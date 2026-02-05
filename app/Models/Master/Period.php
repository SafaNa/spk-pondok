<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Master\AcademicYear;
use App\Models\Assessment\Assessment;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Period extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = ['id'];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function assessments()
    {
        return $this->hasMany(Assessment::class);
    }
}
