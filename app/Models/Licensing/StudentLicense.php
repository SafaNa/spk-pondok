<?php

namespace App\Models\Licensing;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Models\Master\Student;
use App\Models\Master\AcademicYear;

class StudentLicense extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = ['id'];

    protected $casts = [
        'start_date'         => 'date',
        'end_date'           => 'date',
        'actual_return_date' => 'date',
        'is_emergency'       => 'boolean',
        'submitted_at'       => 'datetime',
        'approved_at'        => 'datetime',
        'rejected_at'        => 'datetime',
    ];

    public function getIsLateAttribute()
    {
        if ($this->status !== 'approved') return false;
        if (!$this->actual_return_date) {
            return now()->startOfDay()->gt($this->end_date);
        }
        return $this->actual_return_date->startOfDay()->gt($this->end_date);
    }

    public function getLateDaysAttribute()
    {
        if (!$this->is_late) return 0;
        $compareDate = $this->actual_return_date ? $this->actual_return_date->startOfDay() : now()->startOfDay();
        return $compareDate->diffInDays($this->end_date);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function leaveCategory()
    {
        return $this->belongsTo(LeaveCategory::class);
    }

    public function leaveReason()
    {
        return $this->belongsTo(LeaveReason::class);
    }

    public function creator()
    {
        return $this->morphTo();
    }
}
