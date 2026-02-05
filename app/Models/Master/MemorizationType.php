<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class MemorizationType extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = ['id'];
}
