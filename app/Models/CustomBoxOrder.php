<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\Order;

class CustomBoxOrder extends Model
{
    use HasFactory;

    protected $table = 'custom_box_orders';

    protected $fillable = [
        'order_id',
        'template_name',
        'capacity',
        'print_name',
        'gift_message',
        'details',
        'price',
        'prep_status',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
