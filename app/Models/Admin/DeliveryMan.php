<?php

namespace App\Models\Admin;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class DeliveryMan extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'status',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function getAvatarAttribute($value)
    {
        return isset($value) ? asset(IMG_PROFILE_PIC_PATH . $value) : null;
    }

    public function toArray()
    {
        $array = parent::toArray();
        if (isset($array['avatar']) && !empty($array['avatar']) && strpos($array['avatar'], 'http') !== 0) {
            $array['avatar'] = asset(IMG_PROFILE_PIC_PATH . $array['avatar']);
        }
        return $array;
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'delivery_man_id');
    }
}
