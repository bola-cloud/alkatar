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
}
