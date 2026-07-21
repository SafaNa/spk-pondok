<?php

namespace App\Models\Licensing;

use App\Models\Guardian;
use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class LicenseExtension extends Model
{
    use HasUuids;

    protected $guarded = ['id'];

    protected $casts = [
        'requested_new_end_date' => 'date',
        'requested_at'           => 'datetime',
        'approved_at'            => 'datetime',
        'rejected_at'            => 'datetime',
    ];

    public function studentLicense()
    {
        return $this->belongsTo(StudentLicense::class);
    }

    public function createdBy()
    {
        return $this->morphTo('created_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    // Helpers
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending'  => 'Menunggu',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            default    => ucfirst($this->status),
        };
    }

    public function getCreatorNameAttribute(): string
    {
        if ($this->created_by_type === Guardian::class) {
            return $this->createdBy?->name ?? 'Wali';
        }
        if ($this->created_by_type === User::class) {
            return $this->createdBy?->name ?? 'Pengurus';
        }
        return '-';
    }
}
