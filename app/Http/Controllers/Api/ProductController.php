<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Admin\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('brand', 'category', 'colors', 'sizes', 'product_tags');
        if ($request->filled('category')) {
            $categoryId = $request->get('category');
            $query->where('Category_Id', $categoryId);
        }

        $all_products = $query->latest()->paginate($request->get('per_page', 10));

        return ProductResource::collection($all_products);
    }
}
