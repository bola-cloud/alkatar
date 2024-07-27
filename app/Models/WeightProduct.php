<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\Product;

class WeightProduct extends Model
{
    use HasFactory;

    protected $table = 'weight_product';

    protected $fillable = [
        'Product_Id',
        'price',
        'weight',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
