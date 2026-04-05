<?php

namespace App\Http\Controllers\Frontend;
use Illuminate\Support\Facades\Log;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Category;
use App\Models\Admin\Product;

class CategoryController extends Controller
{
    /**
     * Display products for a given category slug.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $slug
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $slug = null)
    {
        Log::info('CategoryController@show hit with slug: ' . ($slug ?? 'null'));
        // If no slug provided, treat this as "All Categories" / "All Products" view
        if (is_null($slug) || in_array($slug, ['', 'all', 'all-products', 'categories'])) {
            $products = Product::where('Status', 1)->available()->orderByRaw('TRIM(fr_Product_Name) COLLATE utf8mb4_unicode_ci ASC')->paginate(12);
            // Use the display locale (HTML_LANG) from session for UI translations so the
            // frontend shows the language selected by the user (e.g. 'ar') while
            // backend/service locale (APP_LOCALE) may remain for DB compatibility.
            $displayLocale = session('HTML_LANG', app()->getLocale());
            $title = __('All Categories', [], $displayLocale);
            $category = null;

            return view('front.pages.category_products', compact('products', 'slug', 'title', 'category'));
        }

        // Try to find a matching category by slug (en/fr) or by name fallback
        $searchName = str_replace('-', ' ', $slug);

        $category = Category::where('Status', 1)
            ->where(function ($q) use ($slug, $searchName) {
                $q->where('en_Category_Slug', $slug)
                  ->orWhere('fr_Category_Slug', $slug)
                  ->orWhere('en_Category_Name', 'like', "%{$searchName}%")
                  ->orWhere('fr_Category_Name', 'like', "%{$searchName}%");
            })->first();

        if ($category) {
            $products = Product::where('Status', 1)
                ->where('Category_Id', $category->id)
                ->available()
                ->orderByRaw('TRIM(fr_Product_Name) COLLATE utf8mb4_unicode_ci ASC')
                ->paginate(12);

            // Determine title based on the display locale selected by the user (session HTML_LANG).
            // Map display 'ar' to DB 'fr' where Arabic strings are stored in legacy installations.
            $displayLocale = session('HTML_LANG', app()->getLocale());
            $dbPrefix = ($displayLocale === 'ar') ? 'fr' : $displayLocale;
            $title = $category->{$dbPrefix . '_Category_Name'} ?? $category->en_Category_Name ?? $category->fr_Category_Name ?? ucfirst($searchName);
        } else {
            // Fallback: no matching category found — show generic product list
            $products = Product::where('Status', 1)->available()->orderByRaw('TRIM(fr_Product_Name) COLLATE utf8mb4_unicode_ci ASC')->paginate(12);
            $title = ucfirst($searchName);
        }

        return view('front.pages.category_products', compact('products', 'slug', 'title', 'category'));
    }
}
