<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentModel extends Model
{
    use HasFactory;

    protected $table = "payments";

    protected $fillable = [
        "session_id",
        "user_id",
        "admin_id",
        "amount",
        "status",
        "order_number"
    ];
}
