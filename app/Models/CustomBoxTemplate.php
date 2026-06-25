<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomBoxTemplate extends Model
{
    use HasFactory;

    protected $table = 'custom_box_templates';

    protected $fillable = [
        'name_en',
        'name_ar',
        'description_en',
        'description_ar',
        'color_code',
        'image',
        'price',
        'is_active',
    ];

    public function getLocalizedNameAttribute()
    {
        return app()->getLocale() == 'en' ? $this->name_en : $this->name_ar;
    }

    public function getLocalizedDescriptionAttribute()
    {
        return app()->getLocale() == 'en' ? $this->description_en : $this->description_ar;
    }
}
