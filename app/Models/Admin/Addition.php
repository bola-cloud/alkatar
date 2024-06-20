<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Addition extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'name_ar', 'price', 'product_id', 'icon'];


    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
