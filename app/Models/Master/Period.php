<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = ['id'];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }
}
