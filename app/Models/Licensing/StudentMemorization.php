<?php

namespace App\Models\Licensing;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Models\Master\Student;
use App\Models\Master\AcademicYear;

class StudentMemorization extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = ['id'];

    protected $casts = [
        'completed_at' => 'datetime',
        'is_used' => 'boolean',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function items()
    {
        return $this->hasMany(StudentMemorizationItem::class);
    }

    public function checkedItems()
    {
        return $this->hasMany(StudentMemorizationItem::class)->where('is_checked', true);
    }
}
