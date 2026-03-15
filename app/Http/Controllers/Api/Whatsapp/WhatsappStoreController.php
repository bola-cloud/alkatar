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
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string',
            'country_code' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $full_phone = $request->country_code . $request->phone;

        // Check if phone number is unique
        if (User::where('Number', $full_phone)->exists()) {
            return response()->json(['errors' => ['phone' => [__('The phone number has already been taken.')]]], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'Number' => $full_phone,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(), // Assume verified if coming from WhatsApp for now, or we can use OTP
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
            'login_id' => 'required|string', // Email or Phone
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $login_id = $request->login_id;
        $user = User::where('email', $login_id)
            ->orWhere('Number', $login_id)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
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
            ->where('Status', 1);

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

        $products = $query->latest()->paginate($request->get('per_page', 20));

        return ProductResource::collection($products);
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

        $shipping_charge = delivery_charge($validated['billing_city'] ?? $validated['billing_state'] ?? $validated['billing_country']);
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
            'Payment_Method' => $validated['Payment_Method'],
            'order_status' => 'pending',
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

            $weight = \App\Models\WeightProduct::find($item['weight_id']);
            $weightPrice = $weight->price ?? 0;
            $weightValue = $weight->weight ?? 0;

            $price = $sizePrice + $weightPrice;
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

        // Generate Thawani Session if needed
        if ($validated['Payment_Method'] == 'Thawani') {
            return $this->generateThawaniSession($order, $user);
        }

        return response()->json([
            'message' => 'Order created successfully',
            'order_number' => $order_number,
            'grand_total' => $grandTotal
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
            $weight = \App\Models\WeightProduct::find($item['weight_id']);
            $weightPrice = $weight->price ?? 0;
            $additions = \App\Models\Admin\Addition::whereIn('id', $item['addition_ids'] ?? [])->get();
            $additionPrice = $additions->sum('price');

            $price = $sizePrice + $weightPrice + $additionPrice;
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
            $weight = \App\Models\WeightProduct::find($item['weight_id']);
            $weightValue = $weight->weight ?? 0;
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
        // For simplicity, returning a mock URL or real one depends on keys
        // We'll follow Api/CheckoutController logic
        
        $paymentData = [
            'client_reference_id' => $order->Order_Number,
            'mode' => 'payment',
            'products' => [
                [
                    'name' => 'Order ' . $order->Order_Number,
                    'quantity' => 1,
                    'unit_amount' => round($order->Grand_Total * 1000, 2),
                ]
            ],
            'success_url' => route('api.thawani.success', ['order_number' => $order->Order_Number, 'phone_number' => $user->Number]),
            'cancel_url' => route('api.thawani.fail', ['order_number' => $order->Order_Number]),
        ];

        $response = \Illuminate\Support\Facades\Http::withHeaders([
            'thawani-api-key' => config('services.thawani.secret_key')
        ])->post(config('services.thawani.checkout_url') . '/checkout/session', $paymentData);

        if ($response->successful()) {
            $sessionId = $response['data']['session_id'];
            $order->update(['session_id' => $sessionId]);
            $paymentUrl = config('services.thawani.pay_url') . $sessionId . "?key=" . config('services.thawani.public_key');
            return response()->json(['url' => $paymentUrl]);
        }

        return response()->json(['error' => 'Payment gateway error'], 500);
    }
}
