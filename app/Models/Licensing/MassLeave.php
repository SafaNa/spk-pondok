<?php

namespace App\Models\Licensing;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Master\AcademicYear;
use App\Models\User;

class MassLeave extends Model
{
    use HasUuids;

    protected $fillable = [
        'academic_year_id',
        'title',
        'start_date',
        'end_date',
        'status',
        'created_by',
        'bulk_checkout_at',
        'bulk_checkout_by',
    ];

    protected $casts = [
        'start_date'       => 'date',
        'end_date'         => 'date',
        'bulk_checkout_at' => 'datetime',
    ];

    public function getIsActiveAttribute(): bool
    {
        return $this->status === 'active';
    }

    public static function closeExpiredEvents(): void
    {
        self::where('status', '!=', 'completed')
            ->whereDate('end_date', '<', now()->startOfDay())
            ->update(['status' => 'completed']);
    }

    public function isOngoing(): bool
    {
        if ($this->status === 'completed') {
            return false;
        }
        $hMinusOne = \Carbon\Carbon::parse($this->start_date)->subDay()->startOfDay();
        $endOfDay = \Carbon\Carbon::parse($this->end_date)->endOfDay();
        return now()->betweenIncluded($hMinusOne, $endOfDay);
    }

    public function canBeFinishedManually(): bool
    {
        if ($this->status === 'completed') {
            return false;
        }
        $hDay1800 = \Carbon\Carbon::parse($this->end_date)->setTime(18, 0, 0);
        return now()->gte($hDay1800);
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function students(): HasMany
    {
        return $this->hasMany(MassLeaveStudent::class, 'mass_leave_id');
    }
}
