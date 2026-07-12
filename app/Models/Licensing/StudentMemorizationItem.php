<?php

namespace App\Models\Licensing;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Models\Master\MemorizationType;

class StudentMemorizationItem extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = ['id'];

    protected $casts = [
        'is_checked' => 'boolean',
    ];

    public function memorization()
    {
        return $this->belongsTo(StudentMemorization::class, 'student_memorization_id');
    }

    public function memorizationType()
    {
        return $this->belongsTo(MemorizationType::class);
    }
}
