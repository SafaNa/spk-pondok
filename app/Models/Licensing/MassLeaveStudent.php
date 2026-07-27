<?php

namespace App\Models\Licensing;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Master\Student;
use App\Models\User;

class MassLeaveStudent extends Model
{
    use HasUuids;

    protected $fillable = [
        'mass_leave_id',
        'student_id',
        'checked_out_at',
        'actual_return_date',
        'checked_out_by',
        'notes',
    ];

    protected $casts = [
        'checked_out_at' => 'datetime',
        'actual_return_date' => 'datetime',
    ];

    public function massLeave(): BelongsTo
    {
        return $this->belongsTo(MassLeave::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function checkedOutBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checked_out_by');
    }
}
