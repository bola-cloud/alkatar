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
            ->where('Today_Special', 1)
            ->with($relations)
            ->orderBy('id', 'desc')
            ->get();

        // If no Today_Special products are set, fallback to the latest 5 active products.
        if ($products->isEmpty()) {
            $products = Product::where('Status', 1)
                ->with($relations)
                ->orderBy('id', 'desc')
                ->take(5)
                ->get();
        }

        // Best sellers: products ordered by `Sold` desc. If none sold products exist, fallback to latest 5.
        $bestSellers = Product::where('Status', 1)
            ->where('Sold', '>', 0)
            ->with($relations)
            ->orderBy('Sold', 'desc')
            ->take(8)
            ->get();

        if ($bestSellers->isEmpty()) {
            $bestSellers = Product::where('Status', 1)
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
                $q->where('Status', 1);
            })
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        // Featured Categories to show on home (marked by admin)
        $featuredCategories = Category::where('Status', 1)
            ->where('show_on_home', 1)
            ->orderBy('order', 'asc')
            ->orderBy('id', 'desc')
            ->get();
        \Log::info("Featured Categories Count: " . $featuredCategories->count());

        // Manual lazy load products per category to avoid the total limit bug with take() inside with()
        foreach ($featuredCategories as $cat) {
            $products = $cat->products()->where('Status', 1)->with($relations)->take(12)->get();
            $cat->setRelation('products', $products);
        }

        return view('front.home.newdesign', compact('products', 'bestSellers', 'title', 'description', 'keywords', 'reviews', 'featuredCategories'));
    }
}
