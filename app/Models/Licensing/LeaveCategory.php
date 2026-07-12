<?php

namespace App\Models\Licensing;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class LeaveCategory extends Model
{
    use HasUuids;

    protected $fillable = ['name', 'max_duration', 'is_fixed_duration', 'duration_days', 'notes', 'order'];

    protected $casts = [
        'is_fixed_duration' => 'boolean',
    ];

    public function reasons()
    {
        return $this->hasMany(LeaveReason::class)->orderBy('order');
    }
}
