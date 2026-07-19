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

    /**
     * Kecamatan yang wajib datang langsung ke pondok untuk perpanjangan.
     * (case-insensitive match terhadap nama district di DB)
     */
    public const MANDATORY_DISTRICTS = [
        'guluk-guluk',
        'ganding',
        'pragaan',
        'lenteng',
        'bluto',
    ];

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

    public function extensions()
    {
        return $this->hasMany(LicenseExtension::class)->orderBy('requested_at', 'desc');
    }

    /**
     * Extension dengan status pending (hanya boleh 1 aktif sekaligus).
     */
    public function getActiveExtensionAttribute(): ?LicenseExtension
    {
        return $this->extensions->where('status', 'pending')->first();
    }

    /**
     * Cek apakah santri dari kecamatan yang wajib datang langsung ke pondok.
     */
    public function requiresInPersonExtension(): bool
    {
        $districtName = strtolower(trim(
            $this->student?->district?->name ?? ''
        ));

        if (!$districtName) return false;

        foreach (self::MANDATORY_DISTRICTS as $mandatory) {
            if (str_contains($districtName, $mandatory)) {
                return true;
            }
        }

        return false;
    }
}
