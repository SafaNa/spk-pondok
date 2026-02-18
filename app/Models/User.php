<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Master\Department;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'department_id',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    /**
     * Check if user is an administrator.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is a licensing officer.
     */
    public function isLicensingOfficer(): bool
    {
        return $this->role === 'licensing_officer';
    }

    /**
     * Check if user is a department officer.
     */
    public function isDepartmentOfficer(): bool
    {
        return $this->role === 'department_officer';
    }

    /**
     * Check if user is a finance officer.
     */
    public function isFinanceOfficer(): bool
    {
        return $this->role === 'finance_officer';
    }
    /**
     * Check if user is a finance secretary.
     */
    public function isFinanceSecretary(): bool
    {
        return $this->role === 'finance_secretary';
    }

    /**
     * Check if user is a memorization department officer.
     */
    public function isMemorizationOfficer(): bool
    {
        return $this->role === 'department_officer' && $this->department?->acronym === 'PENGAJIAN';
    }
}
