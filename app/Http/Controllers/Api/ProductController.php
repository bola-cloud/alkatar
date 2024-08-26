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
        if ($request->filled('sub_category')) {
            $categoryId = $request->get('sub_category');
            $query->where('subcategory_id', $categoryId);
        }
        $all_products = $query->latest()->paginate($request->get('per_page', 10));
        return ProductResource::collection($all_products);
    }
    public function show($id)
    {
        $product = Product::with('brand', 'category', 'colors', 'sizes', 'product_tags')->findOrFail($id);
        return ProductResource::make($product);
    }
    public function productsWithDiscount()
    {
        $products = Product::withDiscount()->get();
        return ProductResource::collection($products);
    }


}
