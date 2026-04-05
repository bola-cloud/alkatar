<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Admin\Brand;
use App\Models\Admin\Category;
use App\Models\Admin\Color;
use App\Models\Admin\Product;
use App\Models\Admin\ProductTag;
use App\Models\Admin\Size;
use App\Models\SeoSetting;
use Illuminate\Http\Request;
use App\Models\ProductReview;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function singleProduct($slug)
    {
        $product = Product::where('en_Product_Slug', $slug)->with('category')->where('Status', 1)->available()->firstOrFail();
        if (!empty($product)) {
            $cat_id = $product->category?->id;

            $data['similar_product'] = Product::with('brand', 'category', 'colors', 'sizes', 'product_tags')->where('Status', 1)->available()
                ->where('Category_Id', $cat_id)
                ->where('id', '!=', $product->id)
                ->orderByRaw('TRIM(fr_Product_Name) COLLATE utf8mb4_unicode_ci ASC')->take(5)->get();

            $products = Product::where('id', $product->id)
                ->with([
                    'brand',
                    'category',
                    'colors',
                    'sizes',
                    'additions' => function ($query) {
                        $query->where('status', 1);
                    },
                    'product_tags',
                    'product_reviews',
                    'product_reviews.user'
                ])
                ->orderByRaw('TRIM(fr_Product_Name) COLLATE utf8mb4_unicode_ci ASC')
                ->first();
            $data['products'] = $products;
            $data['title'] = $products->en_Product_Name;
            $data['description'] = $products->en_Product_Nam;
            $data['keywords'] = $products->en_Product_Nam;
            return view('front.pages.product.single_product', $data);
        }
        return redirect()->back()->with('error', __('Product Not Found!'));
    }

    /**
     * New design product details page (keeps old data logic but uses new blade)
     * Accessible via route: product/single-new/{slug}
     */
    public function singleProductNewDesign($slug)
    {
        $product = Product::where('en_Product_Slug', $slug)->with('category')->where('Status', 1)->available()->firstOrFail();
        if (!empty($product)) {
            $cat_id = $product->category?->id;

            // Build a related-products query that prefers same category,
            // but also includes products with similar names as a fallback.
            $nameSource = $product->en_Product_Name ?? $product->fr_Product_Name ?? '';
            $words = preg_split('/\s+/', strip_tags($nameSource));
            // keep meaningful words (length > 2) and limit to first 3 keywords
            $keywords = array_slice(array_values(array_filter($words, function ($w) {
                return mb_strlen(trim($w)) > 2;
            })), 0, 3);

            $relatedQuery = Product::with('brand', 'category', 'colors', 'sizes', 'product_tags')
                ->where('Status', 1)->available()
                ->where('id', '!=', $product->id)
                ->where(function ($q) use ($cat_id, $keywords) {
                    // include same-category products when category is available
                    if (!empty($cat_id)) {
                        $q->where('Category_Id', $cat_id);
                    }

                    // also include products that match any of the name keywords
                    if (!empty($keywords)) {
                        $q->orWhere(function ($q2) use ($keywords) {
                            foreach ($keywords as $kw) {
                                $kw = trim($kw);
                                if ($kw === '') continue;
                                $q2->orWhere('en_Product_Name', 'LIKE', "%{$kw}%")
                                   ->orWhere('fr_Product_Name', 'LIKE', "%{$kw}%");
                            }
                        });
                    }
                });

            $related = $relatedQuery->orderByRaw('TRIM(fr_Product_Name) COLLATE utf8mb4_unicode_ci ASC')->take(5)->get();

            $products = Product::where('id', $product->id)
                ->with([
                    'brand',
                    'category',
                    'colors',
                    'sizes',
                    'additions' => function ($query) {
                        $query->where('status', 1);
                    },
                    'product_tags',
                    'product_reviews',
                    'product_reviews.user'
                ])
                ->orderByRaw('TRIM(fr_Product_Name) COLLATE utf8mb4_unicode_ci ASC')
                ->first();

            $data['product'] = $products;
            $data['related'] = $related;
            $data['title'] = $products->en_Product_Name;

            return view('front.pages.product_newdesign', $data);
        }
        return redirect()->back()->with('error', __('Product Not Found!'));
    }
    public function allProduct()
    {
        $data['tags'] = ProductTag::with('product')->latest()->get();
        $data['colors'] = Color::with('products')->latest()->get();
        $data['sizes'] = Size::with('products')->latest()->get();
        $data['category'] = Category::where('Status', 1)->get();
        $data['brands'] = Brand::with('products')->get();
        $products = Product::with([
            'brand',
            'category',
            'colors',
            'sizes',
            'additions' => function ($query) {
                $query->where('status', 1);
            },
            'product_tags'
        ])->where('Status', 1)->available()->orderByRaw('TRIM(fr_Product_Name) COLLATE utf8mb4_unicode_ci ASC')->paginate(9);
        $data['products'] = $products;
        $seo = SeoSetting::where('slug', 'all-products')->first();
        $data['title'] = $seo->title;
        $data['description'] = $seo->description;
        $data['keywords'] = $seo->keywords;
        if ($products) {
            return view('front.pages.product.all_product', $data);
        }
        return view('front.pages.product.empty-product', $data);
    }
    public function productListLeftSidebar()
    {
        $data['tags'] = ProductTag::with('product')->get();
        $data['colors'] = Color::with('products')->latest()->get();
        $data['sizes'] = Size::with('products')->latest()->get();
        $data['category'] = Category::where('Status', 1)->get();
        $data['brands'] = Brand::with('products')->get();
        $products = Product::with([
            'brand',
            'category',
            'colors',
            'sizes',
            'additions' => function ($query) {
                $query->where('status', 1);
            },
            'product_tags'
        ])->where('Status', 1)->available()->orderByRaw('TRIM(fr_Product_Name) COLLATE utf8mb4_unicode_ci ASC')->paginate(9);
        $data['products'] = $products;
        $seo = SeoSetting::where('slug', 'all-products')->first();
        $data['title'] = $seo->title;
        $data['description'] = $seo->description;
        $data['keywords'] = $seo->keywords;
        if ($products) {
            return view('front.pages.product.left_sidebar', $data);
        }
        return view('front.pages.product.empty-product', $data);
    }

    public function productSorting(Request $request)
    {
        if ($request->ajax()) {
            $value = $request->filter;
            if ($value == 'Categories') {
                $filters = Product::where('Status', 1)->available()->where('Category_Id', '!=', null)->orderByRaw('TRIM(fr_Product_Name) COLLATE utf8mb4_unicode_ci ASC')->get();
                if ($filters) {
                    return view('front.pages.product.filter_product', compact('filters'));
                }
            } elseif ($value == 'Brands') {
                $filters = Product::where('Status', 1)->available()->where('Brand_Id', '!=', null)->orderByRaw('TRIM(fr_Product_Name) COLLATE utf8mb4_unicode_ci ASC')->get();
                if ($filters) {
                    return view('front.pages.product.filter_product', compact('filters'));
                }
            } elseif ($value == 'Products') {
                $filters = Product::where('Status', 1)->available()->orderByRaw('TRIM(fr_Product_Name) COLLATE utf8mb4_unicode_ci ASC')->get();
                if ($filters) {
                    return view('front.pages.product.filter_product', compact('filters'));
                }
            }
        }
        return 'something wrong';
    }

    public function productFiltering(Request $request)
    {

        if ($request->ajax()) {
            if ($request->checkCat) {
                $filters = Product::where('Status', 1)->available()->with([
                    'category',
                    'additions' => function ($query) {
                        $query->where('status', 1);
                    }
                ])->whereHas('category', function ($query) use ($request) {
                    $query->whereIn('en_Category_Name', $request->checkCat);
                })->orderByRaw('TRIM(fr_Product_Name) COLLATE utf8mb4_unicode_ci ASC')->get();
            } elseif ($request->checkBrand) {
                $filters = Product::where('Status', 1)->available()->with([
                    'brand',
                    'additions' => function ($query) {
                        $query->where('status', 1);
                    }
                ])->whereHas('brand', function ($query) use ($request) {
                    $query->whereIn('en_BrandName', $request->checkBrand);
                })->orderByRaw('TRIM(fr_Product_Name) COLLATE utf8mb4_unicode_ci ASC')->get();
            } elseif ($request->checkColor) {
                $filters = Product::where('Status', 1)->available()->with([
                    'colors',
                    'additions' => function ($query) {
                        $query->where('status', 1);
                    }
                ])->whereHas('colors', function ($query) use ($request) {
                    $query->whereIn('Name', $request->checkColor);
                })->orderByRaw('TRIM(fr_Product_Name) COLLATE utf8mb4_unicode_ci ASC')->get();
            } elseif ($request->checkSize) {
                $filters = Product::where('Status', 1)->available()->with([
                    'sizes',
                    'additions' => function ($query) {
                        $query->where('status', 1);
                    }
                ])->whereHas('sizes', function ($query) use ($request) {
                    $query->whereIn('Size', $request->checkSize);
                })->orderByRaw('TRIM(fr_Product_Name) COLLATE utf8mb4_unicode_ci ASC')->get();
            } elseif ($request->search) {
                $filters = Product::where('Status', 1)->available()->where('en_Product_Name', 'LIKE', "%{$request->search}%")->orderByRaw('TRIM(fr_Product_Name) COLLATE utf8mb4_unicode_ci ASC')->get();
            } elseif ($request->min && $request->max) {
                $filters = Product::where('Status', 1)->available()->whereBetween('Discount_Price', [$request->min, $request->max])->orderByRaw('TRIM(fr_Product_Name) COLLATE utf8mb4_unicode_ci ASC')->get();
            } elseif ($request->checkSubCat) {
                $filters = Product::where('Status', 1)->available()->with([
                    'subcategory',
                    'additions' => function ($query) {
                        $query->where('status', 1);
                    }
                ])->whereHas('subcategory', function ($query) use ($request) {
                    $query->whereIn('id', $request->checkSubCat);
                })->orderByRaw('TRIM(fr_Product_Name) COLLATE utf8mb4_unicode_ci ASC')->get();
            } else {
                $filters = Product::where('Status', 1)->available()->orderByRaw('TRIM(fr_Product_Name) COLLATE utf8mb4_unicode_ci ASC')->get();
            }
        }
        return view('front.pages.product.filter_product', compact('filters'));
    }

    public function productSortingLeftSide(Request $request)
    {
        if ($request->ajax()) {
            $value = $request->filter;
            if ($value == 'Categories') {
                $filters = Product::where('Status', 1)->available()->where('Category_Id', '!=', null)->orderByRaw('TRIM(fr_Product_Name) COLLATE utf8mb4_unicode_ci ASC')->get();
                if ($filters) {
                    return view('front.pages.product.filter_product', compact('filters'));
                }
            } elseif ($value == 'Brands') {
                $filters = Product::where('Status', 1)->available()->where('Brand_Id', '!=', null)->orderByRaw('TRIM(fr_Product_Name) COLLATE utf8mb4_unicode_ci ASC')->get();
                if ($filters) {
                    return view('front.pages.product.filter_product', compact('filters'));
                }
            } elseif ($value == 'Products') {
                $filters = Product::where('Status', 1)->available()->orderByRaw('TRIM(fr_Product_Name) COLLATE utf8mb4_unicode_ci ASC')->get();
                if ($filters) {
                    return view('front.pages.product.filter_leftsidebar', compact('filters'));
                }
            }
        }
        return 'something wrong';
    }

    public function productFilteringLeftSide(Request $request)
    {
        if ($request->ajax()) {
            if ($request->checkCat) {
                $filters = Product::where('Status', 1)->available()->with('category')->whereHas('category', function ($query) use ($request) {
                    $query->whereIn('en_Category_Name', $request->checkCat);
                })->orderByRaw('TRIM(fr_Product_Name) COLLATE utf8mb4_unicode_ci ASC')->get();
            } elseif ($request->checkBrand) {
                $filters = Product::where('Status', 1)->available()->with('brand')->whereHas('brand', function ($query) use ($request) {
                    $query->whereIn('en_BrandName', $request->checkBrand);
                })->orderByRaw('TRIM(fr_Product_Name) COLLATE utf8mb4_unicode_ci ASC')->get();
            } elseif ($request->checkColor) {
                $filters = Product::where('Status', 1)->available()->with('colors')->whereHas('colors', function ($query) use ($request) {
                    $query->whereIn('Name', $request->checkColor);
                })->orderByRaw('TRIM(fr_Product_Name) COLLATE utf8mb4_unicode_ci ASC')->get();
            } elseif ($request->checkSize) {
                $filters = Product::where('Status', 1)->available()->with('sizes')->whereHas('sizes', function ($query) use ($request) {
                    $query->whereIn('Size', $request->checkSize);
                })->orderByRaw('TRIM(fr_Product_Name) COLLATE utf8mb4_unicode_ci ASC')->get();
            } elseif ($request->search) {
                $filters = Product::where('Status', 1)->available()->where('en_Product_Name', 'LIKE', "%{$request->search}%")->orderByRaw('TRIM(fr_Product_Name) COLLATE utf8mb4_unicode_ci ASC')->get();
            } elseif ($request->min && $request->max) {
                $filters = Product::where('Status', 1)->available()->whereBetween('Discount_Price', [$request->min, $request->max])->orderByRaw('TRIM(fr_Product_Name) COLLATE utf8mb4_unicode_ci ASC')->get();
            } else {
                $filters = Product::where('Status', 1)->available()->orderByRaw('TRIM(fr_Product_Name) COLLATE utf8mb4_unicode_ci ASC')->get();
            }
        }
        return view('front.pages.product.filter_leftsidebar', compact('filters'));
    }

    /**
     * Store a product review submitted from the product page.
     * Route: POST product/{product}/review -> name: product.review.store
     */
    public function storeReview(Request $request, $productId)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', __('Please login to submit a review.'));
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:2000',
            'product_id' => 'required|integer'
        ]);

        $product = Product::findOrFail($productId);

        ProductReview::create([
            'rating' => $request->input('rating', 5),
            'feedback' => $request->input('comment', ''),
            'product_id' => $product->id,
            'user_id' => Auth::id(),
        ]);

        return redirect()->back()->with('success', __('Thank you for your review.'));
    }

    public function CategoryWiseProduct($id = null)
    {
        $data['tags'] = ProductTag::with('product')->latest()->get();

        // Retrieve SEO settings
        $seo = SeoSetting::where('slug', 'all-products')->first();
        $data['title'] = $seo->title;
        $data['description'] = $seo->description;
        $data['keywords'] = $seo->keywords;

        if ($id) {
            // Category-specific search
            $category = Category::findOrFail($id);
            $products = Product::with([
                'brand',
                'category',
                'colors',
                'sizes',
                'additions' => function ($query) {
                    $query->where('status', 1);
                },
                'product_tags'
            ])
                ->where('Status', 1)->available()
                ->where('Category_Id', $id)
                ->orderByRaw('TRIM(fr_Product_Name) COLLATE utf8mb4_unicode_ci ASC')
                ->paginate(10); // Changed from 9 to 10
            $data['category'] = $category;
        } else {
            // General search across all categories
            $search = request()->input('search');
            $products = Product::with([
                'brand',
                'category',
                'colors',
                'sizes',
                'additions' => function ($query) {
                    $query->where('status', 1);
                },
                'product_tags'
            ])
                ->where('Status', 1)->available()
                ->where(function ($query) use ($search) {
                    $query->where('en_Product_Name', 'LIKE', "%{$search}%")
                        ->orWhere('fr_Product_Name', 'LIKE', "%{$search}%");
                })
                ->orderByRaw('TRIM(fr_Product_Name) COLLATE utf8mb4_unicode_ci ASC')
                ->paginate(10); // Changed from 9 to 10
        }

        $data['products'] = $products;
        $data['selected_category'] = $id;

        // dd($products->count());

        if ($products->total() > 0) {
            return view('front.pages.product.category_wise_product', $data);
        } else {
            return view('front.pages.product.empty-product', $data);
        }
    }


    public function CategoryWiseProductLeft($id)
    {
        $data['category_m'] = Category::whereId($id)->first();
        $data['tags'] = ProductTag::with('product')->latest()->get();
        $data['colors'] = Color::with('products')->latest()->get();
        $data['sizes'] = Size::with('products')->latest()->get();
        $data['category'] = Category::where('Status', 1)->get();
        $data['brands'] = Brand::with('products')->get();
        $products = Product::with([
            'brand',
            'category',
            'colors',
            'sizes',
            'additions' => function ($query) {
                $query->where('status', 1);
            },
            'product_tags'
        ])->where('Status', 1)->available()->where('Category_Id', $id)->orderByRaw('TRIM(fr_Product_Name) COLLATE utf8mb4_unicode_ci ASC')->paginate(9);
        $data['products'] = $products;
        $seo = SeoSetting::where('slug', 'all-products')->first();
        $data['title'] = $seo->title;
        $data['description'] = $seo->description;
        $data['keywords'] = $seo->keywords;
        if ($products) {
            return view('front.pages.product.category_wise_product_left', $data);
        }
        return view('front.pages.product.empty-product', $data);
    }
    public function BrandWiseProduct($id)
    {
        $data['category_m'] = Brand::whereId($id)->first();
        $data['tags'] = ProductTag::with('product')->latest()->get();
        $data['colors'] = Color::with('products')->latest()->get();
        $data['sizes'] = Size::with('products')->latest()->get();
        $data['category'] = Category::where('Status', 1)->get();
        $data['brands'] = Brand::with('products')->get();
        $products = Product::with('brand', 'category', 'colors', 'sizes', 'product_tags')->where('Status', 1)->available()->where('Brand_Id', $id)->orderByRaw('TRIM(fr_Product_Name) COLLATE utf8mb4_unicode_ci ASC')->paginate(9);
        $data['products'] = $products;
        $seo = SeoSetting::where('slug', 'all-products')->first();
        $data['title'] = $seo->title;
        $data['description'] = $seo->description;
        $data['keywords'] = $seo->keywords;
        if ($products) {
            return view('front.pages.product.brand_wise_product', $data);
        }
        return view('front.pages.product.empty-product', $data);
    }
    public function BrandWiseProductLeft($id)
    {
        $data['category_m'] = Brand::whereId($id)->first();
        $data['tags'] = ProductTag::with('product')->latest()->get();
        $data['colors'] = Color::with('products')->latest()->get();
        $data['sizes'] = Size::with('products')->latest()->get();
        $data['category'] = Category::where('Status', 1)->get();
        $data['brands'] = Brand::with('products')->get();
        $products = Product::with('brand', 'category', 'colors', 'sizes', 'product_tags')->where('Status', 1)->available()->where('Brand_Id', $id)->orderByRaw('TRIM(fr_Product_Name) COLLATE utf8mb4_unicode_ci ASC')->paginate(9);
        $data['products'] = $products;
        $seo = SeoSetting::where('slug', 'all-products')->first();
        $data['title'] = $seo->title;
        $data['description'] = $seo->description;
        $data['keywords'] = $seo->keywords;
        if ($products) {
            return view('front.pages.product.brand_wise_product_left', $data);
        }
        return view('front.pages.product.empty-product', $data);
    }

    public function CategorySearchProduct(Request $request)
    {
        $search = $request->search;
        $tags = ProductTag::with('product')->latest()->get();
        $colors = Color::with('products')->latest()->get();
        $sizes = Size::with('products')->latest()->get();
        $category = Category::where('Status', 1)->get();
        $brands = Brand::with('products')->get();
        $products = Product::query();
        $products = $products->with([
            'brand',
            'category',
            'colors',
            'sizes',
            'additions' => function ($query) {
                $query->where('status', 1);
            },
            'product_tags'
        ])->where('Status', 1)->available();

        $products = $products->where(function ($q) use ($search) {
            $q->where('en_Product_Name', 'LIKE', "%{$search}%")
                ->orWhere('fr_Product_Name', 'LIKE', "%{$search}%");
        });

        $products = $products->orderByRaw('TRIM(fr_Product_Name) COLLATE utf8mb4_unicode_ci ASC')->paginate(9);
        if (count($products) > 0) {
            return view('front.pages.product.search-result', compact('products', 'category', 'colors', 'sizes', 'brands'));
        }
        return view('front.pages.product.empty-product');
    }

    public function autoSuggest(Request $request)
    {
        $query = $request->get('query');
        $suggestions = Product::where(function ($q) use ($query) {
            $q->where('en_Product_Name', 'LIKE', "%{$query}%")
                ->orWhere('fr_Product_Name', 'LIKE', "%{$query}%");
        })
            ->where('Status', 1)->available()
            ->select('id', 'fr_Product_Name', 'en_Product_Name', 'en_Product_Slug', 'Primary_Image')
            ->limit(5)
            ->get();

        // Standardize URLs for frontend consumption
        $suggestions->transform(function ($it) {
            $img = $it->Primary_Image;
            $imgUrl = asset('new-design/images/special-offer.png');
            if ($img) {
                if (filter_var($img, FILTER_VALIDATE_URL)) {
                    $imgUrl = str_replace('http://', 'https://', $img);
                } elseif (strpos($img, 'uploaded_files/') === 0) {
                    $imgUrl = asset($img);
                } else {
                    $imgUrl = asset(ProductImage() . $img);
                }
            }
            $it->Primary_Image = $imgUrl;
            return $it;
        });

        return response()->json($suggestions);
    }
}
