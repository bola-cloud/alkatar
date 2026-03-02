<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetails extends Model
{
    use HasFactory;
    protected $fillable = [
        'Order_Id',
        'Product_Id',
        'Product_Name',
        'Image',
        'Size',
        'Color',
        'Price',
        'Quantity',
        'Total_Price',
    ];

    public function getImageAttribute($value)
    {
        return isset($value) ? asset(IMG_PRODUCT_PATH . $value) : null;
    }


    public function toArray()
    {
        $array = parent::toArray();
        if (isset($array['Image']) && !empty($array['Image']) && strpos($array['Image'], 'http') !== 0) {
            $array['Image'] = asset(IMG_PRODUCT_PATH . $array['Image']);
        }
        return $array;
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'Product_Id');
    }
}
