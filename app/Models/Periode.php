<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Periode extends Model
{
    use HasUuids;

    protected $fillable = ['nama', 'is_active', 'keterangan'];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
