<?php

use App\Http\Controllers\Api\PaymentApiController;
use App\Http\Controllers\CityController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\AuthController;
use App\Http\Controllers\Frontend\AboutUsController;
use App\Http\Controllers\Frontend\BlogController;
use App\Http\Controllers\Frontend\BlogCommentController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\BuyNowController;
use App\Http\Controllers\Frontend\CouponController;
use App\Http\Controllers\Frontend\ProductController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\WishlistController;
use App\Http\Controllers\Frontend\ContactUsController;
use App\Http\Controllers\Frontend\SubscribeController;
use App\Http\Controllers\Frontend\CompareListController;
use App\Http\Controllers\Frontend\UserProfileController;
use App\Http\Controllers\Frontend\ServiceCustomerController;
use App\Http\Controllers\Frontend\SubscribeSessionController;
use App\Http\Controllers\Frontend\PaymentController;
use App\Http\Controllers\Frontend\PageController;
use App\Http\Controllers\SslCommerzPaymentController;
use App\Http\Controllers\Frontend\NewDesignController;
use App\Http\Controllers\Frontend\CategoryController;
use App\Models\Admin\Category;
use App\Models\Admin\Order;
use App\Models\Admin\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use League\Csv\Reader;


//Route::redirect('/', '');
Route::post('currency-price', [CartController::class, 'currencyPrice'])->name('currency_price');
Route::get('currency-symbol', [CartController::class, 'currencySymbol'])->name('currency_symbol');
Route::group(['middleware' => ['is_user']], function () {
    Route::get('/olddesign', [HomeController::class, 'index'])->name('front.olddesign');
    Route::get('/', [NewDesignController::class, 'index'])->name('front');
    Route::get('/theme-set/{theme}', [HomeController::class, 'theme_set']);
    Route::get('locale/{lang}', [HomeController::class, 'localeSwitch'])->name('locale.switch');
    Route::get('currency/{amount}', [HomeController::class, 'currencySwitch'])->name('currency.switch');
    Route::post('subscribe', [SubscribeController::class, 'subscribe'])->name('subscribe');

    //session value store get and delete
    Route::get('do_not_subscribe', [SubscribeSessionController::class, 'doNotSubscribe'])->name('do.not.subscribe');
    Route::get('get_session', [SubscribeSessionController::class, 'doNotSubscribeGet']);
    Route::get('remove_session', [SubscribeSessionController::class, 'doNotSubscribeRemove']);

    Route::group(['prefix' => 'contact-us'], function () {
        Route::get('/', [ContactUsController::class, 'contactUs'])->name('contact.us');
        Route::post('store', [ContactUsController::class, 'contactUsStore'])->name('contact.us.store')->middleware(['isDemo']);
    });
    Route::group(['prefix' => 'blog'], function () {
        Route::get('/', [BlogController::class, 'index'])->name('blog');
        Route::get('/blog-details/{id}', [BlogController::class, 'blogDetails'])->name('blog.details');
        Route::post('/blog-comment', [BlogCommentController::class, 'blogComment'])->name('user.blog.comment')->middleware(['isDemo']);
    });

    Route::get('/payments/approval', [CheckoutController::class, 'approval'])->name('approval');
    Route::get('/payments/cancelled', [CheckoutController::class, 'cancelled'])->name('cancelled');
    Route::get('/stripe-collapse', [PaymentController::class, 'stripeCollapse'])->name('stripe_collapse');

    Route::get('about-us', [AboutUsController::class, 'aboutUS'])->name('about.us');

    Route::group(['prefix' => 'user/'], function () {
        //User Sign-in and Sign-up
        Route::get('sign-in', [AuthController::class, 'userSignIn'])->name('login');
        Route::post('sign-in', [AuthController::class, 'userSignInPost'])->name('user.sign.in.post');
        Route::post('otp', [AuthController::class, 'otpSignInPost'])->name("user.sign.otp");
        Route::get('otp-verify', [AuthController::class, 'otpVerify'])->name("user.otp.verify.get");
        Route::post('otp-verify', [AuthController::class, 'otpVerifyPost'])->name("user.otp.verify");
        Route::get("complete-registration", [AuthController::class, 'completeRegistration'])->name("user.complete.registration");
        Route::post('login-modal', [AuthController::class, 'loginModal'])->name('user.sign.modal');
        Route::get('sign-up', [AuthController::class, 'userSignUp'])->name('user.sign.up');
        Route::post('sign-up', [AuthController::class, 'userSignUpPost'])->name('user.sign.up.post');
        Route::get('auth/google', [AuthController::class, 'redirectToGoogle'])->name('user.redirect_google');
        Route::get('auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('user.handle_google_callback');
        Route::get('auth/facebook', [AuthController::class, 'redirectToFacebook'])->name('user.redirect_facebook');
        Route::get('auth/facebook/callback', [AuthController::class, 'handleFacebookCallback'])->name('user.handle_facebook_callback');
        Route::get('logout', [AuthController::class, 'userLogout'])->name('user.logout');
        
        // Email verification
        Route::get('verify-whatsapp', [AuthController::class, 'showVerifyEmail'])->name('user.verify.email');
        Route::post('verify-whatsapp', [AuthController::class, 'verifyEmailPost'])->name('user.verify.email.post');
        Route::get('resend-otp', [AuthController::class, 'resendOtp'])->name('user.resend.otp');

        Route::post('change-password', [AuthController::class, 'userChangePassword'])->name('user.profile.change.password')->middleware(['isDemo']);
        //forget password
        Route::get('forget-password', [AuthController::class, 'userForgetPasswordGet'])->name('forget.password.get');
        Route::post('forget-password', [AuthController::class, 'userForgetPasswordPost'])->name('forget.password.post')->middleware(['isDemo']);
        Route::get('reset-password/{token}', [AuthController::class, 'userShowResetPasswordForm'])->name('reset.password.get');
        Route::post('reset-password', [AuthController::class, 'submitResetPasswordForm'])->name('reset.password.post')->middleware(['isDemo']);

        //User Profile

        Route::group(['middleware' => 'auth'], function () {
            Route::get('profile', [UserProfileController::class, 'userProfile'])->name('user.profile');
            Route::get('profile-edit', [UserProfileController::class, 'userProfileEdit'])->name('user.profile.edit');
            Route::post('profile-update', [UserProfileController::class, 'userProfileUpdate'])->name('user.profile.update')->middleware(['isDemo']);
            Route::get('my-order', [UserProfileController::class, 'myOrder'])->name('user.profile.myOrder');
            Route::get('my-review', [UserProfileController::class, 'myReview'])->name('user.profile.myReview');
            Route::post('review-store', [UserProfileController::class, 'reviewStore'])->name('user.profile.review_store')->middleware(['isDemo']);
            Route::get('track-my-order/{id}', [UserProfileController::class, 'trackMyOrder'])->name('user.profile.track.my.order');

            // Subscription Payment
            Route::post('subscription/pay', [\App\Http\Controllers\Frontend\SubscriptionPaymentController::class, 'initiatePayment'])->name('user.subscription.pay');
            Route::get('subscription/callback', [\App\Http\Controllers\Frontend\SubscriptionPaymentController::class, 'paymentCallback'])->name('user.subscription.callback');

            // Order Invoice/Print
            Route::get('order-print/{id}', [\App\Http\Controllers\Frontend\OrderController::class, 'order_print'])->name('order.print');

            // wishlist
            Route::group(['prefix' => 'wishlist'], function () {
                Route::get('/', [WishlistController::class, 'Wishlist'])->name('wishlist');
                Route::get('delete', [WishlistController::class, 'delete'])->name('wishlist.delete');
            });

            // comparelist
            Route::group(['prefix' => 'compare'], function () {
                Route::get('', [CompareListController::class, 'Comparelist'])->name('compare');
                Route::get('delete', [CompareListController::class, 'delete'])->name('compare.delete')->middleware(['isDemo']);
            });

            // user addresses (frontend)
            Route::group(['prefix' => 'addresses'], function () {
                Route::get('/', [\App\Http\Controllers\Frontend\AddressController::class, 'index'])->name('addresses.index');
                Route::post('/', [\App\Http\Controllers\Frontend\AddressController::class, 'store'])->name('addresses.store');
                Route::put('/{address}', [\App\Http\Controllers\Frontend\AddressController::class, 'update'])->name('addresses.update');
                Route::delete('/{address}', [\App\Http\Controllers\Frontend\AddressController::class, 'destroy'])->name('addresses.destroy');
                Route::post('/{address}/default', [\App\Http\Controllers\Frontend\AddressController::class, 'setDefault'])->name('addresses.setDefault');
            });
        });
        Route::get('compare/add', [CompareListController::class, 'add'])->name('compare.add')->middleware(['isDemo']);
        Route::get('wishlist/add', [WishlistController::class, 'add'])->name('wishlist.add')->middleware(['isDemo']);
    });

    Route::group(['prefix' => 'cart'], function () {
        Route::post('add', [CartController::class, 'addToCart'])->name('add.to.cart');
        Route::get('/content', [CartController::class, 'cartContent'])->name('cart.content');
        Route::get('/delete', [CartController::class, 'cartDelete'])->name('cart.delete');
        Route::get('/decrease', [CartController::class, 'cartDecrease'])->name('cart.decrease');
        Route::get('/increase', [CartController::class, 'cartIncrease'])->name('cart.increase');
    });
    Route::post('buy-now', [BuyNowController::class, 'buyNow'])->name('buy.now');

    Route::group(['prefix' => 'product'], function () {
        Route::get('single/{slug}', [ProductController::class, 'singleProduct'])->name('single.product');
        // New-design product detail preview route (keeps old controller logic but returns new blade)
        Route::get('single-new/{slug}', [ProductController::class, 'singleProductNewDesign'])->name('single.product.new');
        Route::get('all', [ProductController::class, 'allProduct'])->name('all.product');
        Route::get('all/left-sidebar', [ProductController::class, 'productListLeftSidebar'])->name('product.list.left.sidebar');
        Route::get('shorting', [ProductController::class, 'productSorting'])->name('product.shorting');
        Route::get('filter', [ProductController::class, 'productFiltering'])->name('product.filtering');
        Route::get('left-shorting', [ProductController::class, 'productSortingLeftSide'])->name('product.shorting.left.side');
        Route::get('filter/left-side', [ProductController::class, 'productFilteringLeftSide'])->name('product.filtering.left.side');
        Route::get('category/{id?}', [ProductController::class, 'CategoryWiseProduct'])->name('category.product');
        Route::get('category/left/{id}', [ProductController::class, 'CategoryWiseProductLeft'])->name('category.product_left');
        Route::get('brand/{id}', [ProductController::class, 'BrandWiseProduct'])->name('brand.product');
        Route::get('brand/left/{id}', [ProductController::class, 'BrandWiseProductLeft'])->name('brand.product_left');
        // product reviews
        Route::post('{product}/review', [ProductController::class, 'storeReview'])->name('product.review.store');
    });

    Route::get('terms/conditions', [ServiceCustomerController::class, 'termsConditionsNewDesign'])->name('terms.conditions');
    // Route::get('privacy/policy', [ServiceCustomerController::class, 'privacyPolicy'])->name('privacy.policy');
    // New-design versions (dynamic content managed from admin customer services)
    Route::get('terms/conditions-new', [ServiceCustomerController::class, 'termsConditionsNewDesign'])->name('terms.conditions.new');
    Route::get('privacy/policy', [ServiceCustomerController::class, 'privacyPolicyNewDesign'])->name('privacy.policy');
    Route::get('privacy/policy-new', [ServiceCustomerController::class, 'privacyPolicyNewDesign'])->name('privacy.policy.new');
    Route::get('shipping/return', [ServiceCustomerController::class, 'shippingReturn'])->name('shipping.return');
    // New-design Shipping & Return page
    Route::get('shipping/return-new', [ServiceCustomerController::class, 'shippingReturnNewDesign'])->name('shipping.return.new');
    Route::get('faq', [ServiceCustomerController::class, 'Faq'])->name('faq');
    Route::get('refund/policy', [ServiceCustomerController::class, 'refundPolicy'])->name('refund.policy');

    Route::group(['prefix' => 'category'], function () {
        Route::get('search', [ProductController::class, 'CategorySearchProduct'])->name('category.search');
    });

    Route::group(['prefix' => 'checkout'], function () {
        Route::get('/{buyFor?}', [CheckoutController::class, 'checkoutPage'])->name('checkout');
        Route::post('order', [CheckoutController::class, 'checkoutOrder'])->name('checkout.order');
        Route::post('guest-order', [CheckoutController::class, 'guestCheckoutOrder'])->name('guest.checkout.order');
        Route::post('get-tax-amount', [CheckoutController::class, 'getTaxAmount'])->name('checkout.get_tax_amount');
        Route::get('thank-you', [CheckoutController::class, 'thankyouPage'])->name('checkout.thankyou_page');
    });
    Route::group(['prefix' => 'coupon'], function () {
        Route::post('apply', [CouponController::class, 'couponApply'])->name('apply.coupon');
    });

    Route::get('/page/{slug}', [PageController::class, 'singlePage'])->name('page.single');
    Route::get('/page/{slug}', [PageController::class, 'singlePage'])->name('page.single');
    Route::post('/order-track', [CheckoutController::class, 'orderTrack'])->name('checkout.order_track');

    // Subscription Payment Routes
    Route::post('/subscription/pay', [App\Http\Controllers\Frontend\SubscriptionPaymentController::class, 'initiatePayment'])->name('user.subscription.pay');
    Route::get('/subscription/callback', [App\Http\Controllers\Frontend\SubscriptionPaymentController::class, 'paymentCallback'])->name('user.subscription.callback');
});

Route::match(array('GET', 'POST'), '/payment-notify/{id}', [PaymentApiController::class, 'paymentNotifier'])->name('paymentNotify');
Route::match(array('GET', 'POST'), 'payment-cancel/{id}', [PaymentApiController::class, 'paymentCancel'])->name('paymentCancel');

// Payment gateway callback routes (user redirect after payment)
Route::get('/payment/callback/success', [\App\Http\Controllers\PaymentCallbackController::class, 'success'])->name('payment.callback.success');
Route::get('/payment/callback/cancel', [\App\Http\Controllers\PaymentCallbackController::class, 'cancel'])->name('payment.callback.cancel');

// Thawani payment webhook (server-to-server notification from Thawani gateway)
Route::post('/payment/webhook/thawani', [\App\Http\Controllers\ThawaniWebhookController::class, 'handle'])->name('thawani.webhook');

// SSLCOMMERZ Start
Route::post('/success', [SslCommerzPaymentController::class, 'success']);
Route::post('/fail', [SslCommerzPaymentController::class, 'fail']);
Route::post('/cancel', [SslCommerzPaymentController::class, 'cancel']);
Route::post('/ipn', [SslCommerzPaymentController::class, 'ipn']);
//SSLCOMMERZ END


// Thawani pay
Route::get("/thawani-success", [CheckoutController::class, "paymentSuccess"])->name("thawani.success");
Route::get("/thawani-cancel", [CheckoutController::class, "paymentCancel"])->name("thawani.cancel");
// In web.php

Route::get("/get-cities-by-state/{state_id}", [CityController::class, "getCitiesByState"]);
Route::get("/get-city-charge/{city_id}", [CityController::class, "getCityCharge"]);
Route::get("/get-area-charge/{area_id}", [CityController::class, "getAreaCharge"]);
Route::get("/get-areas-by-city/{city_id}", [CityController::class, "getAreasByCity"]);

Route::get('/search/suggest', [ProductController::class, 'autoSuggest'])->name('search.suggest');


// Category listing (simple frontend page)
Route::get('/categories/{slug?}', [CategoryController::class, 'show'])->name('categories.show');

Route::get("find-test-order", function () {
    $products = Product::all();
    foreach ($products as $product) {
        $product->Discount_Price = $product->Price;
        $product->Discount = 0;
        $product->save();
    }
    return "oky";
});

Route::get('/debug-api-v4', function () {
    return "Route V4 is Working. Time: " . date('H:i:s');
});



// Debug ERP Sync Route
Route::get('/debug-erp/{order_number}', function ($order_number) {
    if (!auth()->check() || !auth()->user()->is_admin) {
        // Simple auth check for safety, though it's debug
        // return "Admin login required";
    }

    $order = Order::where('Order_Number', $order_number)->with('order_details')->first();
    if (!$order) {
        return "Order number {$order_number} not found.";
    }

    $logs = [];
    $logs[] = "Found Order ID: {$order->id}, Number: {$order->Order_Number}, Status: {$order->Payment_Status}";

    try {
        $erp = new \App\Services\SmartLifeErpService();
        $logs[] = "Service instantiated.";

        if (!$erp->testConnection()) {
            return response()->json(['error' => 'Connection to ERP failed', 'logs' => $logs]);
        }
        $logs[] = "Connection successful.";

        $products = [];
        $logs[] = "Processing " . count($order->order_details) . " items...";

        foreach ($order->order_details as $detail) {
            $logItem = "Item: {$detail->Product_Name} (Qty: {$detail->Quantity}) - ";

            $smartLifeId = null;
            $barcode = null;

            // 1. Direct ID
            $product = \App\Models\Admin\Product::find($detail->Product_Id);
            if ($product) {
                $logItem .= "Local Product Found (ID: {$product->id}, Barcode: {$product->barcode}). ";
                if ($product->smartlife_id) {
                    $smartLifeId = $product->smartlife_id;
                    $logItem .= "Mapped via smartlife_id ($smartLifeId). ";
                } elseif ($product->barcode) {
                    $barcode = $product->barcode;
                    $shadow = \App\Models\SmartLifeProduct::where('barcode', $barcode)->first();
                    if ($shadow) {
                        $smartLifeId = $shadow->smartlife_id;
                        $logItem .= "Mapped via Barcode Shadow Table ($smartLifeId). ";
                    } else {
                        $logItem .= "Barcode OK but NOT in Shadow Table. ";
                    }
                } else {
                    $logItem .= "No Barcode/ID on Local Product. ";
                }
            } else {
                $logItem .= "Local Product Deleted/Missing. ";
            }

            // 2. Fallback Name
            if (!$smartLifeId) {
                $logItem .= "Trying Name Fallback... ";
                $smartLifeProduct = \App\Models\SmartLifeProduct::where('name', 'LIKE', '%' . $detail->Product_Name . '%')->first();
                if ($smartLifeProduct) {
                    $smartLifeId = $smartLifeProduct->smartlife_id;
                    $barcode = $smartLifeProduct->barcode ?? $barcode;
                    $logItem .= "FOUND via Name Match ($smartLifeId). ";
                } else {
                    $logItem .= "Name Match Failed. ";
                }
            }

            if ($smartLifeId) {
                $products[] = [
                    'id' => $smartLifeId,
                    'barcode' => $barcode ?? '0000',
                    'name' => $detail->Product_Name,
                    'price' => (float) $detail->Price,
                    'quantity' => (int) $detail->Quantity,
                ];
                $logs[] = $logItem . " -> ADDED TO PAYLOAD.";
            } else {
                $logs[] = $logItem . " -> SKIPPED (No valid mapping).";
            }
        }

        // Check Warehouses
        try {
            $token = $erp->getAccessToken();
            $apiUrl = config('smartlife.api_url');
            $warehouseRes = Http::withHeaders(['Authorization' => $token])->get("{$apiUrl}/warehouses");

            if ($warehouseRes->successful()) {
                $logs[] = "Warehouses Found: " . json_encode($warehouseRes->json()['data'] ?? 'No data key');
            } else {
                $logs[] = "Check Warehouses Failed: " . $warehouseRes->status() . " (Endpoint might be different)";
            }
        } catch (\Exception $e) {
            $logs[] = "Warehouse Check Exception: " . $e->getMessage();
        }

        if (request()->has('submit') && request()->get('submit') == 'true') {
            if (!empty($products)) {
                $logs[] = "Submitting to ERP...";

                $customerId = $order->user ? $order->user->smartlife_customer_id : 6;
                $saleDetails = [
                    'order_reference' => $order->Order_Number,
                    'notes' => 'Debug Sync ' . $order->Order_Number,
                    'status' => 'final',
                    'payment_status' => 'paid',
                ];

                // Add warehouse_id if passed in URL
                if (request()->has('warehouse_id')) {
                    $saleDetails['warehouse_id'] = request()->get('warehouse_id');
                }

                $result = $erp->addSale($products, $customerId, $saleDetails);
                $logs[] = "Submission Result: " . json_encode($result);
            } else {
                $logs[] = "No products to submit.";
            }
        } else {
            $logs[] = "Dry Run. Add ?submit=true to sync. Add &warehouse_id=X to specify warehouse.";
        }

        return response()->json(['success' => true, 'payload_preview' => $products, 'logs' => $logs]);

    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
    }
});
