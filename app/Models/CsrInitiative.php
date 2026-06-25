<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CsrInitiative extends Model
{
    use HasFactory;

    protected $table = 'csr_initiatives';

    protected $fillable = [
        'title_en',
        'title_ar',
        'description_en',
        'description_ar',
        'image',
        'pdf_file',
        'video_url',
        'type',
    ];

    /**
     * Get localized title.
     */
    public function getLocalizedTitleAttribute()
    {
        return app()->getLocale() == 'en' ? $this->title_en : $this->title_ar;
    }

    /**
     * Get localized description.
     */
    public function getLocalizedDescriptionAttribute()
    {
        return app()->getLocale() == 'en' ? $this->description_en : $this->description_ar;
    }
}
