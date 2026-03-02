<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Product;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $q = trim($request->get('q', ''));
        if ($q === '') {
            return response()->json([]);
        }

        $products = Product::where(function ($query) use ($q) {
            $query->where('en_Product_Name', 'like', "%{$q}%")
                  ->orWhere('fr_Product_Name', 'like', "%{$q}%")
                  ->orWhere('barcode', $q)
                  ->orWhere('en_Product_Slug', 'like', "%{$q}%");
        })->where('Status', 1)
          ->select('id', 'en_Product_Name', 'en_Product_Slug', 'Primary_Image', 'Price')
          ->limit(10)
          ->get();

        $results = $products->map(function ($p) {
            $slug = $p->en_Product_Slug ?: $p->id;
            $url = route('single.product', $slug);
            $image = $p->Primary_Image ? asset(ProductImage() . $p->Primary_Image) : asset(ProductImage() . 'prod.png');
            return [
                'id' => $p->id,
                'name' => $p->en_Product_Name,
                'slug' => $slug,
                'url' => $url,
                'image' => $image,
                'price' => currencyConverter($p->Price),
            ];
        });

        return response()->json($results);
    }
}
