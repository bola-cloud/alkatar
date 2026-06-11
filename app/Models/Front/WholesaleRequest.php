<?php

namespace App\Models\Front;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WholesaleRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'contact_name',
        'contact_phone',
        'estimated_qty',
        'services',
        'notes',
        'status',
    ];

    protected $casts = [
        'services' => 'array',
    ];
}
