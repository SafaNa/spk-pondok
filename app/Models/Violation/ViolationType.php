<?php

namespace App\Models\Violation;

use App\Models\Master\Department;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ViolationType extends Model
{
    use HasUuids;

    protected $guarded = ['id'];

    /**
     * Sumber tata tertib:
     *   'pesantren' = Tata Tertib Pondok Pesantren P2AL II
     *   'madrasah'  = Tata Tertib Madrasah Diniah Annuqayah Latee II (MADAL)
     */
    const RULESET_PESANTREN = 'pesantren';
    const RULESET_MADRASAH  = 'madrasah';

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    /** Hanya pelanggaran tata tertib Pondok Pesantren */
    public function scopePesantren($query)
    {
        return $query->where('ruleset', self::RULESET_PESANTREN);
    }

    /** Hanya pelanggaran tata tertib Madrasah Diniah (MADAL) */
    public function scopeMadrasah($query)
    {
        return $query->where('ruleset', self::RULESET_MADRASAH);
    }

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

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
