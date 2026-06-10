<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $fillable = ['city_id', 'name_en', 'name_ar'];

    public function city()
    {
        return $this->belongsTo(\App\Models\City::class, 'city_id');
    }

    public function deliveryCharge()
    {
        return $this->hasOne(\App\Models\DeliveryCharge::class, 'area_id');
    }

    public function getNameArAttribute()
    {
        return $this->name_fr;
    }

    public function setNameArAttribute($value)
    {
        $this->attributes['name_fr'] = $value;
    }
}
