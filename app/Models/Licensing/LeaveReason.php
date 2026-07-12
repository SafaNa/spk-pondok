<?php

namespace App\Models\Licensing;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class LeaveReason extends Model
{
    use HasUuids;

    protected $fillable = ['leave_category_id', 'reason', 'can_skip_validation', 'order'];

    protected $casts = [
        'can_skip_validation' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(LeaveCategory::class, 'leave_category_id');
    }
}
