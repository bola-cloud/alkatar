# Payment Webhook & Soft Delete Implementation Guide

## Overview
This implementation adds two major features to the Laravel e-commerce system:

1. **Payment Webhook Flow** (similar to Thawani pattern) - Orders created as unpaid, confirmed via webhook
2. **Soft Delete Protection** - Products/categories with order history cannot be permanently deleted

---

## 1. Payment Webhook Implementation

### How It Works (Similar to Thawani Pattern)

#### Step 1: User Initiates Checkout
- User fills checkout form and submits order
- System creates order with `is_paid = false` (unpaid status)
- Generates unique `pending_token` for order identification
- Redirects user to payment gateway with order reference

#### Step 2: Payment Gateway Processing
- User completes payment on gateway (credit card, wallet, etc.)
- Payment gateway sends **webhook notification** to your server (server-to-server)
- Gateway redirects user back to your site with callback

#### Step 3: Webhook Confirms Payment
- **SmartLife Webhook Controller** receives payment notification
- Verifies payment status is "paid"
- Finds order by `pending_token` or `payment_session_id`
- Updates order: `is_paid = true`, `Payment_Status = PAYMENT_SUCCESS`
- Decrements product stock
- Syncs order to SmartLife ERP
- Sends confirmation email

#### Step 4: User Returns to Site
- **Payment Callback Controller** handles user redirect
- Checks if order already marked paid (by webhook)
- Shows success message and clears cart

### Database Schema Changes

**Migration:** `2025_12_15_120000_add_payment_webhook_fields_to_orders.php`

New fields in `orders` table:
```php
is_paid                 boolean    // Payment confirmed via webhook
pending_token           string     // Unique token for webhook identification
payment_session_id      string     // Payment gateway session/transaction ID
smartlife_invoice_id    string     // SmartLife ERP invoice ID (after sync)
smartlife_synced_at     timestamp  // When order was synced to SmartLife
```

### New Files Created

1. **`app/Http/Controllers/SmartLifeWebhookController.php`**
   - Handles webhook notifications from payment gateway
   - Validates webhook signature (security)
   - Updates order status to paid
   - Decrements stock
   - Syncs to SmartLife ERP
   - Sends confirmation email

2. **`app/Http/Controllers/PaymentCallbackController.php`**
   - Handles user redirect after payment
   - Shows success/cancel page
   - Clears cart on success

### Routes Added

```php
// User callback routes (where user lands after payment)
Route::get('/payment/callback/success', [PaymentCallbackController::class, 'success'])
    ->name('payment.callback.success');
Route::get('/payment/callback/cancel', [PaymentCallbackController::class, 'cancel'])
    ->name('payment.callback.cancel');

// Webhook route (server-to-server from payment gateway)
Route::post('/payment/webhook/smartlife', [SmartLifeWebhookController::class, 'handle'])
    ->name('smartlife.webhook');
```

### Configuration Required

Add to `.env`:
```env
SMARTLIFE_WEBHOOK_SECRET=your_webhook_secret_here
```

Add to `config/services.php`:
```php
'smartlife' => [
    'webhook_secret' => env('SMARTLIFE_WEBHOOK_SECRET'),
],
```

### Webhook URL for Payment Gateway
Configure this URL in your payment gateway dashboard:
```
https://yourdomain.com/payment/webhook/smartlife
```

### Webhook Payload Expected

The webhook should receive JSON like:
```json
{
  "event_type": "payment.completed",
  "payment_id": "PAY12345",
  "order_reference": "ORD-xxxxx",
  "payment_status": "paid",
  "amount": 100.00,
  "currency": "OMR",
  "transaction_id": "TXN-xxxxx"
}
```

### Security: Webhook Signature Verification

The webhook controller verifies signatures using HMAC:
```php
$signature = $request->header('X-SmartLife-Signature');
$payload = $request->getContent();
$expectedSignature = hash_hmac('sha256', $payload, $webhookSecret);

if (!hash_equals($expectedSignature, $signature)) {
    return response()->json(['error' => 'Invalid signature'], 401);
}
```

---

## 2. Soft Delete Implementation

### Why Soft Delete?

**Problem:** When products/categories are deleted from SmartLife ERP or locally, orders referencing them lose data integrity.

**Solution:** Soft delete - mark as deleted but keep in database for historical orders.

### Database Schema Changes

**Migration:** `2025_12_15_120001_add_soft_delete_to_products_categories.php`

New fields:
```php
// In products and categories tables
deleted_at      timestamp    // NULL = active, not NULL = soft deleted
deleted_reason  string       // Why deleted: 'smartlife_sync', 'manual', etc
```

### Model Changes

Updated `Product` and `Category` models to use `SoftDeletes` trait:
```php
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, Sluggable, SoftDeletes;
}
```

### Sync Command Updates

**File:** `app/Console/Commands/SyncSmartLifeProducts.php`

New method `handleDeletedItems()`:
- Compares local products with SmartLife active products
- Products not in SmartLife API = deleted from SmartLife
- Soft deletes them with reason: `smartlife_sync`
- Checks if product has orders:
  - **Has orders:** Soft delete (preserve for order history)
  - **No orders:** Soft delete (can be hard deleted later if needed)

Same logic for categories:
- Categories with no active products get soft deleted
- Only if they had products with order history

### Observer Pattern for Deletion Protection

**Files:** 
- `app/Observers/ProductObserver.php`
- `app/Observers/CategoryObserver.php`

**Registered in:** `app/Providers/AppServiceProvider.php`

**Behavior:**
- On **soft delete** (`$product->delete()`): Allowed, logs deletion
- On **hard delete** (`$product->forceDelete()`): 
  - Checks if product has orders
  - If yes: Throws exception, prevents deletion
  - If no: Allows hard deletion

### Usage Examples

#### Soft Delete Product (Admin UI)
```php
// This is safe - preserves order history
$product->delete();
```

#### Restore Deleted Product
```php
$product->restore();
```

#### Hard Delete (Only if No Orders)
```php
// This will throw exception if product has orders
try {
    $product->forceDelete();
} catch (\Exception $e) {
    // Cannot delete - has orders
}
```

#### Query Active Products (Exclude Soft Deleted)
```php
// Automatically excludes soft deleted
$products = Product::all();

// Include soft deleted
$products = Product::withTrashed()->get();

// Only soft deleted
$products = Product::onlyTrashed()->get();
```

---

## 3. SmartLife ERP Integration Flow

### Complete Order Flow

1. **User Registers**
   - Creates SmartLife customer via API
   - Saves `smartlife_customer_id` in `users` table

2. **User Adds to Cart**
   - Products synced from SmartLife have `smartlife_id`

3. **User Checks Out**
   - Order created with `is_paid = false`
   - `pending_token` generated
   - User redirected to payment gateway

4. **Payment Gateway Sends Webhook**
   - Order marked `is_paid = true`
   - Stock decremented
   - SmartLife `submitOrder()` called
   - Invoice ID saved: `smartlife_invoice_id`
   - Timestamp: `smartlife_synced_at`

5. **Product Sync (Cron Job)**
   ```bash
   php artisan smartlife:sync-products
   ```
   - Fetches all products from SmartLife
   - Updates/creates local products
   - Downloads images
   - Creates categories
   - **Handles deletions:** Soft deletes removed items

---

## 4. Running Migrations

Execute migrations in order:
```bash
# Add webhook fields to orders
php artisan migrate --path=database/migrations/2025_12_15_120000_add_payment_webhook_fields_to_orders.php

# Add soft delete to products/categories
php artisan migrate --path=database/migrations/2025_12_15_120001_add_soft_delete_to_products_categories.php
```

Or run all pending:
```bash
php artisan migrate
```

---

## 5. Testing Checklist

### Test Webhook Flow

1. **Create Test Order:**
   - Place order, should create with `is_paid = false`
   - Note the `pending_token`

2. **Simulate Webhook:**
   ```bash
   curl -X POST https://yourdomain.com/payment/webhook/smartlife \
     -H "Content-Type: application/json" \
     -H "X-SmartLife-Signature: your_signature" \
     -d '{
       "event_type": "payment.completed",
       "order_reference": "YOUR_PENDING_TOKEN",
       "payment_status": "paid",
       "amount": 100.00,
       "transaction_id": "TEST123"
     }'
   ```

3. **Verify:**
   - Order `is_paid` changed to `true`
   - Stock decremented
   - Confirmation email sent
   - Check logs: `storage/logs/laravel.log`

### Test Soft Delete

1. **Create Product with Order:**
   - Create product
   - Create order with that product

2. **Try to Hard Delete:**
   ```php
   $product->forceDelete(); // Should throw exception
   ```

3. **Soft Delete:**
   ```php
   $product->delete(); // Should succeed
   ```

4. **Verify:**
   - Product still in database
   - `deleted_at` is set
   - Orders still show product details

5. **Restore:**
   ```php
   $product->restore(); // Should succeed
   ```

### Test SmartLife Sync Deletion

1. **Run Sync:**
   ```bash
   php artisan smartlife:sync-products
   ```

2. **Delete Product from SmartLife ERP**

3. **Run Sync Again:**
   ```bash
   php artisan smartlife:sync-products
   ```

4. **Verify:**
   - Product soft deleted locally
   - `deleted_reason = 'smartlife_sync'`
   - Orders still intact

---

## 6. Monitoring & Logs

All webhook and deletion operations are logged:
```bash
tail -f storage/logs/laravel.log | grep -E "(WEBHOOK|soft-deleted|hard-deleted)"
```

Log entries include:
- Webhook received with full payload
- Order payment confirmation
- Stock decrements
- Soft delete operations
- Hard delete attempts (blocked or allowed)

---

## 7. Benefits of This Implementation

### Payment Webhook Benefits:
✅ **Reliable:** Payment confirmed by gateway, not user
✅ **Secure:** Signature verification prevents fake webhooks
✅ **Idempotent:** Duplicate webhooks handled gracefully
✅ **Async:** Stock/email processing doesn't block payment
✅ **Audit Trail:** Full payment flow logged

### Soft Delete Benefits:
✅ **Data Integrity:** Orders always show correct product details
✅ **Reporting:** Historical sales data preserved
✅ **Compliance:** Order records required for tax/legal
✅ **Reversible:** Deleted items can be restored
✅ **Safe Sync:** SmartLife deletions don't break orders

---

## 8. Troubleshooting

### Webhook Not Receiving
- Check webhook URL in payment gateway dashboard
- Verify route registered: `php artisan route:list | grep webhook`
- Check firewall/server allows POST to webhook URL
- Test with curl locally first

### Order Not Updating to Paid
- Check logs for webhook errors
- Verify `pending_token` matches
- Ensure signature validation passes
- Check database for `is_paid` field exists

### Products Not Soft Deleting
- Run migrations: `php artisan migrate`
- Check observers registered in AppServiceProvider
- Verify SoftDeletes trait in models
- Check logs for deletion attempts

### Hard Delete Blocked
- This is intentional if product has orders
- Check `order_details` table for product references
- Use soft delete instead: `$product->delete()`

---

## 9. Future Enhancements

1. **Retry Failed SmartLife Syncs:**
   - Queue failed order syncs
   - Retry with exponential backoff

2. **Admin UI for Soft Deleted Items:**
   - View trashed products/categories
   - Restore or force delete
   - Filter by deletion reason

3. **Webhook Replay:**
   - Store webhook payloads in database
   - Replay failed webhooks manually

4. **Stock Reservation:**
   - Reserve stock on order create
   - Release if payment fails
   - Decrement only on payment success

---

## 10. Support

For issues or questions:
- Check logs: `storage/logs/laravel.log`
- Review migration status: `php artisan migrate:status`
- Test webhook endpoint: Use Postman or curl
- Verify SmartLife API connectivity: `php artisan smartlife:sync-products --limit=1`
