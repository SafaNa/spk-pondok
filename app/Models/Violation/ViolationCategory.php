<?php

namespace App\Models\Violation;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ViolationCategory extends Model
{
    use HasUuids;

    protected $guarded = ['id'];

    public function violationTypes()
    {
        return $this->hasMany(ViolationType::class);
    }
}
