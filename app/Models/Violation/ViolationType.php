<?php

namespace App\Models\Violation;

use App\Models\Master\Department;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ViolationType extends Model
{
    use HasUuids;

    protected $guarded = ['id'];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function category()
    {
        return $this->belongsTo(ViolationCategory::class, 'violation_category_id');
    }

    public function records()
    {
        return $this->hasMany(ViolationRecord::class);
    }
}
