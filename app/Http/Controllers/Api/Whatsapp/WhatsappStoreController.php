<?php

namespace App\Http\Controllers\Api\Whatsapp;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Category;
use App\Models\Admin\Product;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\DeliveryCharge;
use App\Http\Resources\ProductResource;
use App\Http\Resources\CategoryResource;

class WhatsappStoreController extends Controller
{
    /**
     * Register a new user via WhatsApp Bot
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $phone = $request->phone;
        // Ensure email uniqueness by using phone-based dummy email
        $email = $phone . '@hispeed.om';

        // Check if phone number or dummy email is already taken
        if (User::where('Number', $phone)->orWhere('email', $email)->exists()) {
            return response()->json(['errors' => ['phone' => [__('The phone number or email has already been taken.')]]], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $email,
            'Number' => $phone,
            'password' => Hash::make(\Illuminate\Support\Str::random(12)),
            'email_verified_at' => now(),
        ]);

        $token = $user->createToken('whatsapp_token')->plainTextToken;

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
            'token' => $token
        ], 201);
    }

    /**
     * Login user via WhatsApp Bot
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::where('Number', $request->phone)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $token = $user->createToken('whatsapp_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }

    /**
     * Get all active categories
     */
    public function getCategories()
    {
        $categories = Category::where('Status', 1)->orderBy('order', 'asc')->get();
        return CategoryResource::collection($categories);
    }

    /**
     * Get products with optional filtering
     */
    public function getProducts(Request $request)
    {
        $query = Product::with(['category', 'brand', 'weights', 'sizes', 'additions'])
            ->where('Status', 1)
            ->where('Quantity', '>', 0);

        if ($request->filled('category_id')) {
            $query->where('Category_Id', $request->category_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('en_Product_Name', 'like', "%{$search}%")
                  ->orWhere('fr_Product_Name', 'like', "%{$search}%");
            });
        }

        $products = $query->orderBy('fr_Product_Name', 'asc')->paginate($request->get('per_page', 20));

        return ProductResource::collection($products);
    }

    /**
     * Get a single product detail by ID (same data as product details page)
     */
    public function getProductDetail($id)
    {
        $product = Product::with([
            'brand',
            'category',
            'colors',
            'sizes',
            'weights',
            'additions' => function ($query) {
                $query->where('status', 1);
            },
            'product_tags',
            'product_reviews',
            'product_reviews.user',
            'comboItems',
        ])->where('Status', 1)->where('Quantity', '>', 0)->find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        // Build related products (same category + name keyword matching)
        $cat_id = $product->Category_Id;
        $nameSource = $product->en_Product_Name ?? $product->fr_Product_Name ?? '';
        $words = preg_split('/\s+/', strip_tags($nameSource));
        $keywords = array_slice(array_values(array_filter($words, function ($w) {
            return mb_strlen(trim($w)) > 2;
        })), 0, 3);

        $related = Product::with(['category', 'brand', 'weights', 'sizes'])
            ->where('Status', 1)
            ->where('Quantity', '>', 0)
            ->where('id', '!=', $product->id)
            ->where(function ($q) use ($cat_id, $keywords) {
                if (!empty($cat_id)) {
                    $q->where('Category_Id', $cat_id);
                }
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
            })
            ->latest()
            ->take(5)
            ->get();

        // Build full image list
        $images = array_values(array_filter([
            $product->resizeImage(),
            $product->Image2 ? asset('images/product/' . $product->Image2) : null,
            $product->Image3 ? asset('images/product/' . $product->Image3) : null,
            $product->Image4 ? asset('images/product/' . $product->Image4) : null,
            $product->Image5 ? asset('images/product/' . $product->Image5) : null,
        ]));

        // Calculate average rating
        $reviews = $product->product_reviews ?? collect();
        $avgRating = $reviews->count() > 0 ? round($reviews->avg('rating'), 1) : 0;

        return response()->json([
            'id' => $product->id,
            'en_Product_Name' => $product->en_Product_Name,
            'fr_Product_Name' => $product->fr_Product_Name,
            'en_Product_Slug' => $product->en_Product_Slug,
            'fr_Product_Slug' => $product->fr_Product_Slug,
            'Price' => $product->Price,
            'Discount_Price' => $product->Discount_Price,
            'Discount' => $product->Discount,
            'en_Description' => $product->en_Description,
            'fr_Description' => $product->fr_Description,
            'en_About' => $product->en_About,
            'fr_About' => $product->fr_About,
            'en_ShippingReturn' => $product->en_ShippingReturn,
            'fr_ShippingReturn' => $product->fr_ShippingReturn,
            'en_AdditionalInformation' => $product->en_AdditionalInformation,
            'fr_AdditionalInformation' => $product->fr_AdditionalInformation,
            'Quantity' => $product->virtual_stock,
            'in_stock' => $product->virtual_stock > 0,
            'images' => $images,
            'category' => $product->category ? [
                'id' => $product->category->id,
                'en_Category_Name' => $product->category->en_Category_Name,
                'fr_Category_Name' => $product->category->fr_Category_Name,
            ] : null,
            'brand' => $product->brand ? [
                'id' => $product->brand->id,
                'en_BrandName' => $product->brand->en_BrandName,
                'fr_BrandName' => $product->brand->fr_BrandName,
            ] : null,
            'weights' => $product->weights->map(function ($w) {
                return [
                    'id' => $w->id,
                    'weight' => $w->weight,
                    'price' => $w->price,
                ];
            }),
            'sizes' => $product->sizes->map(function ($s) {
                return [
                    'id' => $s->id,
                    'Size' => $s->Size,
                    'price' => $s->pivot->price ?? 0,
                    'weight' => $s->pivot->weight ?? 0,
                ];
            }),
            'additions' => $product->additions->map(function ($a) {
                return [
                    'id' => $a->id,
                    'name' => $a->name,
                    'name_ar' => $a->name_ar,
                    'price' => $a->price,
                    'icon' => $a->icon,
                ];
            }),
            'colors' => $product->colors->map(function ($c) {
                return [
                    'id' => $c->id,
                    'Name' => $c->Name,
                    'Code' => $c->Code,
                ];
            }),
            'reviews' => [
                'average_rating' => $avgRating,
                'total_count' => $reviews->count(),
                'items' => $reviews->take(10)->map(function ($r) {
                    return [
                        'id' => $r->id,
                        'rating' => $r->rating,
                        'feedback' => $r->feedback,
                        'user_name' => $r->user->name ?? 'Guest',
                        'created_at' => $r->created_at?->toDateTimeString(),
                    ];
                }),
            ],
            'related_products' => ProductResource::collection($related),
        ]);
    }

    /**
     * Get hierarchy of shipping locations with delivery charges
     */
    public function getShippingLocations()
    {
        $countries = Country::with(['states.cities'])->get()->map(function($country) {
            return [
                'id' => $country->id,
                'name' => $country->name,
                'name_ar' => $country->name_ar,
                'delivery_charge' => delivery_charge($country->name), // Fallback country charge
                'states' => $country->states->map(function($state) {
                    return [
                        'id' => $state->id,
                        'name' => $state->name_en,
                        'name_ar' => $state->name_ar,
                        'delivery_charge' => delivery_charge($state->id), // State charge
                        'cities' => $state->cities->map(function($city) {
                            return [
                                'id' => $city->id,
                                'name' => $city->name_en,
                                'name_ar' => $city->name_ar,
                                'delivery_charge' => delivery_charge($city->id), // City charge
                            ];
                        })
                    ];
                })
            ];
        });

        return response()->json($countries);
    }

    /**
     * Checkout order from WhatsApp Bot
     */
    public function checkout(\App\Http\Requests\StoreOrderRequest $request)
    {
        $validated = $request->validated();
        $user = auth()->user();
        $user_id = $user->id;

        // Calculate Totals
        $subtotal = $this->calculateSubtotal($validated['cart_items']);

        // Enforce Minimum Order Amount
        $min_order_amount = floatval(allsetting('min_order_amount') ?: 3.990);
        if ($subtotal < $min_order_amount) {
            return response()->json([
                'message' => __('Minimum order amount is :amount OMR.', ['amount' => number_format($min_order_amount, 3)])
            ], 422);
        }
        
        $countryName = $validated['billing_country'];
        $tax = 0;
        $taxFound = false;
        
        $taxModel = \App\Models\Tax::where('country', $countryName)->where('status', 1)->first();
        if ($taxModel) {
            $tax = ($subtotal * $taxModel->percentage) / 100;
            $taxFound = true;
        }

        if (!$taxFound) {
            $globalTax = floatval(allsetting()['tax_percentage'] ?? 0);
            if ($globalTax > 0) {
                $tax = ($subtotal * $globalTax) / 100;
            }
        }

        // Refined shipping charge resolution: City > State > Country
        // The improved delivery_charge helper now handles the fallback internally.
        $city_id = $validated['billing_city'] ?? null;
        $state_id = $validated['billing_state'] ?? null;
        $country = $validated['billing_country'] ?? 'Oman';

        $shipping_charge = delivery_charge($city_id, 'city');
        
        // If still zero, the helper didn't find a city charge or it was zero, so try state
        if ($shipping_charge == 0) {
            $shipping_charge = delivery_charge($state_id, 'state');
        }

        // If STILL zero, fallback to country name string match
        if ($shipping_charge == 0) {
            $shipping_charge = delivery_charge($country);
        }
        $weight_charge = $this->calculateExtraWeightFees($validated['cart_items']);
        $grandTotal = $subtotal + $shipping_charge + $weight_charge + $tax;

        // Discount
        $discount = 0;
        if (isset($validated['coupon_code'])) {
            $coupon = \App\Models\Admin\Coupon::where('CouponCode', $validated['coupon_code'])
                ->where('Status', 1)
                ->where('ExpireDate', '>=', \Illuminate\Support\Carbon::now()->toDateString())
                ->where('Min_Expenses', '<=', $subtotal)
                ->first();

            if ($coupon) {
                $discount = $coupon->amount;
                $grandTotal -= $discount;
            }
        }

        $order_number = $this->generateOrderNumber();
        
        // Billing
        $billing = \App\Models\Admin\Billing::updateOrCreate(
            ['User_Id' => $user_id],
            [
                'Name' => $validated['billing_name'],
                'Email' => $validated['billing_email'] ?? $user->email,
                'Street' => $validated['billing_street_address'] ?? '',
                'State' => $validated['billing_state'],
                'City' => $validated['billing_city'] ?? null,
                'Zipcode' => $validated['billing_zipcode'],
                'Country' => $validated['billing_country'],
            ]
        );

        $state = \App\Models\State::find($validated['billing_state']);
        $city = \App\Models\City::find($validated['billing_city']);

        $billing_address = [
            'name' => $billing->Name,
            'email' => $billing->Email,
            'street' => $billing->Street,
            'state' => $billing->State,
            'city' => $billing->City,
            'zipcode' => $billing->Zipcode,
            'country' => $billing->Country,
            'state_en' => $state->name_en ?? '',
            'state_ar' => $state->name_ar ?? '',
            'city_en' => $city->name_en ?? '',
            'city_ar' => $city->name_ar ?? '',
            'phone_number' => $user->Number ?? '',
        ];

        $payment_method = $validated['Payment_Method'];
        if ($payment_method == 'CashOnDelivery') {
            $payment_method = 'COD';
        }

        $initial_status = ORDER_PENDING;
        if ($payment_method == 'COD' || $payment_method == 'Thawani') {
            $initial_status = ORDER_PROCESSING;
        }

        // Create Order
        $order = \App\Models\Admin\Order::create([
            'Order_Number' => $order_number,
            'User_Id' => $user_id,
            'Billing_Id' => $billing->id,
            'billing_address' => $billing_address,
            'shipping_address' => $billing_address, // Same as billing for simplicity in bots
            'Delivery_Charge' => $shipping_charge,
            'Tax' => $tax,
            'Sub_Total' => $subtotal,
            'Coupon_Id' => $validated['coupon_code'] ?? null,
            'Coupon_Amount' => $discount,
            'Grand_Total' => $grandTotal,
            'Is_Free_Delivery' => false,
            'Is_Order_Successful' => false,
            'Is_Order_Completed' => false,
            'Payment_Method' => $payment_method,
            'Order_Status' => $initial_status,
            'order_source' => $validated['order_source'],
        ]);

        foreach ($validated['cart_items'] as $item) {
            $product = \App\Models\Admin\Product::find($item['product_id']);
            $this->subQtyProduct($product->id, $item['quantity']);

            $sizePrice = 0;
            $sizeWeight = 0;
            if (!empty($item['size_id'])) {
                $size = $product->sizes()->where('size_product.Size_Id', $item['size_id'])->first();
                $sizePrice = $size->pivot->price ?? 0;
                $sizeWeight = $size->pivot->weight ?? 0;
            }

            $weightPrice = 0;
            $weightValue = 0;
            if (!empty($item['weight_id'])) {
                $weight = \App\Models\WeightProduct::find($item['weight_id']);
                $weightPrice = $weight->price ?? 0;
                $weightValue = $weight->weight ?? 0;
            }

            $price = $sizePrice + $weightPrice;
            if ($price == 0) {
                $price = $product->Price ?? 0;
            }
            $additions = \App\Models\Admin\Addition::whereIn('id', $item['addition_ids'] ?? [])->get();
            $price += $additions->sum('price');

            if ($product->Discount) {
                $price -= ($product->Discount / 100) * $price;
            }

            $productName = app()->getLocale() === 'ar' ? $product->fr_Product_Name : $product->en_Product_Name;

            \App\Models\Admin\OrderDetails::create([
                'Order_Id' => $order->id,
                'Product_Id' => $product->id,
                'Product_Name' => $productName,
                'Image' => $product->Primary_Image,
                'Price' => $price,
                'Size' => $sizeWeight ?: $weightValue,
                'Quantity' => $item['quantity'],
                'Total_Price' => $price * $item['quantity'],
            ]);
        }

        // Sync Order to Smart ERP immediately as UNPAID (Two-step sync approach)
        if (config('smartlife.sync_enabled')) {
            try {
                $smartLifeService = app(\App\Services\SmartLifeErpService::class);
                $invoiceId = $smartLifeService->submitOrder($order);
                if ($invoiceId) {
                    $order->smartlife_synced_at = now();
                    $order->smartlife_invoice_id = $invoiceId;
                    $order->save();
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('SmartLife sync failed in WhatsApp checkout', ['error' => $e->getMessage()]);
            }
        }

        // Generate Thawani Session if needed
        if ($validated['Payment_Method'] == 'Thawani') {
            return $this->generateThawaniSession($order, $user);
        }

        // For non-Thawani (e.g., COD), trigger Print App and Push Notifications
        try {
            event(new \App\Events\OrderCreated($order));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('WhatsApp Checkout: Broadcast failure (ignoring).', ['error' => $e->getMessage()]);
        }

        return response()->json([
            'message' => 'Order created successfully',
            'order_number' => $order_number,
            'grand_total' => $order->Grand_Total,
            'payment_method' => $validated['Payment_Method'],
            'receipt_url' => route('order.print', ['id' => $order->id]),
            'invoice_pdf_url' => route('api.whatsapp.invoice_pdf', ['id' => $order->id])
        ]);
    }

    protected function calculateSubtotal(array $cartItems)
    {
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $product = \App\Models\Admin\Product::find($item['product_id']);
            $sizePrice = 0;
            if (!empty($item['size_id'])) {
                $size = $product->sizes()->where('size_product.Size_Id', $item['size_id'])->first();
                $sizePrice = $size->pivot->price ?? 0;
            }
            $weightPrice = 0;
            if (!empty($item['weight_id'])) {
                $weight = \App\Models\WeightProduct::find($item['weight_id']);
                $weightPrice = $weight->price ?? 0;
            }
            $additions = \App\Models\Admin\Addition::whereIn('id', $item['addition_ids'] ?? [])->get();
            $additionPrice = $additions->sum('price');

            $basePrice = $sizePrice + $weightPrice;
            if ($basePrice == 0) {
                $basePrice = $product->Price ?? 0;
            }
            $price = $basePrice + $additionPrice;
            if ($product->Discount) {
                $price -= ($product->Discount / 100) * $price;
            }
            $subtotal += $price * $item['quantity'];
        }
        return $subtotal;
    }

    protected function calculateExtraWeightFees(array $cartItems)
    {
        $totalWeightGrams = 0;
        foreach ($cartItems as $item) {
            $product = \App\Models\Admin\Product::find($item['product_id']);
            $sizeWeight = 0;
            if (!empty($item['size_id'])) {
                $size = $product->sizes()->where('size_product.Size_Id', $item['size_id'])->first();
                $sizeWeight = $size->pivot->weight ?? 0;
            }
            $weightValue = 0;
            if (!empty($item['weight_id'])) {
                $weight = \App\Models\WeightProduct::find($item['weight_id']);
                $weightValue = $weight->weight ?? 0;
            }
            $totalWeightGrams += ($sizeWeight + $weightValue) * $item['quantity'];
        }

        $totalWeightKg = $totalWeightGrams / 1000;
        $fee = 0;
        if ($totalWeightKg > 25) { // Assuming 25kg free limit
            $extraKg = ceil($totalWeightKg - 25);
            $fee = $extraKg * 0.100;
        }
        return $fee;
    }

    protected function generateOrderNumber()
    {
        do {
            $num = strtoupper(\Illuminate\Support\Str::random(6));
        } while (\App\Models\Admin\Order::where('Order_Number', $num)->exists());
        return $num;
    }

    protected function subQtyProduct($product_id, $qty)
    {
        $product = \App\Models\Admin\Product::find($product_id);
        if ($product) {
            $product->update(['Quantity' => max(0, $product->Quantity - $qty)]);
        }
    }

    protected function generateThawaniSession($order, $user)
    {
        $paymentData = [
            'client_reference_id' => $order->Order_Number,
            'mode' => 'payment',
            'products' => [
                [
                    'name' => 'Order ' . $order->Order_Number,
                    'quantity' => 1,
                    'unit_amount' => (int) round($order->Grand_Total * 1000), // Ensures an integer in baisa
                ]
            ],
            'success_url' => route('api.thawani.success', ['order_number' => $order->Order_Number, 'phone_number' => $user->Number]),
            'cancel_url' => route('api.thawani.fail', ['order_number' => $order->Order_Number]),
        ];

        $response = \Illuminate\Support\Facades\Http::withHeaders([
            'Content-Type' => 'application/json',
            'thawani-api-key' => config('services.thawani.secret_key')
        ])->post(config('services.thawani.checkout_url') . '/checkout/session', $paymentData);

        if ($response->successful()) {
            $sessionId = $response['data']['session_id'] ?? null;
            if ($sessionId) {
                $order->update(['session_id' => $sessionId]);
                
                \App\Models\PaymentModel::create([
                    'session_id' => $sessionId,
                    'user_id' => $user->id,
                    'order_number' => $order->Order_Number,
                    'amount' => $order->Grand_Total,
                    'status' => 'CREATED',
                ]);

                $paymentUrl = config('services.thawani.pay_url') . $sessionId . "?key=" . config('services.thawani.public_key');
                return response()->json([
                    'message' => 'Payment session created',
                    'order_number' => $order->Order_Number,
                    'grand_total' => $order->Grand_Total,
                    'payment_method' => 'Thawani',
                    'url' => $paymentUrl,
                    'invoice_pdf_url' => route('api.whatsapp.invoice_pdf', ['id' => $order->id])
                ]);
            }
        }

        \Illuminate\Support\Facades\Log::error('Thawani API Checkout Error', ['body' => $response->body(), 'status' => $response->status()]);
        return response()->json(['error' => 'Payment gateway error', 'details' => $response->json() ?? $response->body()], 500);
    }

    /**
     * Generate actual PDF for WhatsApp Invoice (Optimized for mPDF)
     */
    public function getOrderInvoicePdf($id)
    {
        $order = \App\Models\Admin\Order::with(['order_details', 'user', 'billing', 'order_details.product'])->find($id);
        
        if (!$order) {
            abort(404, 'Order not found');
        }

        $order['billing_address'] = $order->billing_address;

        $pdf = \PDF::loadView('admin.pages.orders.pdf_invoice', compact('order'), [], [
            'mode'                 => 'utf-8',
            'format'               => 'A4-P',
            'autoScriptToLang'     => true,
            'autoArabic'           => true,
            'margin_left'          => 10,
            'margin_right'         => 10,
            'margin_top'           => 10,
            'margin_bottom'        => 10,
        ]);

        return $pdf->stream('invoice-' . $order->Order_Number . '.pdf');
    }

    /**
     * Get last order details for authenticated user
     */
    public function getLastOrder()
    {
        $order = \App\Models\Admin\Order::where('User_Id', auth()->id())->latest()->first();
        
        if (!$order) {
            return response()->json(['message' => 'No orders found'], 404);
        }

        return response()->json([
            'order' => [
                'id' => $order->id,
                'order_number' => $order->Order_Number,
                'grand_total' => $order->Grand_Total,
                'order_status' => $order->Order_Status,
                'created_at' => $order->created_at?->toDateTimeString(),
                'invoice_pdf_url' => route('api.whatsapp.invoice_pdf', ['id' => $order->id])
            ]
        ]);
    }
}
