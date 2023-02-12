<?php

namespace App\Models;

use Epigra\TrGeoZones\Models\City;
use Epigra\TrGeoZones\Models\CityDistrict;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'model_class',
        'model_id',
        'city',
        'district',
        'neighbourhood',
    ];

    public function city()
    {
        return $this->hasOne(City::class, 'id', 'city')->select(['id', 'name']);
    }

    public function neighbourhood()
    {
        return $this->hasOne(CityDistrict::class, 'id', 'neighbourhood')->select(['id', 'mahalle as name']);
    }
}
