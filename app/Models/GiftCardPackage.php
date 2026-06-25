<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiftCardPackage extends Model
{
    use HasFactory;

    protected $table = 'gift_card_packages';

    protected $fillable = [
        'key',
        'name_ar',
        'name_en',
        'description_ar',
        'description_en',
        'price',
        'status',
    ];

    /**
     * Get localized name.
     */
    public function getLocalizedNameAttribute()
    {
        return app()->getLocale() == 'en' ? $this->name_en : $this->name_ar;
    }

    /**
     * Get localized description.
     */
    public function getLocalizedDescriptionAttribute()
    {
        return app()->getLocale() == 'en' ? $this->description_en : $this->description_ar;
    }
}
