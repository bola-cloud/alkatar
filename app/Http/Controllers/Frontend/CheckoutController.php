<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PaymentController;
use App\Http\Requests\CheckoutOrderRequest;
use App\Http\Services\BasePaymentService;
use App\Http\Services\InstamojoService;
use App\Http\Services\PaymentService;
// use App\Jobs\OrderConfirmMail;
use App\Library\SslCommerz\SslCommerzNotification;
use App\Models\Admin\Billing;
use App\Models\Admin\Coupon;
use App\Models\Admin\Order;
use App\Models\Admin\OrderDetails;
use App\Models\Admin\Product;
use App\Models\Admin\Shipping;
use App\Models\City;
use App\Models\Country;
use App\Models\Currency;
use App\Models\PaymentPlatform;
use App\Models\SeoSetting;
use App\Models\State;
use App\Resolvers\PaymentPlatformResolver;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\Http;
use Exception;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmMail;
use Illuminate\Support\Str;


class CheckoutController extends Controller
{
    private $grand_total;
    private $discount;
    protected $paymentPlatformResolver;
    protected $paymentController;



    public function __construct(PaymentPlatformResolver $paymentPlatformResolver, PaymentController $paymentController)
    {
        $this->discount = 0;
        $this->paymentPlatformResolver = $paymentPlatformResolver;
        $this->paymentController = $paymentController;
    }

    public function calculateExtraWeightFees()
    {
        $totalWeightGrams = 0;
        foreach (Cart::content() as $item) {
            $itemWeight = $item->options->weight->weight ?? 0;
            $totalWeightGrams += $itemWeight * $item->qty;
        }

        // Convert grams to kilograms
        $totalWeightKg = $totalWeightGrams / 1000;

        $shippingFee = 0;

        // if ($totalWeightKg >= 1 && $totalWeightKg <= 10) {
        //     $shippingFee = 0; // 2 OMR for 1-10kg
        // } else
        if ($totalWeightKg > 10) {
            $extraKg = ceil($totalWeightKg - 10);
            $shippingFee = ($extraKg * 0.100); //  0.100 OMR for each extra kg
        }

        return $shippingFee;
    }


    public function checkoutPage()
    {
        $check = Cart::count();
        if ($check) {
            $data['content'] = Cart::content();
            $data['currencies'] = Currency::all();
            $data['paymentPlatforms'] = PaymentPlatform::where('status', ACTIVE)->get();
            $data['user'] = Auth::user();
            $data['billing'] = Billing::where('User_Id', Auth::id())->first() ?? Auth::user();
            $data['shipping'] = Shipping::where('User_Id', Auth::id())->first();
            $seo = SeoSetting::where('slug', 'checkout')->first();
            $data['title'] = $seo->title;
            $data['description'] = $seo->description;
            $data['keywords'] = $seo->keywords;
            $data['extraWeightFees'] = $this->calculateExtraWeightFees();
            $oman_country_id = Country::where('name_en', 'Oman')->first()->id;
            $data['states'] = State::where('country_id', $oman_country_id)->get();
            // dd($data);

            return view('front.pages.checkout.checkout', $data);
        } else {
            return redirect()->route('front')->with('toast_warning', 'Cart is Empty');
        }
    }

    public function thankyouPage()
    {
        $data['title'] = __('Order Confirmed');
        $data['description'] = __('Order Confirmed');
        $data['keywords'] = __('Order Confirmed');
        return view('front.pages.checkout.thankyou', $data);
    }
    public function checkoutOrder(Request $request)
    {
        $isLoggedIn = Auth::check();
        $user_id = $isLoggedIn ? Auth::id() : null;


        // Validation
        $validationRules = [
            'billing_name' => 'required',
            'billing_email' => 'nullable|email',
            'billing_street_address' => 'nullable',
            'billing_zipcode' => 'required',
            'billing_country' => 'required',
            "billing_phone" => 'required|digits_between:8,11',
        ];

        $validationMessages = [
            'billing_phone.required' => __('The phone number field is required.'),
            'billing_phone.digits_between' => __('The phone number must be correct number.'),
        ];
        // dd($request->all());

        if (!$isLoggedIn) {
            $validationRules += [
                'billing_name' => 'required',
                'billing_state' => 'required',
                'billing_country' => 'required',
                'billing_zipcode' => 'required',
            ];

            $validationMessages += [
                'billing_name.required' => __('The name field is required.'),
                'billing_state.required' => __('The state field is required.'),
                'billing_zipcode.required' => __('The zipcode field is required.'),
                'billing_country.required' => __('The country field is required.'),
            ];
        }

        $request->validate($validationRules, $validationMessages);

        try {
            $subtotal = Cart::subtotal();
            $cartItems = Cart::content();
            $tax = tax_amount($subtotal, $request->billing_country);
            $shipping_charge = delivery_charge($request->billing_city ?? $request->billing_country);
            $weight_charge = $this->calculateExtraWeightFees();
            $shipping_charge += $weight_charge;
            $this->grand_total = $subtotal + $shipping_charge;

            $shipping_city = City::find($request->billing_city);
            $shipping_state = State::find($request->billing_state);


            // Address handling
            if ($isLoggedIn) {
                if (hasBlillingAddress($user_id) == 1) {
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
            } else {
                $billing_address = [
                    'name' => $request->billing_name,
                    'email' => $request->billing_email,
                    'street' => $request->billing_street_address,
                    'state' => $request->billing_state,
                    'city' => $request->billing_city,
                    'zipcode' => $request->billing_zipcode,
                    'country' => $request->billing_country,
                ];

                $shipping_address = [
                    'name' => $request->shipping_name,
                    'email' => $request->shipping_email,
                    'street' => $request->shipping_street_address,
                    'state' => $request->shipping_state,
                    'city' => $request->shipping_city,
                    'zipcode' => $request->shipping_zipcode,
                    'country' => $request->shipping_country
                ];
            }

            $billing_address['state_en'] = $shipping_state->name_en;
            $billing_address['state_ar'] = $shipping_state->name_ar;
            $billing_address['city_en'] = $shipping_city->name_en;
            $billing_address['city_ar'] = $shipping_city->name_ar;
            $billing_address['phone_number'] = $request->billing_phone;

            $shipping_address['state_en'] = $shipping_state->name_en;
            $shipping_address['state_ar'] = $shipping_state->name_ar;
            $shipping_address['city_en'] = $shipping_city->name_en;
            $shipping_address['city_ar'] = $shipping_city->name_ar;
            $shipping_address['phone_number'] = $request->billing_phone;


            Session::put('billing_address', $billing_address);
            Session::put('shipping_address', $shipping_address);
            Session::put('checkout_email', $billing_address['email']);

            if ($isLoggedIn) {
                Session::put('billing_id', $billing_create->id);
                Session::put('shipping_id', $billing_create->id);
            }

            // Generate unique order number
            do {
                $order_number = $this->generateRandomString(6);
                $exists_order_number = Order::where('Order_Number', $order_number)->exists();
            } while ($exists_order_number);

            // Coupon handling
            if ($isLoggedIn && Session::has('Coupon_Id')) {
                $coupon = Coupon::whereId(Session::get('Coupon_Id'))->first();
                if ($coupon) {
                    $orderCoupon = Order::where('Coupon_Id', $coupon->id)->where('User_Id', $user_id)->count();
                    if ($orderCoupon != 0) {
                        session()->put('couponCode', null);
                        return redirect()->back()->with('error', 'Already used coupon Code');
                    }
                    $this->discount = $coupon->Amount;
                }
            }

            // Set session variables
            session()->put('order_number', $order_number);
            session()->put('shipping_charge', $shipping_charge);
            session()->put('subtotal', $subtotal);
            session()->put('discount', $this->discount);
            session()->put('grand_total', $this->grand_total);
            session()->put('tax', $tax);

            // Payment processing
            switch ($request->payment) {
                case 'creditcard':
                    session()->put('payment_method_name', STRIPE);
                    return $this->pay($this->grand_total * (conversion_rate('USD') ? conversion_rate('USD') : 0), $this->discount, 'USD', 2, $request->payment_method);

                case 'paypal':
                    $checkoutProduct = [];

                    foreach ($cartItems as $item) {
                        $checkoutProduct[] = [
                            'name' => Str::limit($item->name, 35),
                            'quantity' => $item->qty,
                            'unit_amount' => number_format($item->price, 3) * 1000,
                        ];
                    }
                    if ($shipping_charge) {
                        $checkoutProduct[] = [
                            'name' => 'Shipping Charge',
                            'quantity' => 1,
                            'unit_amount' => $shipping_charge * 1000,
                        ];
                    }


                    $response = Http::withHeaders([
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                        'thawani-api-key' => env('THAWANI_TEST_SECRET_KEY'),
                    ])->post(env('THAWANI_TEST_CHECKOUT_URL') . '/checkout/session', [
                                'client_reference_id' => $order_number,
                                'mode' => 'payment',
                                'products' => $checkoutProduct,
                                'success_url' => route('thawani.success', [
                                    'order_number' => $order_number,
                                ]),
                                'cancel_url' => route('thawani.cancel', [
                                    'order_number' => $order_number,
                                ]),
                                'metadata' => [
                                    'order_number' => $order_number,
                                    'shipping_charge' => $shipping_charge,
                                    'subtotal' => $subtotal,
                                    'discount' => $this->discount,
                                    'grand_total' => $this->grand_total,
                                    'tax' => $tax,
                                ]
                            ]);


                    if ($response->successful()) {
                        $paymentJsonData = $response->json();

                        // create new request body for create payment
                        $payment = [
                            'session_id' => $paymentJsonData['data']['session_id'],
                            'user_id' => $user_id,
                            'order_number' => $order_number,
                            'amount' => $this->grand_total,
                            'status' => 'CREATED',
                        ];


                        $paymentRequest = new Request($payment);


                        // create payment
                        $this->paymentController->createPayment($paymentRequest);

                        $paymentUrl = env('THAWANI_TEST_PAY_URL') . $paymentJsonData['data']['session_id'] . '?key=' . env("THAWANI_TEST_PUBLIC_KEY");
                        $this->orderCreateCall($order_number, $shipping_charge, $tax, $subtotal, $this->discount, $this->grand_total, " ");

                        return redirect()->away($paymentUrl);
                    } else {
                        // Handle the error case
                        return response()->json(['error' => 'Failed to create session'.$response], 500);
                    }

                case 'COD':
                    return $this->orderCreateCall($order_number, $shipping_charge, $tax, $subtotal, $this->discount, $this->grand_total, COD);

                case 'bank':
                    if ($request->bank_transaction_number != null) {
                        return $this->orderCreateCall($order_number, $shipping_charge, $tax, $subtotal, $this->discount, $this->grand_total, BANK_TRANSFER, $request->bank_transaction_number);
                    } else {
                        return redirect()->back()->with('error', 'Bank Transaction Number is Required.');
                    }

                default:
                    return redirect()->back()->with('error', 'Payment method is required');
            }
        } catch (\Exception $e) {
            info($e);
            return redirect()->back()->with('error', 'Something went wrong');
        }

    }
    public function guestCheckoutOrder(Request $request)
    {
        $request->validate([
            'billing_name' => 'required',
            'billing_email' => 'required|email',
            'billing_street_address' => 'required',
            'billing_zipcode' => 'required',
            'billing_country' => 'required',

            'shipping_name' => 'required',
            'shipping_email' => 'required|email',
            'shipping_street_address' => 'required',
            'shipping_state' => 'required',
            'shipping_zipcode' => 'required',
            'shipping_country' => 'required',
        ], [
            'shipping_name' => 'The name field is required.',
            'shipping_email' => 'The email field is required.',
            'shipping_street_address' => 'The address field is required.',
            'shipping_state' => 'The state field is required.',
            'shipping_zipcode' => 'The zip code field is required.',
            'shipping_country' => 'The country field is required.',
        ]);

        $billing_address = [
            'name' => $request->billing_name,
            'email' => $request->billing_email,
            'street' => $request->billing_street_address,
            'state' => $request->billing_state,
            'zipcode' => $request->billing_zipcode,
            'country' => $request->billing_country,
        ];

        $shipping_addresss = [
            'name' => $request->shipping_name,
            'email' => $request->shipping_email,
            'street' => $request->shipping_street_address,
            'state' => $request->shipping_state,
            'zipcode' => $request->shipping_zipcode,
            'country' => $request->shipping_country
        ];
        Session::put('billing_address', $billing_address);
        Session::put('shipping_address', $shipping_addresss);
        Session::put('checkout_email', $request->billing_email);

        $subtotal = Cart::subtotal();
        $tax = tax_amount($subtotal, $request->billing_country);
        $shipping_charge = delivery_charge($request->billing_country);
        $this->grand_total = $subtotal + $tax + $shipping_charge;

        do {
            $order_number = $this->generateRandomString(6);
            $exists_order_number = Order::where('Order_Number', $order_number)->exists();
        } while ($exists_order_number);

        if (Auth::check() && Session::has('Coupon_Id')) {
            $user_id = Auth::id();
            if (hasBlillingAddress($user_id) == 1) {
                $this->updateBillingAddress($request, $user_id);
            } else {
                $this->createBillingAddress($request, $user_id);
            }

            if (hasShippingAddress($user_id) == 1) {
                $this->updateShippingAddress($request, $user_id);
            } else {
                $this->createShippingAddress($request, $user_id);
            }
            $coupon = Coupon::whereId(Session::get('Coupon_Id'))->first();
            if ($coupon) {
                $orderCoupon = Order::where('Coupon_Id', $coupon->id)->where('User_Id', $user_id)->count();
                if ($orderCoupon != 0) {
                    session()->put('couponCode', null);
                    return redirect()->back()->with('error', 'Already used coupon Code');
                }
                $this->discount = $coupon->Amount;
            }
        }

        session()->put('order_number', $order_number);
        session()->put('shipping_charge', $shipping_charge);
        session()->put('subtotal', $subtotal);
        session()->put('discount', $this->discount);
        session()->put('grand_total', $this->grand_total);
        session()->put('tax', $tax);

        if ($request->payment == 'creditcard') {
            session()->put('payment_method_name', STRIPE);
            return $this->pay($this->grand_total, $this->discount, 'USD', 2, $request->payment_method);
        } elseif ($request->payment == 'sslcommerz') {
            $tran_id = uniqid();
            $post_data = array();
            $post_data['total_amount'] = $this->grand_total; # You cant not pay less than 10
            $post_data['currency'] = "BDT";
            $post_data['tran_id'] = $tran_id; // tran_id must be unique

            # CUSTOMER INFORMATION
            $post_data['cus_name'] = $request->billing_name;
            $post_data['cus_email'] = $request->billing_email;
            $post_data['cus_add1'] = $request->billing_street_address;
            $post_data['cus_add2'] = "";
            $post_data['cus_city'] = $request->billing_state;
            $post_data['cus_state'] = $request->billing_state;
            $post_data['cus_postcode'] = $request->billing_zipcode;
            $post_data['cus_country'] = $request->billing_country;
            $post_data['cus_phone'] = '8801XXXXXXXXX';
            $post_data['cus_fax'] = "";

            # SHIPMENT INFORMATION
            $post_data['ship_name'] = $request->shipping_name;
            $post_data['ship_add1'] = $request->shipping_street_address;
            $post_data['ship_add2'] = "";
            $post_data['ship_city'] = $request->shipping_state;
            $post_data['ship_state'] = $request->shipping_state;
            $post_data['ship_postcode'] = $request->shipping_zipcode;
            $post_data['ship_phone'] = "";
            $post_data['ship_country'] = $request->shipping_country;

            $post_data['shipping_method'] = "sslcommerz";
            $post_data['product_name'] = "Computer";
            $post_data['product_category'] = "Goods";
            $post_data['product_profile'] = "physical-goods";

            $this->orderCreateCall($order_number, $shipping_charge, $tax, $subtotal, $this->discount, $this->grand_total, SSLCOMMERZ, $post_data['tran_id']);

            $sslc = new SslCommerzNotification();
            $payment_options = $sslc->makePayment($post_data, 'hosted');
            if (!is_array($payment_options)) {
                print_r($payment_options);
                $payment_options = array();
            }
        } elseif ($request->payment == 'paypal') {
            session()->put('payment_method_name', PAYPAL);
            return $this->pay($this->grand_total, $this->discount, 'USD', 1, $request->payment_method);
        } elseif ($request->payment == 'COD') {
            return $this->orderCreateCall($order_number, $shipping_charge, $tax, $subtotal, $this->discount, $this->grand_total, COD);
        } elseif ($request->payment == 'bank') {
            if ($request->bank_transaction_number != null) {
                return $this->orderCreateCall($order_number, $shipping_charge, $tax, $subtotal, $this->discount, $this->grand_total, BANK_TRANSFER, $request->bank_transaction_number);
            } else {
                return redirect()->back()->with('error', 'Bank Transaction Number is Required.');
            }
        } elseif ($request->payment == 'razorpay') {
            $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
            $payment = $api->payment->fetch($request->razorpay_payment_id);
            try {
                $response = $api->payment->fetch($request->razorpay_payment_id)->capture(array('amount' => $payment['amount']));
                return $this->orderCreateCall($order_number, $shipping_charge, $tax, $subtotal, $this->discount, $this->grand_total, RAZORPAY, $request->razorpay_payment_id);
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Something went wrong in razorpay.');
            }
        } else {
            return redirect()->back()->with('error', 'Payment Failed');
        }
    }

    public function pay($amount, $discount, $currency, $payment_platform, $payment_method)
    {
        $value = $amount - $discount;
        $paymentPlatform = $this->paymentPlatformResolver->resolveService($payment_platform);
        session()->put('paymentPlatformId', $payment_platform);
        return $paymentPlatform->handlePayment($value, $currency, $payment_method);
    }

    public function approval()
    {
        if (session()->has('paymentPlatformId')) {

            $paymentPlatform = $this->paymentPlatformResolver
                ->resolveService(session()->get('paymentPlatformId'));

            $payment = $paymentPlatform->handleApproval();

            if ($payment['success'] == true) {
                return $this->orderCreateCall(
                    session()->get('order_number'),
                    session()->get('shipping_charge'),
                    session()->get('tax'),
                    session()->get('subtotal'),
                    session()->get('discount'),
                    session()->get('grand_total'),
                    session()->get('payment_method_name')
                );
            }
            return redirect()->back()->with('error', $payment['message']);
        }
        return redirect()->back()->with('error', 'We can not retrieve payment platform. Please,  try again!');
    }

    public function cancelled()
    {
        return redirect()->back()->with('error', 'Payment cancelled!');
    }

    public function orderCreateCall($order_number, $shipping_charge, $tax, $subtotal, $discount, $grand_total, $payment_method, $txn = null)
    {
        $payment_status = $this->paymentStatus($payment_method);
        $order = $this->orederCreate($order_number, $shipping_charge, $tax, $subtotal, $discount, $grand_total, $payment_method, $payment_status, $txn);
        if ($order['success'] == true) {
            session()->forget('Coupon_Id');
            Cart::destroy();
            return redirect()->route('checkout.thankyou_page')->with('success', 'Order successfully created!');
        }
        return redirect()->back()->with('error', 'Order not accepted');
    }

    public function orderConfirmMail($order)
    {
        $ship = json_decode($order->shipping_address, true);
        $data['userName'] = $ship['name'] ?? null;
        $data['userEmail'] = $ship['email'] ?? null;
        $data['order'] = $order;
        $data['companyName'] = isset(allsetting()['app_title']) && !empty(allsetting()['app_title']) ? allsetting()['app_title'] : __('Company Name');
        $data['subject'] = __('Order Confirm Mail');
        $data['data'] = $order->Order_Number;
        $data['template'] = 'email.order-confirm';
        // dispatch(new OrderConfirmMail($data))->onQueue('email-send');
    }

    public function sendOrderMail($id)
    {
        $order = Order::query()
            ->with('order_details', 'user', 'coupon', 'order_details.product', 'billing', 'shipping')
            ->find($id);

        $order['billing_address'] = json_decode($order->billing_address, true);
        try {
            Mail::to('Alsaraamills@gmail.com')->send(new OrderConfirmMail($order));
            return response()->json(['msg' => 'OK']);
        } catch (Exception $ex) {
            return response()->json(['msg' => "$ex"]);
        }
    }

    public function paymentStatus($payment_method)
    {
        if ($payment_method == STRIPE || $payment_method == PAYPAL) {
            return PAYMENT_SUCCESS;
        }
        return PAYMENT_PENDING;
    }

    public function orederCreate($order_number, $shipping_charge, $tax, $subtotal, $discount, $grand_total, $payment_method, $payment_status, $txn = null)
    {
        try {
            $data = ['success' => false, 'data' => []];

            $order = Order::create([
                'Order_Number' => $order_number,
                'User_Id' => Auth::check() ? Auth::id() : null,
                'Billing_Id' => session('billing_id'),
                // 'Shipping_Id' => session('billing_id'),
                'billing_address' => json_encode(Session::get('billing_address'), true),
                'shipping_address' => json_encode(Session::get('shipping_address'), true),
                'Delivery_Charge' => $shipping_charge,
                'Tax' => $tax,
                'Sub_Total' => $subtotal,
                'Coupon_Id' => Session::get('Coupon_Id'),
                'Coupon_Amount' => $discount,
                'Grand_Total' => $grand_total - $discount,
                'Is_Free_Delivery' => false,
                'Is_Order_Successful' => false,
                'Is_Order_Completed' => false,
                'Payment_Method' => $payment_method,
                'Payment_Status' => $payment_status,
                'Order_Status' => ORDER_PENDING,
                'txn' => $txn != null ? $txn : randomString(8),
            ]);


            if ($order) {
                session()->put('Coupon_Id', null);
                session()->put('couponCode', null);
                session()->put('CouponAmount', 0);
                session()->put('order_id', $order->id);
                session()->put('checkout_number', $order->Order_Number);
                $content = Cart::content();
                foreach ($content as $item) {
                    $this->subQtyProduct($item->id, $item->qty);
                    OrderDetails::create([
                        'Order_Id' => $order->id,
                        'Product_Id' => $item->id,
                        'Product_Name' => $item->name,
                        'Image' => $item->options->image,
                        'Price' => $item->price,
                        'Color' => $item->options->color,
                        'Size' => $item->options->size,
                        'Quantity' => $item->qty,
                        'Total_Price' => $item->price * $item->qty,
                    ]);
                }
                $data['success'] = true;
            }
            // mail
            // $this->orderConfirmMail($order);

            $this->sendOrderMail($order->id);

            return $data;
        } catch (\Exception $e) {
            info('dasd' . $e->getMessage());
            $this->error($e->getMessage());
            return $this->error($e->getMessage());
        }
    }

    public function subQtyProduct($product_id, $qty)
    {
        $product = Product::whereId($product_id)->first();
        $new_qty = $product->Quantity - $qty;
        if ($new_qty < 1) {
            $nn_qty = 0;
        } else {
            $nn_qty = $new_qty;
        }
        $product->update([
            'Quantity' => $nn_qty,
        ]);
    }

    public function redirectStripePage()
    {
        $data['currencies'] = Currency::all();
        $data['paymentPlatforms'] = PaymentPlatform::all();
        return view('front.pages.checkout.stripe', $data);
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

    public function getTaxAmount(Request $request)
    {
        $data = [
            'success' => false,
            'tax_rate' => 0,
            'tax_amount' => 0,
            'tax_show' => 0,
            'total_cost' => 0,
            'total_cost_curr' => 0,
            'delivery_charge' => 0,
            'delivery_charge_curr' => 0,
        ];
        $data['tax_rate'] = tax_rate($request->country);
        $data['tax_amount'] = tax_amount(Cart::subtotal(), $request->country);
        $data['tax_show'] = currencyConverter(tax_amount(Cart::subtotal(), $request->country));
        $data['delivery_charge'] = delivery_charge($request->country);
        $data['delivery_charge_curr'] = currencyConverter(delivery_charge($request->country));
        $data['total_cost'] = Cart::subtotal() + delivery_charge($request->country) + tax_amount(Cart::subtotal(), $request->country) - Session::get('CouponAmount');
        $data['total_cost_curr'] = currencyConverter(Cart::subtotal() + delivery_charge($request->country) + tax_amount(Cart::subtotal(), $request->country) - Session::get('CouponAmount'));
        $data['success'] = true;
        return $data;
    }

    public function orderTrack(Request $request)
    {
        $order = Order::where('Order_Number', $request->order_number)->with('order_details', 'order_details.product')->first();
        if (is_null($order)) {
            return redirect()->back()->with('error', __('No order found!'));
        }
        $data['order'] = Order::where('Order_Number', $request->order_number)->with('order_details', 'order_details.product')->first();
        $data['title'] = __('Order Track');
        $data['description'] = __('Order Track');
        $data['keywords'] = __('Order Track');
        return view('front.pages.checkout.order-track', $data);
    }

    public function paymentSuccess(Request $request)
    {
        $data = $request->all();
        $order = Order::where('Order_Number', $data['order_number'])->first();

        $order->Is_Order_Successful = true;
        $order->Is_Order_Completed = true;
        $order->Payment_Method = THAWANI;
        $order->Payment_Status = PAYMENT_SUCCESS;
        $order->Order_Status = ORDER_PROCESSING;

        $order->save();

        return redirect()->route('checkout.thankyou_page')->with('success', 'Order successfully created!');
    }

    public function paymentCancel(Request $request)
    {
        $data = $request->all();
        $order = Order::where('Order_Number', $data['order_number'])->first();

        $order->Is_Order_Successful = false;
        $order->Is_Order_Completed = false;
        $order->Order_Status = ORDER_CANCELLED;

        $order->save();

        return redirect()->route('front')->with('error', 'Order Cancelled!');
    }
}
