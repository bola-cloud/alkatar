<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Admin\Billing;
use App\Models\Admin\Coupon;
use App\Models\Admin\Order;
use App\Models\Admin\Product;
use App\Models\Admin\Shipping;
use App\Models\City;
use App\Models\DeliveryCharge;
use App\Models\PaymentModel;
use App\Models\State;
use App\Models\Tax;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    public function checkoutOrder(StoreOrderRequest $request)
    {
        $validated = $request->validated();
        $user_id = Auth::id();
        Log::info('Checkout requested', ['request' => $validated]);
        try {
            // Simulate cart items and calculate totals
            $subtotal = $this->calculateSubtotal($validated['cart_items']);
            $tax = tax_amount($subtotal, $validated['billing_country']);
            $shipping_charge = delivery_charge($validated['billing_city'] ?? $validated['billing_country']);
            $weight_charge = $this->calculateExtraWeightFees($validated['cart_items']);
            $grandTotal = $subtotal + $shipping_charge + $weight_charge + $tax;
            // Apply coupon discount if available
            $discount = 0;
            if (isset($validated['coupon_code'])) {
                $coupon = Coupon::where('CouponCode', $validated['coupon_code'])
                    ->where('Status', 1)
                    ->where('ExpireDate', '>=', Carbon::now()->toDateString())
                    ->where('Min_Expenses', '<=', $subtotal)
                    ->first();
                if ($coupon && !$this->hasCouponBeenUsed($coupon->id, $user_id)) {
                    $discount = $coupon->amount;
                    $grandTotal -= $discount;
                } else {
                    return response()->json(['error' => 'Invalid or already used coupon code'], 400);
                }
            }
            // Generate unique order number
            $order_number = $this->generateOrderNumber();
            // Address handling
            if ($user_id) {
                if (hasBlillingAddress($user_id)) {
                    $billing_create = $this->updateBillingAddress($request, $user_id);
                } else {
                    $billing_create = $this->createBillingAddress($request, $user_id);
                }

                $billing_address = [
                    'name' => $billing_create->Name,
                    'email' => $billing_create->Email,
                    'street' => $billing_create->Street,
                    'state' => $billing_create->State,
                    'city' => $billing_create->City,
                    'zipcode' => $billing_create->Zipcode,
                    'country' => $billing_create->Country,
                ];

                $shipping_address = $billing_address;
            }
            $city = City::find($validated['billing_city'] ?? '');
            $state = State::find($validated['billing_state']);
            $billing_address['state_en'] = $city->name_en ?? '';
            $billing_address['state_ar'] = $city->name_ar ?? '';
            $billing_address['city_en'] = $state->name_en ?? '';
            $billing_address['city_ar'] = $state->name_ar ?? '';

            $order = Order::create([
                'Order_Number' => $order_number,
                'User_Id' => Auth::id(),
                'Billing_Id' => $billing_create->id,
                // 'Shipping_Id' => session('billing_id'),
                'billing_address' => json_encode($billing_address, true),
                'shipping_address' => json_encode($shipping_address, true),
                'Delivery_Charge' => $shipping_charge,
                'Tax' => $tax,
                'Sub_Total' => $subtotal,
                'Coupon_Id' => $validated['coupon_code'] ?? null,
                'Coupon_Amount' => $discount,
                'Grand_Total' => $grandTotal - $discount,
                'Is_Free_Delivery' => false,
                'Is_Order_Successful' => false,
                'Is_Order_Completed' => false,
                'Payment_Method' => '',
                'Payment_Status' => PAYMENT_PENDING,
                'Order_Status' => ORDER_PENDING,
//            'txn' => $txn != null ? $txn : randomString(8),
            ]);
            // Initialize the paymentData array with necessary information
            $paymentData = [
                'client_reference_id' => $order_number,
                'mode' => 'payment',
                'products' => [],
                'success_url' => route('api.thawani.success', [
                    'order_number' => $order_number,
                ]),
                'cancel_url' => route('api.thawani.fail', [
                    'order_number' => $order_number,
                ]),
                'metadata' => [
                    'order_number' => $order_number,
                    'shipping_charge' => $shipping_charge,
                    'subtotal' => $subtotal,
                    'discount' => $discount,
                    'grand_total' => $grandTotal,
                    'tax' => $tax,
                ]
            ];

// Add tax to the paymentData if it exists
            if ($tax) {
                $paymentData['products'][] = [
                    'name' => 'tax',
                    'quantity' => 1,
                    'unit_amount' => round($tax * 1000, 2),
                ];
            }

        // Add weight charge to the paymentData if it exists
            if ($weight_charge) {
                $paymentData['products'][] = [
                    'name' => 'weight extra charge',
                    'quantity' => 1,
                    'unit_amount' => round($weight_charge * 1000, 2),
                ];
            }

            // Add shipping charge to the paymentData if it exists
            if ($shipping_charge) {
                $paymentData['products'][] = [
                    'name' => 'shipping charge',
                    'quantity' => 1,
                    'unit_amount' => round($shipping_charge * 1000, 2),
                ];
            }

            // Loop through each cart item and add the product details to paymentData
            foreach ($validated['cart_items'] as $item) {
                $product = Product::find($item['product_id']);
                $size = $product->sizes()->where('size_product.Size_Id', $item['size'])->first();

                if ($size) {
                    $price = $size->pivot->price;
                    $weight = $size->pivot->weight;
                } else {
                    $price = $product->Discount_Price ?? $product->Price;
                    $weight = 0;
                }

                $paymentData['products'][] = [
                    'name' => $product->name . ' (' . $item['size'] . ')',
                    'quantity' => $item['quantity'],
                    'unit_amount' => round($price * 1000, 2),  // Price after applying the discount
                ];
            }

            // Make the API call to Thawani
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'thawani-api-key' => env('THAWANI_TEST_SECRET_KEY')
            ])->post(env('THAWANI_TEST_CHECKOUT_URL') . '/checkout/session', $paymentData);

            Log::info('Thawani API session response', ['response' => $response->body()]);

            if ($response->successful()) {
                $sessionId = $response['data']['session_id'] ?? '';
                $order->update(['session_id' => $sessionId]);
                $payment = [
                    'session_id' => $sessionId,
                    'user_id' => $user_id,
                    'order_number' => $order_number,
                    'amount' => $grandTotal,
                    'status' => 'CREATED',
                ];
                $payment = PaymentModel::create($payment);
                // Redirect the user to the Thawani payment page
                $paymentUrl = env('THAWANI_TEST_PAY_URL') . $sessionId . "?key=" . env('THAWANI_TEST_PUBLIC_KEY');
                return response()->json(['url' => $paymentUrl]);
            } else {
                return response()->json(['error' => 'Failed to create payment session'], 500);
            }

        } catch (\Exception $e) {
            Log::error('Error during checkout', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }

    public function success(Request $request)
    {
        $orderNumber = $request->get('order_number');
        Log::info('locak at request ', ['requesst' => $request->all()]);
        Log::info('Payment order id accessed', ['order_id' => $orderNumber]);
        $order = Order::where('Order_Number', $orderNumber)->first();
        Log::info('Order status updated on success', ['order_id' => $order->Id]);
        $order->update([
            'order_status' => $response['data']['payment_status'] ?? $order->order_status,
            'Payment_Method' => THAWANI,
            'Is_Order_Successful' => true,
            'Is_Order_Completed' => true,
            'Payment_Status' => PAYMENT_SUCCESS,
            'Order_Status' => ORDER_PROCESSING
        ]);
        return redirect()->to("/#/donations/paymentstatus/?payId={$order->Order_Number}");
//        return redirect()->to($request->getHost() . "/services/paymentstatus/?payId={$order->Id}");
    }

    public function fail(Request $request)
    {
        $orderNumber = $request->get('order_id');
        $order = Order::where('Order_Number', $orderNumber)->first();
        Log::info('Payment failed', ['order_id' => $orderNumber]);
        $order->update([
            'order_status' => $response['data']['payment_status'] ?? $order->order_status,
            'Is_Order_Successful' => false,
            'Is_Order_Completed' => false,
            'Payment_Status' => PAYMENT_SUCCESS,
            'Order_Status' => ORDER_CANCELLED
        ]);
        Log::info('Order status updated on failure', ['order_id' => $order->Id]);
        return redirect()->to("/#/donations/paymentstatus/?payId={$order->Id}");
//        return redirect()->to($request->getHost()."/services/paymentstatus/?payId={$order->Id}");

//        return redirect()->to('https://zakat-website.netlify.app/aboutus/');

    }


    protected function calculateSubtotal(array $cartItems)
    {
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $product = Product::find($item['product_id']);
            $size = $product->sizes()->where('size_product.Size_Id', $item['size'])->first();

            if ($size) {
                $price = $size->pivot->price;
                $discountPercentage = $product->Discount; // Discount percentage
                $discountAmount = ($discountPercentage / 100) * $price;
                $price -= $discountAmount;
            } else {
                $price = $product->Discount_Price ?? $product->Price;
            }
            $subtotal += $price * $item['quantity'];
        }
        return $subtotal;
    }

    protected function calculateTax($subtotal, $country = null)
    {
        $tax = 0;
        if ($country) {
            $tax_percentage = Tax::where('country', $country)->where('status', 'active')->first();
            if ($tax_percentage) {
                $tax = ($subtotal * $tax_percentage->percentage) / 100;
            }
        }
        return $tax;
    }

    protected function calculateShippingCharge($city = null)
    {
        $shipping_charge = 0;
        if ($city) {
            $delivery_charge = DeliveryCharge::where('city_id', $city)->where('status', 'active')->first();
            if ($delivery_charge) {
                $shipping_charge = $delivery_charge->charge;
            }
        }
        return $shipping_charge;
    }

    protected function calculateExtraWeightFees(array $cartItems)
    {
        $totalWeightGrams = 0;
        foreach ($cartItems as $item) {
            $product = Product::find($item['product_id']);
            $size = $product->sizes()->where('size_product.Size_Id', $item['size'])->first();

            $itemWeight = $size->pivot->weight ?? 0;
            $totalWeightGrams += $itemWeight * $item['quantity'];
        }

        // Convert grams to kilograms
        $totalWeightKg = $totalWeightGrams / 1000;


        $shippingFee = 0;

        if ($totalWeightKg >= 1 && $totalWeightKg <= 10) {
            $shippingFee = 2; // Example: 2 OMR for 1-10kg
        } elseif ($totalWeightKg > 10) {
            $extraKg = ceil($totalWeightKg - 10);
            $shippingFee = 2 + ($extraKg * 0.100); // Example: 2 OMR + 0.100 OMR for each extra kg
        }

        return $shippingFee;
    }

    public function generateRandomString($length = 20)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    protected function generateOrderNumber()
    {
        do {
            $order_number = $this->generateRandomString(6);
            $exists_order_number = Order::where('Order_Number', $order_number)->exists();
        } while ($exists_order_number);

        return $order_number;
    }

    protected function hasCouponBeenUsed($coupon_id, $user_id)
    {
        return Order::where('Coupon_Id', $coupon_id)->where('User_Id', $user_id)->exists();
    }

    public function createBillingAddress($request, $user_id)
    {
        return Billing::create([
            'User_Id' => $user_id,
            'Name' => $request->billing_name,
            'Email' => $request->billing_email,
            'Street' => $request->billing_street_address,
            'State' => $request->billing_state,
            'City' => $request->billing_city,
            'Zipcode' => $request->billing_zipcode,
            'Country' => $request->billing_country,
        ]);
    }

    public function updateBillingAddress($request, $user_id)
    {
        $billing = Billing::where('User_Id', $user_id)->first();
        $billing->update([
            'Name' => $request->billing_name,
            'Email' => $request->billing_email,
            'Street' => $request->billing_street_address,
            'State' => $request->billing_state,
            'City' => $request->billing_city,
            'Zipcode' => $request->billing_zipcode,
            'Country' => $request->billing_country,
        ]);
        return $billing;
    }

    public function createShippingAddress($request, $user_id)
    {
        return Shipping::create([
            'User_Id' => $user_id,
            'Name' => $request->shipping_name,
            'Email' => $request->shipping_email,
            'Street' => $request->shipping_street_address,
            'State' => $request->shipping_state,
            'Zipcode' => $request->shipping_zipcode,
            'Country' => $request->shipping_country
        ]);
    }

    public function updateShippingAddress($request, $user_id)
    {
        $shipping = Shipping::where('User_Id', $user_id)->first();
        $shipping->update([
            'Name' => $request->shipping_name,
            'Email' => $request->shipping_email,
            'Street' => $request->shipping_street_address,
            'State' => $request->shipping_state,
            'Zipcode' => $request->shipping_zipcode,
            'Country' => $request->shipping_country
        ]);
        return $shipping;
    }

}
