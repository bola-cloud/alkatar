<?php

namespace App\Models\Front;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnerRequest extends Model
{
    use HasFactory;

    protected $table = 'partner_requests';

    protected $fillable = [
        'name',
        'company',
        'phone',
        'email',
        'message',
        'status',
    ];
}
