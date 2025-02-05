<?php

namespace App\Models;

use App\Models\Admin\Category;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\Product;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'discount_value',
        'name',
        'is_percentage',
        'category_id',
        'sub_category_id',
        'required_product_ids',
        'gift_product_ids',
        'minimum_total',
        'product_id',
        'applies_to',
        'Status',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'required_product_ids' => 'array',
        'gift_product_ids' => 'array',
    ];
    

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subCategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function requiredProducts()
    {
        return $this->belongsToMany(Product::class, 'offer_required_products');
    }


    public function giftProducts()
    {
        return $this->belongsToMany(Product::class, 'offer_gift_products');
    }

    public function getRequiredProductIdsAttribute($value)
    {
        return json_decode($value, true);
    }

    public function getGiftProductIdsAttribute($value)
    {
        return json_decode($value, true);
    }

}
