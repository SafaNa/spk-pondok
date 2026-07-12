<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Department extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = ['id'];

    /**
     * Tipe yang tersedia:
     *   'department' = departemen resmi struktural
     *   'unit'       = pengurus/unit non-departemen (contoh: Perizinan)
     */
    const TYPE_DEPARTMENT = 'department';
    const TYPE_UNIT        = 'unit';

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    /** Hanya departemen resmi */
    public function scopeDepartments($query)
    {
        return $query->where('type', self::TYPE_DEPARTMENT);
    }

    /** Hanya pengurus/unit non-departemen */
    public function scopeUnits($query)
    {
        return $query->where('type', self::TYPE_UNIT);
    }

    // -------------------------------------------------------------------------
    // Accessors — helper boolean
    // -------------------------------------------------------------------------

    public function getIsDepartmentAttribute(): bool
    {
        return $this->type === self::TYPE_DEPARTMENT;
    }

    public function getIsUnitAttribute(): bool
    {
        return $this->type === self::TYPE_UNIT;
    }

    // -------------------------------------------------------------------------
    // Accessors for Indonesian View Compatibility
    // -------------------------------------------------------------------------

    public function getKodeDepartemenAttribute()
    {
        return $this->code;
    }

    public function getNamaDepartemenAttribute()
    {
        return $this->name;
    }

    public function getSingkatanAttribute()
    {
        return $this->acronym;
    }

    public function getKeteranganAttribute()
    {
        return $this->description;
    }

    // Dummy Accessors for missing Violation features
    public function getJenisPelanggaranAttribute()
    {
        return collect([]);
    }

    public function getJenisPelanggaranCountAttribute()
    {
        return 0;
    }
}
