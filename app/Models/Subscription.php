<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','slug','period_type','period_value','price','discount_percent','max_discount_amount','free_shipping','tax_exempt','description','is_active'
    ];

    protected $casts = [
        'free_shipping' => 'boolean',
        'tax_exempt' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function users()
    {
        return $this->hasMany(UserSubscription::class);
    }
}
