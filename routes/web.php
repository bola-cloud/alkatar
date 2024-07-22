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
use App\Models\Admin\Category;
use App\Models\Admin\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use League\Csv\Reader;


//Route::redirect('/', '');
Route::post('currency-price', [CartController::class, 'currencyPrice'])->name('currency_price');
Route::get('currency-symbol', [CartController::class, 'currencySymbol'])->name('currency_symbol');
Route::group(['middleware' => ['is_user']], function () {
    Route::get('/', [HomeController::class, 'index'])->name('front');
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
    });

    Route::get('terms/conditions', [ServiceCustomerController::class, 'termsConditions'])->name('terms.conditions');
    Route::get('privacy/policy', [ServiceCustomerController::class, 'privacyPolicy'])->name('privacy.policy');
    Route::get('shipping/return', [ServiceCustomerController::class, 'shippingReturn'])->name('shipping.return');
    Route::get('faq', [ServiceCustomerController::class, 'Faq'])->name('faq');
    Route::get('refund/policy', [ServiceCustomerController::class, 'refundPolicy'])->name('refund.policy');

    Route::group(['prefix' => 'category'], function () {
        Route::get('search', [ProductController::class, 'CategorySearchProduct'])->name('category.search');
    });

    Route::group(['prefix' => 'checkout'], function () {
        Route::get('', [CheckoutController::class, 'checkoutPage'])->name('checkout');
        Route::post('order', [CheckoutController::class, 'checkoutOrder'])->name('checkout.order');
        Route::post('guest-order', [CheckoutController::class, 'guestCheckoutOrder'])->name('guest.checkout.order');
        Route::post('get-tax-amount', [CheckoutController::class, 'getTaxAmount'])->name('checkout.get_tax_amount');
        Route::get('thank-you', [CheckoutController::class, 'thankyouPage'])->name('checkout.thankyou_page');
    });
    Route::group(['prefix' => 'coupon'], function () {
        Route::post('apply', [CouponController::class, 'couponApply'])->name('apply.coupon');
    });

    Route::get('/page/{slug}', [PageController::class, 'singlePage'])->name('page.single');
    Route::post('/order-track', [CheckoutController::class, 'orderTrack'])->name('checkout.order_track');
});

Route::match(array('GET', 'POST'), '/payment-notify/{id}', [PaymentApiController::class, 'paymentNotifier'])->name('paymentNotify');
Route::match(array('GET', 'POST'), 'payment-cancel/{id}', [PaymentApiController::class, 'paymentCancel'])->name('paymentCancel');

// SSLCOMMERZ Start
Route::post('/success', [SslCommerzPaymentController::class, 'success']);
Route::post('/fail', [SslCommerzPaymentController::class, 'fail']);
Route::post('/cancel', [SslCommerzPaymentController::class, 'cancel']);
Route::post('/ipn', [SslCommerzPaymentController::class, 'ipn']);
//SSLCOMMERZ END


// Thawani pay
Route::get("/thawani-success", [CheckoutController::class, "paymentSuccess"])->name("thawani.success");
// In web.php

Route::get("/get-cities-by-state/{state_id}", [CityController::class, "getCitiesByState"]);



Route::get('/sync-products', function () {
    ini_set('max_execution_time', 1800); // 10 minutes

    $csv = Reader::createFromPath(storage_path('products.csv'), 'r');
    $csv->setHeaderOffset(0);
    $records = $csv->getRecords();

    $processed = 0;
    $errors = [];
    $productData = [];
    $categoryCache = [];

    foreach ($records as $record) {
        $productId = $record['Product ID'];
        $language = strtolower($record['Language']);

        // Map Arabic ('ar') to French ('fr') for internal processing
        if ($language === 'ar') {
            $language = 'fr';
        }

        if (!isset($productData[$productId])) {
            $productData[$productId] = [
                'en' => [],
                'fr' => [],
                'shared' => [
                    'Price' => $record['Price'],
                    'Discount_Price' => $record['Price'],
                    'Quantity' => $record['Quantity'],
                    'Primary_Image' => $record['Main Image'],
                    // 'Status' => $record['Status'],
                    'Status' => 1,
                    'Brand_Id' => null,
                ]
            ];
        }

        $productData[$productId][$language] = [
            $language . '_Product_Name' => $record['Name'],
            $language . '_Product_Slug' => $record['Slug'],
            $language . '_About' => $record['Description'],
            $language . '_Description' => $record['Description'],
            $language . '_ShippingReturn' => '',
            $language . '_AdditionalInformation' => '',
            'Voucher' => ''
        ];

        if ($language === 'en' || $language === 'fr') {
            $categoryPath = explode(' > ', $record['Categories']);
            $categoryName = end($categoryPath);
            $productData[$productId]['shared']['Category_Name'][$language] = trim($categoryName);
        }
    }

    // Default French data to English if French is missing
    foreach ($productData as $productId => $data) {
        if (empty($data['fr']) && !empty($data['en'])) {
            foreach ($data['en'] as $key => $value) {
                $frKey = str_replace('en_', 'fr_', $key);
                $productData[$productId]['fr'][$frKey] = $value;
            }
        }
    }

    DB::beginTransaction();

    foreach ($productData as $productId => $data) {
        try {
            $product = Product::firstOrNew(['id' => $productId]);
            $productAttributes = array_merge($data['en'], $data['fr'], $data['shared']);
            unset($productAttributes['Category_Name']);

            foreach ($productAttributes as $key => $value) {
                $product->{$key} = $value;
            }

            if (!empty($product->Primary_Image)) {
                $contents = Http::get($product->Primary_Image)->body();
                $filename = basename($product->Primary_Image);
                $path = substr($filename, 0, 10) . '.png';
                file_put_contents(public_path('/uploaded_files/product_image/' . $path), $contents);
                $product->Primary_Image = $path;
            }

            $product->save();

            // Create and attach category with names in both languages
            if (!empty($data['shared']['Category_Name'])) {
                $enCategoryName = $data['shared']['Category_Name']['en'] ?? null;
                $frCategoryName = $data['shared']['Category_Name']['fr'] ?? $enCategoryName; // Use English name if French name is missing

                $categoryKey = $enCategoryName . '|' . $frCategoryName;
                if (!isset($categoryCache[$categoryKey])) {
                    $category = Category::firstOrCreate([
                        'en_Category_Name' => $enCategoryName,
                        'fr_Category_Name' => $frCategoryName,
                        'en_Category_Slug' => Str::slug($enCategoryName),
                        'fr_Category_Slug' => Str::slug($frCategoryName),
                        'Status' => 1
                    ]);
                    $categoryCache[$categoryKey] = $category->id;
                }

                $product->Category_Id = $categoryCache[$categoryKey];
                $product->save();
            }

            // Attaching default size
            $defaultSizeId = 1; // Assuming '1' is the ID of the default size
            $defaultPrice = $data['shared']['Price']; // Use the product's price for this size
            $product->sizes()->sync([$defaultSizeId => ['price' => $defaultPrice]], false);


            $processed++;
            DB::commit();
        } catch (\Exception $e) {
            $errors[] = "Error processing product ID {$productId}: " . $e->getMessage();
            DB::rollBack();
        }
    }

    return response()->json([
        'processed' => $processed,
        'errors' => $errors
    ]);
});
