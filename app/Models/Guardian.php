<?php

namespace App\Models;

use App\Models\Master\Student;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Guardian extends Authenticatable
{
    use HasUuids, Notifiable;

    protected $table = 'guardians';

    protected $fillable = [
        'name', 'username', 'password',
        'phone', 'email', 'nik', 'address', 'job', 'relationship', 'avatar',
    ];

    protected $hidden = ['password'];

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_guardian');
    }
}
