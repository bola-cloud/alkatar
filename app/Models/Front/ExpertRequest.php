<?php

namespace App\Models\Front;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpertRequest extends Model
{
    use HasFactory;

    protected $table = 'expert_requests';

    protected $fillable = [
        'name',
        'company',
        'email',
        'phone',
        'message',
        'status',
    ];
}
