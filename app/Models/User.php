<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Master\Department;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'photo',
        'password',
        'type',
        'department_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'type'              => 'integer',
        ];
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function isAdmin(): bool
    {
        return $this->type === 0;
    }

    public function isDepartmentOfficer(): bool
    {
        return $this->type === 1;
    }

    public function isLicensingOfficer(): bool
    {
        return $this->type === 1 && $this->department?->acronym === 'PERIZINAN';
    }

    public function isMemorizationOfficer(): bool
    {
        return $this->type === 1 && $this->department?->acronym === 'KAMTIB';
    }

    public function isSecurityOfficer(): bool
    {
        return $this->type === 1 && $this->department?->acronym === 'KAMTIB';
    }

    public function canManageViolations(): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        // Departemen selain Perizinan bisa menginput pelanggaran
        return $this->isDepartmentOfficer() && !in_array($this->department?->acronym, ['PERIZINAN']);
    }
}
