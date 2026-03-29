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
        $query = Product::with([
            'brand',
            'category',
            'colors',
            'sizes',
            'weights',
            'additions' => function ($query) {
                $query->where('status', 1);
            },
            'product_tags'
        ]);
        if ($request->filled('category')) {
            $categoryId = $request->get('category');
            $query->where('Category_Id', $categoryId);
        }
        if ($request->filled('sub_category')) {
            $categoryId = $request->get('sub_category');
            $query->where('subcategory_id', $categoryId);
        }
        $all_products = $query->where('Status', 1)->where('Quantity', '>', 0)->orderBy('fr_Product_Name', 'asc')->paginate($request->get('per_page', 10));
        return ProductResource::collection($all_products);
    }

    public function show($id)
    {
        $product = Product::where('Status', 1)->with([
            'brand',
            'category',
            'colors',
            'sizes',
            'weights',
            'additions' => function ($query) {
                $query->where('status', 1);
            },
            'product_tags'
        ])->where('Quantity', '>', 0)->findOrFail($id);
        return ProductResource::make($product);
    }
    public function productsWithDiscount()
    {
        $products = Product::where('Status', 1)->where('Quantity', '>', 0)->withDiscount()->with([
            'brand',
            'category',
            'colors',
            'sizes',
            'weights',
            'additions' => function ($query) {
                $query->where('status', 1);
            },
            'product_tags'
        ])->get();
        return ProductResource::collection($products);
    }


}
