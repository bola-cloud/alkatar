# SmartLife ERP Integration Setup Guide

## Overview
This document outlines the integration between your Laravel e-commerce application and SmartLife ERP system. The integration includes user synchronization, product syncing, and order submission.

## ⚠️ IMPORTANT: Product Data Matching

The local products table has been updated to match SmartLife ERP data structure:

### SmartLife → Laravel Field Mapping

| SmartLife Field | Laravel Field | Description |
|----------------|---------------|-------------|
| `id` | `smartlife_id` | SmartLife product unique ID |
| `barcode` | `barcode` | Product barcode (required) |
| `name` | `en_Product_Name`, `fr_Product_Name` | Product names |
| `price` | `Price` | Selling price (decimal 10,3) |
| `cost` | `cost` | Cost price (decimal 10,3) |
| `quantity` | `Quantity` | Stock quantity |
| `alert_quantity` | `alert_quantity` | Low stock alert threshold |
| `unit` | `unit` | Unit type (KG, PC, etc) |
| `type` | `product_type` | Standard, Combo |
| `image` | `Primary_Image` | Product image URL |
| `description` | `en_Description`, `fr_Description` | Product description |
| `show_pos` | `show_pos` | Show in POS system |

### New Product Table Columns

The migration adds these columns to your `products` table:

```php
$table->string('smartlife_id')->nullable()->unique();
$table->string('barcode')->nullable()->index();
$table->string('unit')->nullable(); // KG, PC
$table->decimal('cost', 10, 3)->nullable();
$table->integer('alert_quantity')->nullable();
$table->string('product_type')->nullable(); // Standard, Combo
$table->boolean('show_pos')->default(true);
$table->boolean('synced_from_smartlife')->default(false);
```

### Product Sync Behavior

1. **SmartLife is the Source of Truth**: Products are created/updated from SmartLife ERP
2. **Shadow Table**: `smartlife_products` caches raw SmartLife data
3. **Main Table Sync**: Products are synced to main `products` table with mapping
4. **Barcode Matching**: Orders use barcode or smartlife_id to match products
5. **Read-Only Admin**: Product create/edit/delete disabled in admin panel

## Features Implemented

### 1. User Synchronization
- **On Registration**: When a user registers, a customer account is created in SmartLife ERP automatically
- **Customer ID Storage**: The SmartLife customer ID is stored in the `users.smartlife_customer_id` column
- **Non-blocking**: If SmartLife API fails, user registration still completes successfully

### 2. Product Management (Read-Only)
- **Admin Panel**: Product list is VIEW ONLY - create, edit, delete buttons are disabled
- **Product Sync**: Products are automatically synced from SmartLife ERP via cron job
- **Local Cache**: Products are stored in `smartlife_products` table for fast queries
- **Status Toggle**: Admins can still activate/deactivate products

### 3. Order Submission
- **Automatic Sync**: Orders are submitted to SmartLife ERP after successful checkout
- **Customer Mapping**: System uses stored `smartlife_customer_id` or creates new customer
- **Product Matching**: Order items are matched with SmartLife products by ID or name
- **Non-blocking**: Checkout completes even if SmartLife API fails

### 4. Subscription Discounts
- **Automatic Application**: Active user subscriptions automatically apply discounts at checkout
- **Maximum Limit**: Respects `max_discount_amount` from subscription plan
- **Stacking**: Subscription discounts stack with coupon/offer discounts

## Installation Steps

### Step 1: Environment Configuration

Add these variables to your `.env` file:

```env
# SmartLife ERP Configuration
SMARTLIFE_API_URL=https://api.smartlife.com
SMARTLIFE_EMAIL=your-email@example.com
SMARTLIFE_PASSWORD=your-password
SMARTLIFE_DEFAULT_CUSTOMER_ID=1
SMARTLIFE_TOKEN_CACHE_TTL=3600
SMARTLIFE_SYNC_ENABLED=true
```

**Note**: Get the correct API URL, email, and password from your SmartLife ERP account.

### Step 2: Run Database Migrations

Run migrations to add SmartLife fields to products table:

```bash
php artisan migrate
```

This will create:
- `users.smartlife_customer_id` column
- `smartlife_products` table (shadow table for raw SmartLife data)
- SmartLife-related columns in `products` table:
  - `smartlife_id` (unique identifier)
  - `barcode` (indexed for fast lookup)
  - `unit` (KG, PC, etc)
  - `cost` (cost price)
  - `alert_quantity` (low stock alert)
  - `product_type` (Standard, Combo)
  - `show_pos` (POS visibility)
  - `synced_from_smartlife` (tracking flag)

### Step 3: Clear Caches

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### Step 4: Test Product Sync

Run a manual sync to test the connection and sync products to main products table:

```bash
php artisan smartlife:sync-products
```

**Options:**
- `--limit=100`: Set products per page (default: 100)
- `--shadow-only`: Sync to shadow table only (for testing)

**What happens during sync:**
1. Fetches all products from SmartLife API (paginated)
2. Syncs to `smartlife_products` shadow table
3. Maps and syncs to main `products` table with proper field mapping
4. Matches existing products by `smartlife_id` or `barcode`
5. Creates new products with default Laravel fields
6. Preserves existing product customizations (descriptions, images, etc)

You should see output like:
```
Retrieved 150 products from SmartLife ERP.
[Progress Bar]
Sync completed!
Successful: 150
Created: 120
Updated: 30
Errors: 0
```

Check `storage/logs/laravel.log` for detailed logs.

### Step 5: Schedule Cron Job

#### Option A: Laravel Scheduler (Recommended)

Edit `app/Console/Kernel.php` and add to the `schedule()` method:

```php
protected function schedule(Schedule $schedule)
{
    // Sync products from SmartLife ERP every hour
    $schedule->command('smartlife:sync-products')->hourly();
}
```

Then add this single cron entry:

```bash
* * * * * cd /path/to/your/project && php artisan schedule:run >> /dev/null 2>&1
```

#### Option B: Direct Cron Entry

```bash
# Sync products every hour
0 * * * * cd /path/to/your/project && php artisan smartlife:sync-products >> /dev/null 2>&1
```

## Testing

### Test User Registration
1. Register a new user on the frontend
2. Check `storage/logs/laravel.log` for "SmartLife customer created successfully"
3. Verify the user has a `smartlife_customer_id` in the database

### Test Product Sync
1. Run: `php artisan smartlife:sync-products`
2. Check `smartlife_products` table for synced products
3. View admin product list - should show synced products with info notice

### Test Checkout
1. Add products to cart
2. Complete checkout (authenticated or guest)
3. Check logs for "SmartLife order submitted successfully"
4. Verify order appears in SmartLife ERP dashboard

### Test Subscription Discount
1. Create or assign a subscription to a user (with discount_percent > 0)
2. Ensure subscription is active (`end_at >= now()` and `status = 1`)
3. Add products to cart and proceed to checkout
4. Verify discount is applied in the order total

## File Structure

```
app/
├── Console/Commands/
│   └── SyncSmartLifeProducts.php         # Cron command for product sync
├── Http/Controllers/
│   ├── Admin/
│   │   └── ProductController.php         # Modified: disabled edit/delete
│   └── Frontend/
│       ├── AuthController.php            # Modified: creates SmartLife customer
│       └── CheckoutController.php        # Modified: submits order, applies subscription discount
├── Models/
│   └── SmartLifeProduct.php              # Model for synced products
└── Services/
    └── SmartLifeErpService.php           # Service for all SmartLife API calls

config/
└── smartlife.php                          # Configuration file

database/migrations/
├── 2025_12_14_000001_add_smartlife_customer_id_to_users_table.php
└── 2025_12_14_000002_create_smartlife_products_table.php

resources/views/admin/pages/product/
└── index.blade.php                        # Modified: shows ERP sync notice
```

## Configuration Options

Edit `config/smartlife.php` or use environment variables:

| Config Key | Env Variable | Description | Default |
|------------|-------------|-------------|---------|
| `api_url` | `SMARTLIFE_API_URL` | SmartLife API base URL | - |
| `email` | `SMARTLIFE_EMAIL` | SmartLife account email | - |
| `password` | `SMARTLIFE_PASSWORD` | SmartLife account password | - |
| `default_customer_id` | `SMARTLIFE_DEFAULT_CUSTOMER_ID` | Default customer ID for API | 1 |
| `token_cache_ttl` | `SMARTLIFE_TOKEN_CACHE_TTL` | Token cache duration (seconds) | 3600 |
| `sync_enabled` | `SMARTLIFE_SYNC_ENABLED` | Enable/disable sync | true |

## Troubleshooting

### Product Sync Not Working

**Check logs:**
```bash
tail -f storage/logs/laravel.log | grep SmartLife
```

**Test connection:**
```bash
php artisan smartlife:sync-products
```

**Common issues:**
- Wrong API URL, email, or password in `.env`
- SmartLife API is down
- Network/firewall blocking requests

### User Registration Not Creating Customer

**Check logs for errors:**
```bash
grep "SmartLife customer" storage/logs/laravel.log
```

**Verify:**
- `SMARTLIFE_SYNC_ENABLED=true` in `.env`
- User has phone number in request
- API credentials are correct

### Orders Not Submitting to SmartLife

**Check logs:**
```bash
grep "SmartLife order" storage/logs/laravel.log
```

**Verify:**
- Products exist in `smartlife_products` table
- User has `smartlife_customer_id` (or can be created)
- Order items have valid product references

### Subscription Discount Not Applying

**Check:**
- User has an active subscription (`user_subscriptions.status = 1`)
- Subscription not expired (`end_at >= now()`)
- Subscription has `discount_percent > 0`
- User is logged in (guests don't get subscription discounts)

**Check logs:**
```bash
grep "Subscription discount applied" storage/logs/laravel.log
```

## API Endpoints Used

| Endpoint | Method | Purpose |
|----------|--------|---------|
| `/user/login` | POST | Get access token |
| `/products/get_products_list` | GET | Fetch products (paginated) |
| `/clients/add` | POST | Create customer |
| `/sales/add` | POST | Submit order/sale |

## Security Notes

1. **Token Caching**: Access tokens are cached for 1 hour to reduce login calls
2. **Error Logging**: All API failures are logged to `storage/logs/laravel.log`
3. **Non-blocking**: Integration failures don't block user operations
4. **Config Toggle**: Set `SMARTLIFE_SYNC_ENABLED=false` to disable sync

## Maintenance

### Disable Sync Temporarily
```bash
# In .env
SMARTLIFE_SYNC_ENABLED=false
```

Then clear config cache:
```bash
php artisan config:clear
```

### Clear Token Cache
```php
// In tinker or controller
app(\App\Services\SmartLifeErpService::class)->clearTokenCache();
```

### Re-sync All Products
```bash
php artisan smartlife:sync-products
```

## Support

For issues related to:
- **SmartLife API**: Contact SmartLife ERP support
- **Laravel Integration**: Check logs in `storage/logs/laravel.log`
- **Cron Jobs**: Verify crontab with `crontab -l` and check `/var/log/cron`

---

**Last Updated**: December 2024  
**Integration Version**: 1.0
