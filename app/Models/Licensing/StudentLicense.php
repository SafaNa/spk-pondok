<?php

namespace App\Models\Licensing;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Models\Master\Student;

class StudentLicense extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = ['id'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'memorization_check' => 'boolean',
    ];

    // event() relationship removed

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
