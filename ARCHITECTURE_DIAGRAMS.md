# Implementation Architecture Diagrams

## 1. Payment Webhook Flow

```
┌─────────────┐
│    USER     │
└──────┬──────┘
       │ 1. Checkout
       ▼
┌─────────────────────────────────────┐
│   CheckoutController                │
│                                     │
│  - Validate order                   │
│  - Create order (is_paid=false)     │
│  - Generate pending_token           │
│  - Save payment_session_id          │
└──────┬──────────────────────────────┘
       │ 2. Redirect to payment
       ▼
┌─────────────────────────────────────┐
│   Payment Gateway                   │
│   (Credit Card / SmartLife Pay)     │
└──────┬───────────────────┬──────────┘
       │                   │
       │ 3. Webhook        │ 4. User redirect
       │ (server-to-       │ (browser)
       │  server)          │
       ▼                   ▼
┌────────────────┐   ┌──────────────────────┐
│ SmartLife      │   │ Payment              │
│ Webhook        │   │ Callback             │
│ Controller     │   │ Controller           │
│                │   │                      │
│ - Verify sig   │   │ - Check if paid      │
│ - Find order   │   │   (by webhook)       │
│ - Mark paid    │   │ - Show success       │
│ - Decrement    │   │ - Clear cart         │
│   stock        │   │                      │
│ - Sync to      │   └──────────────────────┘
│   SmartLife    │
│ - Send email   │
└────────────────┘

Order Status Timeline:
─────────────────────────────────────────────
Create          Webhook         User Sees
Order           Confirms        Success
│               │               │
▼               ▼               ▼
is_paid=false   is_paid=true    Thank you!
Pending         Success         (Order paid)
```

## 2. Soft Delete Protection Flow

```
┌─────────────────────────────────────────────┐
│  Product/Category Deletion Attempt          │
└──────┬──────────────────────────────────────┘
       │
       ▼
┌─────────────────────────────────────────────┐
│  Observer (ProductObserver/CategoryObserver)│
└──────┬──────────────────────────────────────┘
       │
       ▼
┌─────────────────────────────────────────────┐
│  Is this HARD delete (forceDelete)?         │
└──────┬───────────────────┬──────────────────┘
       │                   │
       NO                  YES
       │                   │
       ▼                   ▼
┌──────────────┐    ┌────────────────────────┐
│ Soft Delete  │    │ Check for Orders       │
│ Allowed      │    └──────┬─────────────────┘
│              │           │
│ Set:         │           ▼
│ deleted_at   │    ┌────────────────────────┐
│ deleted_     │    │ Has Orders?            │
│  reason      │    └──────┬───────────┬─────┘
│              │           │           │
│ Product      │          YES         NO
│ hidden from  │           │           │
│ listings     │           ▼           ▼
│              │    ┌──────────┐ ┌─────────┐
│ Orders still │    │ BLOCK    │ │ ALLOW   │
│ show it      │    │ Delete   │ │ Hard    │
└──────────────┘    │          │ │ Delete  │
                    │ Throw    │ │         │
                    │ Exception│ │ Remove  │
                    │          │ │ from DB │
                    └──────────┘ └─────────┘
```

## 3. SmartLife Sync with Deletion Handling

```
┌────────────────────────────────────────┐
│  php artisan smartlife:sync-products   │
└────────┬───────────────────────────────┘
         │
         ▼
┌────────────────────────────────────────┐
│  1. Fetch Active Products from         │
│     SmartLife API                       │
└────────┬───────────────────────────────┘
         │
         ▼
┌────────────────────────────────────────┐
│  2. Sync to Shadow Table                │
│     (smartlife_products)                │
└────────┬───────────────────────────────┘
         │
         ▼
┌────────────────────────────────────────┐
│  3. Sync to Main Products Table         │
│     - Create new                        │
│     - Update existing                   │
│     - Download images                   │
│     - Map categories                    │
└────────┬───────────────────────────────┘
         │
         ▼
┌────────────────────────────────────────┐
│  4. Handle Deletions                    │
│     handleDeletedItems()                │
└────────┬───────────────────────────────┘
         │
         ▼
┌────────────────────────────────────────┐
│  Find Local Products NOT in SmartLife  │
│  (Deleted from SmartLife ERP)          │
└────────┬───────────────────────────────┘
         │
         ▼
┌────────────────────────────────────────┐
│  For Each Deleted Product:             │
│                                         │
│  Has Orders?                            │
│  ├─ YES → Soft Delete                  │
│  │         (deleted_reason=            │
│  │          'smartlife_sync')          │
│  │         Keep for order history      │
│  │                                      │
│  └─ NO  → Soft Delete                  │
│            (deleted_reason=             │
│             'smartlife_sync_no_orders') │
│            Can be purged later          │
└─────────────────────────────────────────┘
```

## 4. Data Flow: Order Creation to Fulfillment

```
USER CHECKOUT
     │
     ▼
┌─────────────────────────────────┐
│ CREATE ORDER                    │
│ ┌─────────────────────────────┐ │
│ │ Order Table                 │ │
│ │ - Order_Number              │ │
│ │ - is_paid = FALSE           │ │
│ │ - pending_token = UUID      │ │
│ │ - Payment_Status = PENDING  │ │
│ │ - Grand_Total               │ │
│ └─────────────────────────────┘ │
│                                 │
│ ┌─────────────────────────────┐ │
│ │ OrderDetails Table          │ │
│ │ - Product_Id                │ │
│ │ - Quantity                  │ │
│ │ - Price                     │ │
│ └─────────────────────────────┘ │
└─────────────────────────────────┘
     │
     ▼
PAYMENT GATEWAY PROCESSES
     │
     ▼
┌─────────────────────────────────┐
│ WEBHOOK RECEIVED                │
│                                 │
│ Update Order:                   │
│ - is_paid = TRUE                │
│ - Payment_Status = SUCCESS      │
│ - txn = transaction_id          │
│                                 │
│ Decrement Stock:                │
│ - Product.Quantity -= ordered   │
│                                 │
│ Sync to SmartLife:              │
│ - submitOrder()                 │
│ - smartlife_invoice_id saved    │
│ - smartlife_synced_at = now()   │
│                                 │
│ Send Email:                     │
│ - orderConfirmMail()            │
└─────────────────────────────────┘
     │
     ▼
ORDER COMPLETED
```

## 5. Product Lifecycle with Soft Delete

```
PRODUCT CREATED
     │
     ▼
┌─────────────────────────────────┐
│ Active Product                  │
│ - Status = 1                    │
│ - deleted_at = NULL             │
│                                 │
│ Visible in:                     │
│ - Product listings              │
│ - Search                        │
│ - Category pages                │
└─────────────────────────────────┘
     │
     │ Orders placed ────────┐
     │                       │
     ▼                       ▼
SOFT DELETE             ┌─────────────┐
(delete())              │ OrderDetails│
     │                  │ - Product_Id│
     ▼                  │ - Name      │
┌─────────────────────────────────┐  │ - Price     │
│ Soft Deleted Product            │  └─────────────┘
│ - deleted_at = timestamp        │        │
│ - deleted_reason = ...          │        │
│                                 │        │
│ Hidden from:                    │        │
│ - Product listings              │        │
│ - Search                        │        │
│ - Category pages                │        │
│                                 │        │
│ Still visible in:               │        │
│ - Order history   ◄─────────────┴────────┘
│ - Admin panel (trashed section) │
│                                 │
│ Can be restored:                │
│ - restore()                     │
└─────────────────────────────────┘
     │
     │ Hard delete attempt
     │ with orders
     ▼
┌─────────────────────────────────┐
│ BLOCKED by Observer             │
│ - Exception thrown              │
│ - "Cannot delete, has orders"   │
└─────────────────────────────────┘
```

## 6. SmartLife Integration Full Cycle

```
┌─────────────────────────────────────────────┐
│  SMARTLIFE ERP (External System)            │
│  ┌─────────────┐  ┌──────────────┐         │
│  │  Products   │  │  Customers   │         │
│  │  Database   │  │  Database    │         │
│  └──────┬──────┘  └──────┬───────┘         │
│         │                │                  │
└─────────┼────────────────┼──────────────────┘
          │                │
          │ API Sync       │ API Create
          │ (Pull)         │ (Push)
          ▼                ▼
┌─────────────────────────────────────────────┐
│  YOUR LARAVEL APP                           │
│                                             │
│  ┌──────────────────┐  ┌─────────────────┐ │
│  │ smartlife_       │  │ users           │ │
│  │ products         │  │ smartlife_      │ │
│  │ (shadow table)   │  │ customer_id     │ │
│  └────────┬─────────┘  └─────────────────┘ │
│           │                                 │
│           ▼                                 │
│  ┌──────────────────┐                      │
│  │ products         │                      │
│  │ - smartlife_id   │                      │
│  │ - barcode        │                      │
│  │ - images (local) │                      │
│  └────────┬─────────┘                      │
│           │                                 │
│           ▼                                 │
│  ┌──────────────────┐                      │
│  │ orders           │                      │
│  │ - smartlife_     │                      │
│  │   invoice_id     │ ◄───────┐            │
│  └──────────────────┘         │            │
│                          Push │            │
│                          after │            │
│                          payment│           │
└─────────────────────────────────┼───────────┘
                                  │
                                  ▼
                          ┌───────────────┐
                          │ SmartLife     │
                          │ Invoice       │
                          │ Created       │
                          └───────────────┘

Sync Flow:
1. Cron job: php artisan smartlife:sync-products
2. Pull products → Shadow table → Main products
3. User registers → Create SmartLife customer
4. User orders → Create order (unpaid)
5. Payment webhook → Mark paid + Push to SmartLife
6. SmartLife returns invoice_id → Store locally
```

## Legend

```
┌─────┐
│ Box │ = Process/Component
└─────┘

  │
  ▼    = Flow direction

─────  = Timeline

◄───   = Data flow back

┌─────────────┐
│ Decision    │
└──────┬──────┘
       ├─ YES
       └─ NO
```
