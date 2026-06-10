<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $fillable = ['name_en', 'name_ar', 'state_id'];

    public function areas()
    {
        return $this->hasMany(Area::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
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
