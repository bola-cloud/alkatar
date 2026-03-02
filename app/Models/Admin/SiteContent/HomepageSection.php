<?php

namespace App\Models\Admin\SiteContent;

use Illuminate\Database\Eloquent\Model;

class HomepageSection extends Model
{
    protected $table = 'homepage_sections';

    protected $fillable = [
        'section_key', 'content_en', 'content_fr', 'image', 'display_order', 'status'
    ];

    protected $casts = [
        'content_en' => 'array',
        'content_fr' => 'array',
        'status' => 'boolean'
    ];
}
