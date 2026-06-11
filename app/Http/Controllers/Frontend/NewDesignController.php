<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Admin\Product;
use App\Models\Admin\Category;
use App\Models\SeoSetting;
use App\Models\ProductReview;
use App\Models\Admin\Order;
use App\Models\User;
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

        // Manual lazy load products per featured category
        foreach ($featuredCategories as $cat) {
            $catProducts = $cat->products()->where('Status', 1)->available()->orderByRaw('fr_Product_Name COLLATE utf8mb4_unicode_ci ASC')->with($relations)->take(12)->get();
            $cat->setRelation('products', $catProducts);
        }

        // Load homepage sections from content management
        $whyChoose = \App\Models\Admin\SiteContent\HomepageSection::where('section_key', 'newdesign_why_choose')->first();
        $features = \App\Models\Admin\SiteContent\HomepageSection::where('section_key', 'newdesign_features')->first();
        $saleBanner = \App\Models\Admin\SiteContent\HomepageSection::where('section_key', 'newdesign_sale_banner')->first();
        $brands = \App\Models\Admin\SiteContent\HomepageSection::where('section_key', 'newdesign_brands')->first();
        $heroSection = \App\Models\Admin\SiteContent\HomepageSection::where('section_key', 'newdesign_hero')->first();
        $statsSection = \App\Models\Admin\SiteContent\HomepageSection::where('section_key', 'newdesign_stats')->first();

        // Fetch dynamic packages
        $packages = Product::where('Status', 1)
            ->available()
            ->whereHas('category', function ($q) {
                $q->where('en_Category_Slug', 'packages');
            })
            ->with($relations)
            ->orderBy('id', 'desc')
            ->get();

        return view('front.home.newdesign', compact(
            'products', 'bestSellers', 'title', 'description', 'keywords', 
            'reviews', 'allCategories', 'featuredCategories',
            'whyChoose', 'features', 'saleBanner', 'brands', 'heroSection', 'statsSection',
            'packages'
        ));
    }

    public function store()
    {
        // عرض صفحة المتجر الكامل مع جلب الفئات والمنتجات المتاحة
        $categories = Category::where('Status', 1)->orderBy('order', 'asc')->get();
        $products = Product::available()->get();
        return view('front.home.store', compact('categories', 'products'));
    }

    public function login()
    {
        // عرض صفحة تسجيل الدخول (فرونت فقط)
        return view('front.auth.login');
    }

    public function register()
    {
        // عرض صفحة إنشاء الحساب (فرونت فقط)
        return view('front.auth.register');
    }

    public function product_details()
    {
        // عرض صفحة تفاصيل المنتج (فرونت فقط)
        return view('front.home.product_details');
    }

    public function cart()
    {
        // عرض صفحة سلة التسوق (فرونت فقط)
        return view('front.home.cart');
    }

    public function wholesale()
    {
        // عرض صفحة طلبات الجملة
        return view('front.home.wholesale');
    }

    public function storeWholesaleRequest(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_name' => 'required|string|max:255',
            'contact_phone' => 'required|string|max:50',
            'estimated_qty' => 'required|string|max:255',
            'services' => 'nullable|array',
            'notes' => 'nullable|string',
        ]);

        \App\Models\Front\WholesaleRequest::create([
            'company_name' => $request->company_name,
            'contact_name' => $request->contact_name,
            'contact_phone' => $request->contact_phone,
            'estimated_qty' => $request->estimated_qty,
            'services' => $request->services,
            'notes' => $request->notes,
            'status' => 0, // Pending
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => app()->getLocale() == 'en' 
                    ? 'Your wholesale order request has been submitted successfully!' 
                    : 'تم إرسال طلب الجملة الخاص بك بنجاح!',
            ]);
        }

        return redirect()->back()->with('success', app()->getLocale() == 'en' 
            ? 'Your wholesale order request has been submitted successfully!' 
            : 'تم إرسال طلب الجملة الخاص بك بنجاح!');
    }

    public function become_partner()
    {
        // عرض صفحة كن شريكاً
        return view('front.home.become_partner');
    }

    public function social_responsibility()
    {
        // عرض صفحة المسؤولية الاجتماعية
        return view('front.home.social_responsibility');
    }

    public function trial_boxes()
    {
        // عرض صفحة بوكسات التجربة
        $category = Category::where('en_Category_Slug', 'trial-boxes')
                            ->orWhere('fr_Category_Slug', 'trial-boxes')
                            ->first();
        $products = $category ? $category->products()->where('Status', 1)->get() : collect();
        return view('front.home.trial_boxes', compact('products'));
    }

    public function coffee_crops()
    {
        // عرض صفحة محاصيل القهوة
        $category = Category::where('en_Category_Slug', 'coffee-crops')
                            ->orWhere('fr_Category_Slug', 'coffee-crops')
                            ->first();
        $products = $category ? $category->products()->where('Status', 1)->get() : collect();
        $subcategories = $category ? $category->subCategories()->where('status', 1)->get() : collect();
        
        $advertises = \App\Models\Admin\Advertise::where('status', 1)
            ->where('location', 'coffee_crops')
            ->orderBy('display_order', 'asc')
            ->get();
            
        if ($advertises->isEmpty()) {
            $advertises = \App\Models\Admin\Advertise::where('status', 1)
                ->where('location', 'hero')
                ->orderBy('display_order', 'asc')
                ->get();
        }

        return view('front.home.coffee_crops', compact('products', 'subcategories', 'advertises'));
    }

    public function technical_tools()
    {
        // عرض صفحة معدات التحضير / الأدوات الفنية
        $category = Category::where('en_Category_Slug', 'preparation-tools')
                            ->orWhere('fr_Category_Slug', 'preparation-tools')
                            ->first();
        $products = $category ? $category->products()->where('Status', 1)->get() : collect();
        $subcategories = $category ? $category->subCategories()->where('status', 1)->get() : collect();

        $advertises = \App\Models\Admin\Advertise::where('status', 1)
            ->where('location', 'technical_tools')
            ->orderBy('display_order', 'asc')
            ->get();
            
        if ($advertises->isEmpty()) {
            $advertises = \App\Models\Admin\Advertise::where('status', 1)
                ->where('location', 'hero')
                ->orderBy('display_order', 'asc')
                ->get();
        }

        return view('front.home.technical_tools', compact('products', 'subcategories', 'advertises'));
    }

    public function experts()
    {
        // عرض صفحة استعن بخبير / الخبراء
        return view('front.home.experts');
    }

    public function storeExpertRequest(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:50',
            'message' => 'required|string',
        ]);

        \App\Models\Front\ExpertRequest::create([
            'name' => $request->name,
            'company' => $request->company,
            'email' => $request->email,
            'phone' => $request->phone,
            'message' => $request->message,
            'status' => 0, // Pending
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => app()->getLocale() == 'en' 
                    ? 'Your expert assistance request has been submitted successfully!' 
                    : 'تم إرسال طلب استشارة الخبير بنجاح!',
            ]);
        }

        return redirect()->back()->with('success', app()->getLocale() == 'en' 
            ? 'Your expert assistance request has been submitted successfully!' 
            : 'تم إرسال طلب استشارة الخبير بنجاح!');
    }

    public function monthly_offers()
    {
        $packages = \App\Models\Admin\Product::where('Status', 1)
            ->available()
            ->whereHas('category', function ($q) {
                $q->where('en_Category_Slug', 'packages');
            })
            ->orderBy('id', 'desc')
            ->get();

        return view('front.home.monthly_offers', compact('packages'));
    }

    public function gift_cards()
    {
        // عرض صفحة بطاقة الإهداء
        return view('front.home.gift_cards');
    }

    public function contact_us()
    {
        // عرض صفحة تواصل معنا
        return view('front.home.contact_us');
    }

    public function contact_us_store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        \App\Models\Front\Contactus::create([
            'FirstName' => $request->name,
            'Email' => $request->email,
            'subject' => $request->subject,
            'Message' => $request->message,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => app()->getLocale() == 'en' 
                    ? 'Your message has been sent successfully!' 
                    : 'تم إرسال رسالتك بنجاح!',
            ]);
        }

        return redirect()->back()->with('success', app()->getLocale() == 'en' 
            ? 'Your message has been sent successfully!' 
            : 'تم إرسال رسالتك بنجاح!');
    }

    public function profile()
    {
        $user = auth()->user();
        
        // If not authenticated, we fallback to mock data matching the Figma layout
        if (!$user) {
            $user = (object)[
                'name' => 'Alex Johnson',
                'email' => 'alex.j@example.com',
                'Number' => '555-0123',
                'image' => null
            ];
            $orders = collect([
                (object)[
                    'id' => 1,
                    'Order_Number' => 'ORD-88219',
                    'collection_method' => 'delivery',
                    'collection_method_label' => 'Delivery',
                    'created_at' => \Carbon\Carbon::parse('2023-10-24 18:45:00'),
                    'Grand_Total' => 34.50,
                    'subtotal' => 30.50,
                    'delivery_fee' => 2.50,
                    'service_fee' => 1.50,
                    'tax' => 0.00,
                    'payment_method_brand' => 'Visa',
                    'payment_method_last4' => '4242',
                    'delivery_name' => 'Jane Doe',
                    'delivery_address' => '4521 Sunset Boulevard, Apt 4B, Los Angeles, CA 90027',
                    'Order_Status' => ORDER_DELIVERED,
                    'order_details' => collect([
                        (object)[
                            'product' => (object)[
                                'en_Product_Name' => 'bnvkfdnlfmdl;mglkdsnb',
                                'fr_Product_Name' => 'بن القطر الفاخر',
                                'volume' => '100ml',
                                'image' => 'assets/elketar/placeholder.png'
                            ],
                            'Quantity' => 1,
                            'Price' => 18.50
                        ],
                        (object)[
                            'product' => (object)[
                                'en_Product_Name' => 'bdcfksndlgmdlkjgb',
                                'fr_Product_Name' => 'قهوة مختصة كولومبيا',
                                'volume' => '100ml',
                                'image' => 'assets/elketar/placeholder.png'
                            ],
                            'Quantity' => 1,
                            'Price' => 12.00
                        ]
                    ])
                ],
                (object)[
                    'id' => 2,
                    'Order_Number' => 'ORD-7722',
                    'collection_method' => 'delivery',
                    'collection_method_label' => 'Delivery',
                    'created_at' => \Carbon\Carbon::parse('2023-10-24'),
                    'Grand_Total' => 32.50,
                    'subtotal' => 28.50,
                    'delivery_fee' => 2.50,
                    'service_fee' => 1.50,
                    'tax' => 0.00,
                    'payment_method_brand' => 'Visa',
                    'payment_method_last4' => '1111',
                    'delivery_name' => 'Alex Johnson',
                    'delivery_address' => 'Al-Maha St, Muscat, Oman',
                    'Order_Status' => ORDER_CANCELLED,
                    'order_details' => collect([
                        (object)[
                            'product' => (object)[
                                'en_Product_Name' => 'Ethiopia Yirgacheffe',
                                'fr_Product_Name' => 'بن إثيوبيا يرجاشيف',
                                'volume' => '250g',
                                'image' => 'assets/elketar/placeholder.png'
                            ],
                            'Quantity' => 2,
                            'Price' => 16.25
                        ]
                    ])
                ]
            ]);
            $addresses = collect([
                (object)[
                    'id' => 1,
                    'title' => 'Family Home',
                    'type' => 'home',
                    'street' => '123 Maple Avenue, Apt 4B',
                    'building_no' => 'Bldg 5',
                    'apartment' => 'Apt 4B',
                    'city' => 'Springfield',
                    'state' => 'IL',
                    'zip_code' => '62704',
                    'country' => 'United States',
                    'phone' => '(555) 6666666',
                    'notes' => 'Gate code 1234, ring bell',
                    'is_default' => true
                ],
                (object)[
                    'id' => 2,
                    'title' => 'Springfield Tech Hub',
                    'type' => 'work',
                    'street' => '456 Business Loop, Suite 200',
                    'building_no' => 'Suite 200',
                    'apartment' => 'Floor 2',
                    'city' => 'Springfield',
                    'state' => 'IL',
                    'zip_code' => '62701',
                    'country' => 'United States',
                    'phone' => '(555) 6666666',
                    'notes' => 'Deliver to front desk reception',
                    'is_default' => false
                ]
            ]);
            $reviews = collect([
                (object)[
                    'id' => 1,
                    'rating' => 5,
                    'feedback' => 'This coffee is incredible! Deep rich notes and a perfect roast.',
                    'product_id' => 1,
                    'is_visible' => true,
                    'created_at' => \Carbon\Carbon::parse('2023-10-25 10:30:00'),
                    'product' => (object)[
                        'id' => 1,
                        'en_Product_Name' => 'Premium El Katar Blend',
                        'fr_Product_Name' => 'بن القطر الفاخر',
                        'image' => 'assets/elketar/placeholder.png'
                    ]
                ]
            ]);
        } else {
            // Load real data from DB
            $orders = Order::with(['order_details', 'order_details.product'])->where('User_Id', $user->id)->latest()->get();
            $addresses = $user->addresses()->get();
            $reviews = ProductReview::where('user_id', $user->id)->with('product')->orderByDesc('created_at')->get();
        }

        return view('front.home.profile', compact('user', 'orders', 'addresses', 'reviews'));
    }
}
