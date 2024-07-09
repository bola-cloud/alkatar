<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    use HasFactory;
    protected $fillable = [
        'Size', 'Size_ar',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'size_product');
    }
}
