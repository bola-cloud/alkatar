# Thawani Payment & Subscription Discount Integration Report

**Date:** December 15, 2025  
**Project:** Final Sharaa E-commerce Platform  
**Payment Gateway:** Thawani (Oman)  
**ERP System:** SmartLife ERP

---

## 1. Currency Confirmation: Omani Baisa

### ✅ **CONFIRMED: Thawani uses Omani Baisa (not OMR directly)**

**Conversion Rule:**  
- **1 OMR = 1000 Baisa**
- All amounts must be **multiplied by 1000** before sending to Thawani API

**Implementation Evidence:**
```php
// From CheckoutController.php lines 453, 462, etc.
'unit_amount' => number_format($newUnitAmount, 3) * 1000,  // Convert OMR to Baisa
'unit_amount' => number_format($shipping_charge, 3) * 1000, // Shipping in Baisa
```

**Files Using Baisa Conversion:**
1. `app/Http/Controllers/Frontend/CheckoutController.php` (Lines 445-462)
2. `app/Http/Controllers/Admin/OrderController.php` (Lines 317, 325)
3. `app/Http/Controllers/Api/CheckoutController.php` (Lines 199, 208, 217, 258)

---

## 2. Subscription System Review

### Subscription Model Features
**Location:** `app/Models/Subscription.php`

**Subscription Benefits:**
- `discount_percent` - Percentage discount (e.g., 10 = 10% off)
- `max_discount_amount` - Maximum discount cap in OMR
- `free_shipping` - Boolean flag for free delivery
- `tax_exempt` - Boolean flag for tax exemption
- `period_type` - Subscription duration type (monthly/yearly)
- `period_value` - Subscription duration value

### ✅ Subscription Discount Implementation

**Location:** `app/Http/Controllers/Frontend/CheckoutController.php` (Lines 283-315)

```php
// Check for active user subscription
if (Auth::check()) {
    $activeSubscription = \App\Models\UserSubscription::where('user_id', Auth::id())
        ->where('status', 'active')
        ->where('end_date', '>=', now())
        ->first();

    if ($activeSubscription && $activeSubscription->subscription->discount_percent > 0) {
        $subscriptionDiscountPercent = $activeSubscription->subscription->discount_percent;
        $maxDiscountAmount = $activeSubscription->subscription->max_discount_amount ?? PHP_INT_MAX;
        
        $calculatedDiscount = ($subscriptionDiscountPercent / 100) * $subtotal;
        $subscriptionDiscount = min($calculatedDiscount, $maxDiscountAmount);
        
        $discountAmount += $subscriptionDiscount;
        
        // Store in session for tracking
        session()->put('subscription_discount_percent', $subscriptionDiscountPercent);
        session()->put('subscription_discount_amount', $subscriptionDiscount);
    }
}
```

### ✅ Grand Total Calculation (With All Discounts)

**Formula:**
```php
$grand_total = $subtotal + $shipping_charge - $discountAmount
```

Where `$discountAmount` includes:
1. **Coupon discount** (if applied)
2. **Subscription discount** (if active)
3. **Promotional discount** (from `$this->discount`)

---

## 3. SmartLife ERP Integration

### ❌ **ISSUE IDENTIFIED: Discounts NOT sent to SmartLife**

**Current Implementation:** `app/Services/SmartLifeErpService.php`

The `addSale()` method **only sends products** without discount information:

```php
public function addSale(array $products, $customerId = null)
{
    $payload = [
        'customer_id' => $customerId ?? $this->customerId,
        'products' => $products  // ❌ No discount field
    ];
    
    $response = Http::withHeaders([
        'Authorization' => $token,
    ])->post("{$this->apiUrl}/sales/add", $payload);
}
```

**Products Format:**
```php
$products[] = [
    'id' => $smartLifeProduct->id,
    'barcode' => $smartLifeProduct->barcode,
    'name' => $orderDetail->Product_Name,
    'price' => (float) $orderDetail->Price,  // ❌ Full price, not discounted
    'quantity' => (int) $orderDetail->Quantity,
];
```

### 🔧 **REQUIRED FIX:** Add Discount to SmartLife Sale

**SmartLife API Requirements** (verify with API documentation):
- Does SmartLife API support `discount_amount` or `discount_percent` in sale payload?
- Should discount be applied per-product or as a sale-level discount?

**Recommended Enhancement:**

```php
public function addSale(array $products, $customerId = null, array $discounts = [])
{
    $payload = [
        'customer_id' => $customerId ?? $this->customerId,
        'products' => $products,
        'discount_amount' => $discounts['total_discount'] ?? 0,
        'discount_type' => $discounts['type'] ?? 'fixed', // or 'percentage'
        'coupon_code' => $discounts['coupon_code'] ?? null,
        'subscription_discount' => $discounts['subscription_discount'] ?? 0,
    ];
    
    // If SmartLife doesn't support sale-level discount, apply per-product:
    // foreach ($products as &$product) {
    //     $product['discounted_price'] = $product['price'] - ($product['price'] * $discountPercent / 100);
    // }
    
    $response = Http::withHeaders([
        'Authorization' => $token,
    ])->post("{$this->apiUrl}/sales/add", $payload);
}
```

---

## 4. New Design Checkout Integration

### ✅ Checkout Blade: `checkout_newdesign.blade.php`

**Location:** `resources/views/front/pages/checkout/checkout_newdesign.blade.php`

**Payment Methods Displayed:**
1. **Credit Card (Thawani Gateway)** - Radio button `value="paypal"` (Line 143)
2. **Cash on Delivery (COD)** - Radio button `value="COD"` (Line 152)

**Form Submission:**
```blade
<form method="post" action="{{ route('checkout.order') }}" id="paymentForm">
    @csrf
    <!-- Billing fields -->
    <button type="submit" class="btn btn-primary w-100">{{ __('Place Order') }}</button>
</form>
```

**Cart Summary Shows:**
- Subtotal
- Shipping Cost
- VAT/Tax
- Coupon Discount (if applied)
- **❌ Subscription Discount NOT displayed**
- Total Cost

### ❌ **ISSUE: Subscription Discount Not Displayed in Checkout Summary**

**Required Fix:** Add subscription discount display in checkout blade.

---

## 5. Thawani Payment Flow

### ✅ Complete Flow Implementation

**Step 1: User Submits Checkout**
- Route: `POST /checkout/order`
- Controller: `CheckoutController@checkoutOrder`

**Step 2: Calculate Grand Total (with subscription discount)**
```php
// Lines 283-330 in CheckoutController
$discountAmount = 0;

// Add coupon discount
if ($couponAmount) {
    $discountAmount += $couponAmount;
}

// Add subscription discount
if ($activeSubscription) {
    $subscriptionDiscount = min($calculatedDiscount, $maxDiscountAmount);
    $discountAmount += $subscriptionDiscount;
}

$grand_total = $subtotal + $shipping_charge - $discountAmount;
```

**Step 3: Create Thawani Checkout Session**
```php
// Lines 440-463
$checkoutProduct = [];
foreach ($cartItems as $item) {
    $checkoutProduct[] = [
        'name' => Str::limit($item->name, 35),
        'quantity' => $item->qty,
        'unit_amount' => number_format($newUnitAmount, 3) * 1000,  // ✅ Baisa conversion
    ];
}

if ($shipping_charge) {
    $checkoutProduct[] = [
        'name' => 'Shipping Charge',
        'quantity' => 1,
        'unit_amount' => number_format($shipping_charge, 3) * 1000,  // ✅ Baisa conversion
    ];
}

$response = Http::withHeaders([
    'thawani-api-key' => env('THAWANI_TEST_SECRET_KEY'),
])->post(env('THAWANI_TEST_CHECKOUT_URL') . '/checkout/session', [
    'client_reference_id' => $order_number,
    'mode' => 'payment',
    'products' => $checkoutProduct,  // ✅ Includes discounted prices in Baisa
    'success_url' => route('thawani.success', ['order_number' => $order_number]),
    'cancel_url' => route('thawani.cancel', ['order_number' => $order_number]),
    'metadata' => [
        'order_number' => $order_number,
        'shipping_charge' => $shipping_charge,
        'subtotal' => $subtotal,
        'discount' => $this->discount,  // ✅ Total discount percentage
        'grand_total' => $this->grand_total,  // ✅ Final amount after all discounts
        'tax' => $tax,
    ]
]);
```

**Step 4: Redirect to Thawani Payment Page**
```php
$paymentUrl = env('THAWANI_TEST_PAY_URL') . $session_id . '?key=' . env("THAWANI_TEST_PUBLIC_KEY");
// User is redirected to Thawani checkout
```

**Step 5: Payment Confirmation**
- **Webhook:** `POST /payment/webhook/thawani` (server-to-server)
- **Callback:** `GET /thawani-success` (user redirect)

### ✅ Webhook Controller: `ThawaniWebhookController.php`

**Features:**
- Signature verification (optional)
- Idempotency check (prevents double-processing)
- Stock decrement after payment success
- SmartLife ERP sync (calls `submitOrder`)
- Email confirmation

---

## 6. Required Configuration

### Environment Variables (.env)

```ini
# Thawani Payment Gateway (Oman)
# Note: Thawani uses Omani Baisa (1 OMR = 1000 Baisa)
THAWANI_TEST_SECRET_KEY=rRQ26GcsZzoEhbrP2HZvLYDbn9C9et
THAWANI_TEST_PUBLIC_KEY=HGvTMLDssJghr9tlN9gr4DVYt0qyBy
THAWANI_TEST_CHECKOUT_URL=https://uatcheckout.thawani.om/api/v1
THAWANI_TEST_PAY_URL=https://uatcheckout.thawani.om/pay/

# Production (when ready)
THAWANI_PROD_SECRET_KEY=your_production_secret_key
THAWANI_PROD_PUBLIC_KEY=your_production_public_key
THAWANI_PROD_CHECKOUT_URL=https://checkout.thawani.om/api/v1
THAWANI_PROD_PAY_URL=https://checkout.thawani.om/pay/

# Webhook secret (if Thawani provides signature verification)
THAWANI_WEBHOOK_SECRET=your_webhook_secret
```

### Webhook URL Configuration

**Register in Thawani Dashboard:**
```
Production Webhook URL: https://yourdomain.com/payment/webhook/thawani
Test Webhook URL: https://test.yourdomain.com/payment/webhook/thawani
```

**Route:** `routes/web.php`
```php
Route::post('/payment/webhook/thawani', 
    [\App\Http\Controllers\ThawaniWebhookController::class, 'handle'])
    ->name('thawani.webhook');
```

---

## 7. Issues & Recommendations

### ✅ **WORKING CORRECTLY:**
1. ✅ Thawani uses Baisa (all amounts multiplied by 1000)
2. ✅ Subscription discount calculated in checkout
3. ✅ Grand total includes all discounts (coupon + subscription)
4. ✅ Discounted prices sent to Thawani payment page
5. ✅ New design checkout form submits correctly
6. ✅ Webhook controller handles payment confirmation

### ❌ **ISSUES TO FIX:**

#### **Issue 1: Subscription Discount Not Displayed in UI**
**File:** `resources/views/front/pages/checkout/checkout_newdesign.blade.php`  
**Problem:** Cart summary shows coupon discount but not subscription discount

**Fix Required:** Add subscription discount display:
```blade
@if (!empty(Session::get('subscription_discount_amount')))
    <div class="d-flex justify-content-between text-success">
        <span>{{ __('Subscription Discount (-)') }}</span>
        <span>{{ currencyConverter(Session::get('subscription_discount_amount')) }}</span>
    </div>
@endif
```

#### **Issue 2: SmartLife Does NOT Receive Discount Information**
**File:** `app/Services/SmartLifeErpService.php`  
**Problem:** `addSale()` method only sends products without discount details

**Action Required:**
1. **Check SmartLife API documentation** - Does it support discount fields?
2. **Update `addSale()` method** to include:
   - Total discount amount
   - Discount type (coupon/subscription/promotional)
   - Coupon code (if applicable)
3. **Alternative:** Apply discounted price per-product instead of full price

#### **Issue 3: Free Shipping from Subscription Not Applied**
**File:** `app/Http/Controllers/Frontend/CheckoutController.php`  
**Problem:** `free_shipping` flag from subscription model is not checked

**Fix Required:**
```php
if ($activeSubscription && $activeSubscription->subscription->free_shipping) {
    $shipping_charge = 0;
    session()->put('free_shipping_applied', true);
    \Log::info("Free shipping applied from subscription", [
        'user_id' => Auth::id(),
        'subscription_id' => $activeSubscription->subscription_id,
    ]);
}
```

---

## 8. Testing Checklist

### Before Go-Live Testing:

- [ ] **Test Subscription Discount**
  - [ ] Create active subscription with 10% discount
  - [ ] Add items to cart (subtotal = 100 OMR)
  - [ ] Verify checkout shows 10 OMR subscription discount
  - [ ] Verify grand total = 90 OMR + shipping
  
- [ ] **Test Thawani Payment (Test Mode)**
  - [ ] Complete checkout with subscription discount
  - [ ] Verify redirect to Thawani payment page
  - [ ] Verify payment amount in Baisa (90000 Baisa for 90 OMR)
  - [ ] Complete test payment
  - [ ] Verify webhook received and order marked as paid
  
- [ ] **Test Free Shipping Subscription**
  - [ ] Create subscription with `free_shipping = true`
  - [ ] Verify shipping cost = 0 at checkout
  
- [ ] **Test SmartLife Sync**
  - [ ] Complete order with subscription discount
  - [ ] Check SmartLife ERP - verify sale is created
  - [ ] **Verify if discount is recorded** (check with SmartLife team)
  
- [ ] **Test COD Payment**
  - [ ] Select Cash on Delivery
  - [ ] Verify order created immediately (no webhook)
  - [ ] Verify stock decremented
  - [ ] Verify SmartLife sync

---

## 9. Summary & Action Items

### ✅ **Confirmed Working:**
- Thawani currency = **Omani Baisa** (1 OMR = 1000 Baisa)
- Subscription discounts calculated correctly in backend
- Discounted grand total sent to Thawani
- Webhook integration for payment confirmation

### 🔧 **Action Items:**

1. **Update checkout UI** - Display subscription discount in cart summary
2. **Fix SmartLife integration** - Send discount information to ERP
3. **Implement free shipping** - Apply `free_shipping` flag from subscription
4. **Test end-to-end** - Complete payment flow with subscription
5. **Configure webhook URL** - Register in Thawani dashboard
6. **Monitor logs** - Check `storage/logs/laravel.log` for webhook events

---

## 10. Contact & Support

**Thawani Support:** https://thawani.om/docs  
**SmartLife ERP API:** Contact SmartLife technical team for discount field support  
**Laravel Logs:** `storage/logs/laravel.log`

---

*Report Generated: December 15, 2025*
