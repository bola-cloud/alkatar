# Testing Guide

## Prerequisites
1. Migrations run: `php artisan migrate`
2. Observers registered in `AppServiceProvider`
3. SmartLife credentials in `.env`

---

## Test 1: Webhook Payment Flow

### Step 1: Create a Test Order
```bash
# Start server
php artisan serve

# In browser, go to:
http://127.0.0.1:8000

# Add product to cart and proceed to checkout
# Fill form and submit
```

### Step 2: Check Order Created as Unpaid
```sql
SELECT id, Order_Number, is_paid, pending_token, Payment_Status 
FROM orders 
ORDER BY id DESC 
LIMIT 1;
```

Expected:
- `is_paid` = 0 (false)
- `Payment_Status` = 'pending' or similar
- `pending_token` = some UUID

### Step 3: Simulate Webhook
Copy the `pending_token` from above and use it in this curl command:

```bash
curl -X POST http://127.0.0.1:8000/payment/webhook/smartlife \
  -H "Content-Type: application/json" \
  -d '{
    "event_type": "payment.completed",
    "order_reference": "PASTE_PENDING_TOKEN_HERE",
    "payment_status": "paid",
    "amount": 50.00,
    "currency": "OMR",
    "transaction_id": "TEST_TXN_123"
  }'
```

Expected response:
```json
{
  "status": "success",
  "order_id": 123,
  "order_number": "ORD-xxxxx"
}
```

### Step 4: Verify Order Updated
```sql
SELECT id, Order_Number, is_paid, Payment_Status, txn 
FROM orders 
WHERE pending_token = 'YOUR_PENDING_TOKEN';
```

Expected:
- `is_paid` = 1 (true)
- `Payment_Status` = 'success' or PAYMENT_SUCCESS constant
- `txn` = 'TEST_TXN_123'

### Step 5: Check Logs
```bash
tail -50 storage/logs/laravel.log
```

Look for:
- "SMARTLIFE PAYMENT WEBHOOK RECEIVED"
- "Order found for webhook"
- "Stock decremented via webhook"
- "Order marked as paid successfully via webhook"

---

## Test 2: Soft Delete Protection

### Step 1: Find Product with Orders
```sql
SELECT p.id, p.en_Product_Name, COUNT(od.id) as order_count
FROM products p
INNER JOIN order_details od ON p.id = od.Product_Id
GROUP BY p.id
LIMIT 1;
```

Note the product ID.

### Step 2: Try Soft Delete (Should Succeed)
```bash
php artisan tinker
```

```php
$product = App\Models\Admin\Product::find(YOUR_PRODUCT_ID);
$product->delete(); // Soft delete
exit;
```

### Step 3: Verify Soft Deleted
```sql
SELECT id, en_Product_Name, deleted_at, deleted_reason
FROM products
WHERE id = YOUR_PRODUCT_ID;
```

Expected:
- `deleted_at` = current timestamp
- `deleted_reason` = 'manual' or NULL

### Step 4: Try Hard Delete (Should Fail)
```bash
php artisan tinker
```

```php
$product = App\Models\Admin\Product::withTrashed()->find(YOUR_PRODUCT_ID);
try {
    $product->forceDelete(); // Hard delete
    echo "ERROR: Should have been blocked!\n";
} catch (\Exception $e) {
    echo "SUCCESS: Blocked! Message: " . $e->getMessage() . "\n";
}
exit;
```

Expected output:
```
SUCCESS: Blocked! Message: Cannot permanently delete product...
```

### Step 5: Restore Product
```bash
php artisan tinker
```

```php
$product = App\Models\Admin\Product::withTrashed()->find(YOUR_PRODUCT_ID);
$product->restore();
echo "Product restored\n";
exit;
```

### Step 6: Verify Restored
```sql
SELECT id, en_Product_Name, deleted_at, deleted_reason
FROM products
WHERE id = YOUR_PRODUCT_ID;
```

Expected:
- `deleted_at` = NULL
- `deleted_reason` = NULL

---

## Test 3: SmartLife Sync with Deletions

### Step 1: Run Initial Sync
```bash
php artisan smartlife:sync-products --limit=20
```

Output should show:
```
Starting SmartLife product sync...
Connection successful. Fetching products...
Retrieved X products from SmartLife ERP.
[progress bar]
Checking for deleted items in SmartLife...
Products soft-deleted: 0
Categories soft-deleted: 0
Sync completed!
```

### Step 2: Note SmartLife Product IDs
```sql
SELECT id, smartlife_id, en_Product_Name, deleted_at
FROM products
WHERE smartlife_id IS NOT NULL
ORDER BY id DESC
LIMIT 5;
```

### Step 3: Manually "Delete" a Product (Simulate SmartLife Deletion)

For testing, manually soft-delete a product:
```bash
php artisan tinker
```

```php
$product = App\Models\Admin\Product::where('smartlife_id', 'YOUR_SMARTLIFE_ID')->first();
$product->deleted_reason = 'smartlife_sync';
$product->delete();
exit;
```

### Step 4: Run Sync Again
```bash
php artisan smartlife:sync-products --limit=20
```

If the product is still active in SmartLife, it should be:
- Detected as "not in SmartLife anymore"
- Soft deleted with `deleted_reason = 'smartlife_sync'`

### Step 5: Check Sync Logs
```bash
grep -E "(soft-deleted|smartlife_sync)" storage/logs/laravel.log
```

---

## Test 4: Query Soft Deleted Products

### Test Default Query (Excludes Deleted)
```bash
php artisan tinker
```

```php
// Default: only active products
$active = App\Models\Admin\Product::count();
echo "Active products: $active\n";

// Include soft deleted
$all = App\Models\Admin\Product::withTrashed()->count();
echo "Total (with deleted): $all\n";

// Only soft deleted
$deleted = App\Models\Admin\Product::onlyTrashed()->count();
echo "Soft deleted: $deleted\n";

exit;
```

### Test Orders Still Show Deleted Products
```bash
php artisan tinker
```

```php
// Find an order with a deleted product
$order = App\Models\Admin\Order::with('order_details')->first();

foreach ($order->order_details as $detail) {
    $product = App\Models\Admin\Product::withTrashed()->find($detail->Product_Id);
    echo "Product: " . $detail->Product_Name . " - ";
    echo ($product && $product->trashed() ? "DELETED" : "ACTIVE") . "\n";
}

exit;
```

---

## Test 5: Category Soft Delete

### Step 1: Create Category with Products
```bash
php artisan tinker
```

```php
$cat = App\Models\Admin\Category::create([
    'en_Category_Name' => 'Test Delete Category',
    'en_Category_Slug' => 'test-delete-category',
    'fr_Category_Name' => 'Test Delete Category',
    'fr_Category_Slug' => 'test-delete-category',
    'Status' => 1
]);

echo "Category created: ID " . $cat->id . "\n";
exit;
```

### Step 2: Assign Product to Category
```bash
php artisan tinker
```

```php
$product = App\Models\Admin\Product::first();
$product->Category_Id = YOUR_CATEGORY_ID;
$product->save();
echo "Product assigned to category\n";
exit;
```

### Step 3: Create Order with Product
(Use the website to create an order with this product)

### Step 4: Try to Delete Category (Should Work - Soft Delete)
```bash
php artisan tinker
```

```php
$cat = App\Models\Admin\Category::find(YOUR_CATEGORY_ID);
$cat->delete(); // Soft delete
echo "Category soft deleted\n";
exit;
```

### Step 5: Try Hard Delete (Should Fail if Products Have Orders)
```bash
php artisan tinker
```

```php
$cat = App\Models\Admin\Category::withTrashed()->find(YOUR_CATEGORY_ID);
try {
    $cat->forceDelete();
    echo "ERROR: Should have been blocked\n";
} catch (\Exception $e) {
    echo "SUCCESS: Blocked! " . $e->getMessage() . "\n";
}
exit;
```

---

## Test 6: End-to-End Order Flow

### Full Scenario:
1. User visits site
2. Adds product to cart
3. Proceeds to checkout
4. Fills billing/shipping info
5. Submits order
6. Payment gateway processes
7. **Webhook confirms payment**
8. User redirected to success page
9. Email sent
10. Stock decremented
11. Order synced to SmartLife

### Manual Steps:
1. **Open browser:** http://127.0.0.1:8000
2. **Add to cart:** Click "Add to Cart" on any product
3. **View cart:** Click cart icon
4. **Checkout:** Click "Proceed to Checkout"
5. **Fill form:** Enter billing details
6. **Submit:** Click "Place Order"

### Monitor Backend:
```bash
# Terminal 1: Laravel logs
tail -f storage/logs/laravel.log

# Terminal 2: Watch orders table
watch -n 1 'mysql -u root -D sharaa -e "SELECT id, Order_Number, is_paid, Payment_Status FROM orders ORDER BY id DESC LIMIT 3;"'
```

### Simulate Webhook:
After order created, run:
```bash
curl -X POST http://127.0.0.1:8000/payment/webhook/smartlife \
  -H "Content-Type: application/json" \
  -d '{
    "event_type": "payment.completed",
    "order_reference": "GET_FROM_DATABASE",
    "payment_status": "paid",
    "transaction_id": "TEST_123"
  }'
```

### Verify Results:
- ✅ Order `is_paid` = 1
- ✅ `Payment_Status` = success
- ✅ Product `Quantity` decremented
- ✅ Email sent (check logs)
- ✅ `smartlife_invoice_id` set (if SmartLife enabled)

---

## Test 7: Idempotency (Duplicate Webhooks)

### Send Same Webhook Twice:
```bash
# First webhook
curl -X POST http://127.0.0.1:8000/payment/webhook/smartlife \
  -H "Content-Type: application/json" \
  -d '{
    "order_reference": "SAME_TOKEN",
    "payment_status": "paid",
    "transaction_id": "TEST_123"
  }'

# Wait 2 seconds

# Second webhook (duplicate)
curl -X POST http://127.0.0.1:8000/payment/webhook/smartlife \
  -H "Content-Type: application/json" \
  -d '{
    "order_reference": "SAME_TOKEN",
    "payment_status": "paid",
    "transaction_id": "TEST_123"
  }'
```

### Check Logs:
```bash
grep "already marked as paid" storage/logs/laravel.log
```

Expected:
- First webhook: Order updated
- Second webhook: "already_processed" response
- Stock decremented **only once**

---

## Common Issues & Solutions

### Issue: Webhook 404 Not Found
```bash
# Check route exists
php artisan route:list | grep webhook

# Should show:
# POST  | payment/webhook/smartlife
```

### Issue: Observer Not Firing
```bash
# Check registered in AppServiceProvider
grep -A 5 "boot()" app/Providers/AppServiceProvider.php
```

### Issue: Soft Delete Not Working
```sql
-- Check column exists
DESCRIBE products;
-- Should have: deleted_at, deleted_reason

-- Check migration ran
SELECT * FROM migrations WHERE migration LIKE '%soft_delete%';
```

### Issue: Webhook Signature Fails
Add to `.env`:
```env
SMARTLIFE_WEBHOOK_SECRET=your_secret
```

Or disable verification temporarily (for testing):
```php
// In SmartLifeWebhookController.php
// Comment out signature verification
```

---

## Success Criteria

✅ **Webhook Flow:**
- Order created unpaid
- Webhook updates to paid
- Stock decremented
- Email sent

✅ **Soft Delete:**
- Products with orders cannot be hard deleted
- Soft delete works
- Restore works
- Orders still show deleted products

✅ **SmartLife Sync:**
- Products sync from SmartLife
- Deletions handled (soft delete)
- Categories managed
- Images downloaded

✅ **Protection:**
- Hard delete blocked if orders exist
- Logs show attempts
- Observers fire correctly

---

## Cleanup After Testing

```bash
# Reset test orders
php artisan tinker
```

```php
// Delete test orders
App\Models\Admin\Order::where('Payment_Method', 'test')->forceDelete();

// Restore soft-deleted products
App\Models\Admin\Product::onlyTrashed()->restore();

// Clear logs
file_put_contents(storage_path('logs/laravel.log'), '');

exit;
```
