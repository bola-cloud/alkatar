<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Admin\Product;
use App\Models\Admin\Category;
use App\Models\SeoSetting;
use App\Models\ProductReview;
use Illuminate\Http\Request;

class NewDesignController extends Controller
{
    /**
     * Display the new design home page.
     */
    public function index()
    {
        $relations = ['sizes', 'weights', 'additions', 'comboItems'];

        // Only show admin-selected Today Special products in the special offers carousel.
        $products = Product::where('Status', 1)
            ->available()
            ->where('Today_Special', 1)
            ->with($relations)
            ->orderBy('id', 'desc')
            ->get();

        // If no Today_Special products are set, fallback to the latest 5 active products.
        if ($products->isEmpty()) {
            $products = Product::where('Status', 1)
                ->available()
                ->with($relations)
                ->orderBy('id', 'desc')
                ->take(5)
                ->get();
        }

        // Best sellers: products ordered by `Sold` desc. If none sold products exist, fallback to latest 5.
        $bestSellers = Product::where('Status', 1)
            ->available()
            ->where('Sold', '>', 0)
            ->with($relations)
            ->orderBy('Sold', 'desc')
            ->take(8)
            ->get();

        if ($bestSellers->isEmpty()) {
            $bestSellers = Product::where('Status', 1)
                ->available()
                ->with($relations)
                ->orderBy('id', 'desc')
                ->take(5)
                ->get();
        }

        // Load SEO for home page (admin-managed). Controller supplies title/description/keywords to the view.
        $seo = SeoSetting::where('slug', 'home')->first();
        $title = $seo->title ?? __('HiSpeed — New Design');
        $description = $seo->description ?? '';
        $keywords = $seo->keywords ?? '';

        $reviews = ProductReview::with('user', 'product')
            ->where('is_visible', true)
            ->whereHas('product', function ($q) {
                $q->where('Status', 1)->available();
            })
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        // All active categories for the "Browse Categories" carousel
        $allCategories = Category::where('Status', 1)
            ->orderBy('order', 'asc')
            ->orderBy('id', 'desc')
            ->get();

        // Featured Categories to show as individual product sections (marked by admin)
        $featuredCategories = $allCategories->where('show_on_home', 1);

        // \Log::info("Home: All active categories: " . $allCategories->count() . ", Featured: " . $featuredCategories->count());

        // Manual lazy load products per featured category
        foreach ($featuredCategories as $cat) {
            $catProducts = $cat->products()->where('Status', 1)->available()->orderByRaw('fr_Product_Name COLLATE utf8mb4_unicode_ci ASC')->with($relations)->take(12)->get();
            $cat->setRelation('products', $catProducts);
        }

        return view('front.home.newdesign', compact('products', 'bestSellers', 'title', 'description', 'keywords', 'reviews', 'allCategories', 'featuredCategories'));
    }
}
