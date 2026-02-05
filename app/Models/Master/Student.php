<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Master\Room;
use App\Models\Master\EducationLevel;
use App\Models\Assessment\Assessment;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Village;

class Student extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = ['id'];

    // protected $fillable = [
    //     'district_code',
    //     'village_code',
    //     'address',
    //     'rayon_id',
    //     'room_id',
    // ];

    protected $casts = [
        'birth_date' => 'date',
        'entry_date' => 'date',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function formalEducation()
    {
        return $this->belongsTo(EducationLevel::class, 'formal_education_level_id');
    }

    public function religiousEducation()
    {
        return $this->belongsTo(EducationLevel::class, 'religious_education_level_id');
    }

    public function assessments()
    {
        return $this->hasMany(Assessment::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_code', 'code');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_code', 'code');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district_code', 'code');
    }

    public function village()
    {
        return $this->belongsTo(Village::class, 'village_code', 'code');
    }

    public function rayon()
    {
        return $this->belongsTo(Rayon::class);
    }

    public function licenses()
    {
        return $this->hasMany(\App\Models\Licensing\StudentLicense::class);
    }
}
