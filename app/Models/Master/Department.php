<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Department extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = ['id'];

    // Accessors for Indonesian View Compatibility
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
