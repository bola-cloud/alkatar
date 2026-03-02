<?php

namespace App\Models;

use App\Models\Admin\Billing;
use App\Models\Admin\Order;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'google_id',
        'facebook_id',
        'image',
        'code',
        'Number',
        'Gender',
        'DOB',
        'About',
        'password',
        'is_admin',
        'status',
        'offer_types'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'offer_types' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($user) {
            $user->orders()->delete();
            $user->payments()->delete();
        });
    }

    public function getImageAttribute($value)
    {
        return isset($value) ? asset(IMG_PROFILE_PIC_PATH . $value) : null;
    }

    public function toArray()
    {
        $array = parent::toArray();
        if (isset($array['image']) && !empty($array['image']) && strpos($array['image'], 'http') !== 0) {
            $array['image'] = asset(IMG_PROFILE_PIC_PATH . $array['image']);
        }
        return $array;
    }

    public function billing()
    {
        return $this->belongsTo(Billing::class, 'User_Id');
    }

    public function payments()
    {
        return $this->hasMany(PaymentModel::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function addresses()
    {
        return $this->hasMany(\App\Models\Address::class, 'user_id');
    }
}
