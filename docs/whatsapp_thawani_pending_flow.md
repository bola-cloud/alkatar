# WhatsApp Store — Unpaid Thawani Order Flow

**Version:** 2.0  
**Last Updated:** 2026-04-26  
**Base URL:** `https://hispeed.om/api/whatsapp`

---

## Overview

When a customer places an order and selects **Thawani** as the payment method (whether from the website or the WhatsApp store bot), the system will:

1. Create the order with `Payment_Status = PENDING`.
2. Return the Thawani payment URL to the customer immediately.
3. Schedule a **background job that fires after 30 minutes**.
   - ✅ **If the customer paid within 30 minutes** → the job detects the order is no longer PENDING and does nothing.
   - ⏰ **If the order is still PENDING after 30 minutes** → the backend calls the bot at `pending/payment`.
4. The bot must then present the customer with **three interactive options**:
   - 💳 **Pay Now** — redirect to `payment_url`
   - 🔄 **Convert to Cash on Delivery (COD)** — call `POST /order-action` with `action: convert_to_cod`
   - ❌ **Cancel Order** — call `POST /order-action` with `action: cancel_order`

> ⏱ **Why 30 minutes?** To avoid interrupting customers who are actively completing their payment on the Thawani page. Only genuinely abandoned orders trigger the follow-up message.

---

## Checkout Payment Methods

![Checkout Payment Methods](checkout_methods.png)

*The image above shows the three supported payment methods: Thawani (Online), Cash on Delivery, and In-Store Pickup.*

---

## 1. Pending Payment Notification

The **backend will call this endpoint on the bot** automatically when a Thawani order is created with `PENDING` status.

> This is **not a call you make** — the backend calls `whatsapi.hispeed.om`. It is documented here so you understand what payload the bot receives and what to present to the user.

- **Direction:** Backend → Bot
- **Endpoint on the bot:** `POST https://whatsapi.hispeed.om/api/v1/whatsapp/pending/payment`
- **Trigger:** Fires **30 minutes after order creation**, only if the order is still `Payment_Status = PENDING`. If the customer paid before that, the notification is never sent.

### Payload sent from the backend:

```json
{
    "phone_number": "+96871234567",
    "name": "Ahmed Al-Rashdi",
    "booking_id": 542,
    "order_id": 542,
    "pdf": "https://hispeed.om/api/whatsapp/order-invoice/542/invoice.pdf?lang=ar",
    "payment_url": "https://checkout.thawani.om/session/ss_abc123?key=pk_test_xxx"
}
```

| Field | Type | Description |
|---|---|---|
| `phone_number` | string | Customer's phone number with country code |
| `name` | string | Customer's full name |
| `booking_id` | integer | Order ID in the database |
| `order_id` | integer | Same as `booking_id` |
| `pdf` | string | URL of the order invoice PDF |
| `payment_url` | string | Thawani payment URL to redirect the customer |

### What the bot should do:

Present an interactive message to the customer with **3 buttons/options**:

| Button | Label (AR) | Label (EN) | Action |
|---|---|---|---|
| 1 | ادفع الآن | Pay Now | Open `payment_url` |
| 2 | تحويل إلى كاش | Convert to COD | Call `POST /api/whatsapp/order-action` with `action: convert_to_cod` |
| 3 | إلغاء الطلب | Cancel Order | Call `POST /api/whatsapp/order-action` with `action: cancel_order` |

---

## 2. Order Action Webhook

This is the endpoint **the bot calls** when the customer makes a choice regarding their pending Thawani order.

- **Direction:** Bot → Backend
- **Endpoint:** `POST https://hispeed.om/api/whatsapp/order-action`
- **Auth Required:** No
- **Content-Type:** `application/json`

### Request Body:

```json
{
    "order_id": 542,
    "action": "convert_to_cod"
}
```

| Field | Type | Required | Values |
|---|---|---|---|
| `order_id` | integer | ✅ | The `order_id` received in the pending notification payload |
| `action` | string | ✅ | `convert_to_cod` or `cancel_order` |

---

### Action: `convert_to_cod`

Converts the pending Thawani order to Cash on Delivery.

**What the backend does:**
- Changes `Payment_Method` to `COD`
- Changes order status to **Processing**
- Sends the standard order confirmation invoice to the customer via WhatsApp (`success/payment`)

**cURL Example:**
```bash
curl --location 'https://hispeed.om/api/whatsapp/order-action' \
--header 'Content-Type: application/json' \
--data '{
    "order_id": 542,
    "action": "convert_to_cod"
}'
```

**Success Response (200):**
```json
{
    "message": "Order converted to COD and notification sent."
}
```

**Error Response (400):**
```json
{
    "message": "Order cannot be converted. Status might be paid or already COD."
}
```

---

### Action: `cancel_order`

Cancels the order locally.
    
**What the backend does:**
- Removes the order from the local database.
    
> ℹ️ **Note:** Since unpaid Thawani orders are no longer synced to SmartLife ERP immediately upon creation, there is no need to create a return in the ERP for these cancellations.

**cURL Example:**
```bash
curl --location 'https://hispeed.om/api/whatsapp/order-action' \
--header 'Content-Type: application/json' \
--data '{
    "order_id": 542,
    "action": "cancel_order"
}'
```

**Success Response (200):**
```json
{
    "message": "Order cancelled and deleted successfully."
}
```

---

## 3. Updated Checkout Response (Thawani)

When the bot calls `POST /checkout` and the `Payment_Method` is `Thawani`, the response now includes the `order_id` field — **you must save this** to use in the order-action webhook.

**Checkout Endpoint:** `POST /checkout`  
**Auth Required:** Yes (Sanctum Bearer Token)

**Request Body (Thawani):**
```json
{
    "billing_name": "Ahmed Al-Rashdi",
    "billing_phone": "+96871234567",
    "billing_country": "Oman",
    "billing_state": 10,
    "billing_city": 100,
    "billing_zipcode": "123",
    "order_source": "whatsapp",
    "language": "ar",
    "Collection_Method": "Delivery", // Options: Delivery, Store_Pickup
    "Payment_Method": "Thawani",
    "cart_items": [
        {
            "product_id": 50,
            "quantity": 2
        }
    ]
}
```

**Response (200):**
```json
{
    "message": "Payment session created",
    "order_id": 542,
    "order_number": "2604251530542",
    "grand_total": 5.800,
    "payment_method": "Thawani",
    "url": "https://checkout.thawani.om/session/ss_abc123?key=pk_test_xxx",
    "language": "ar",
    "invoice_pdf_url": "https://hispeed.om/api/whatsapp/order-invoice/542/invoice.pdf?lang=ar"
}
```

> ⚠️ **Important:** The `pending/payment` notification is sent **automatically** by the backend **30 minutes after checkout**, but only if the order is still unpaid. If the customer pays within 30 minutes, no notification is sent. The bot does **not** need to call it manually. Just store the `order_id` from the checkout response for later use.

---

## 4. Standard Success Notification (Already Implemented)

When a Thawani payment is **successfully completed**, the backend automatically calls the bot at:

- **Endpoint on the bot:** `POST https://whatsapi.hispeed.om/api/v1/whatsapp/success/payment`

This is also called when:
- A COD order is placed directly
- The admin converts a pending Thawani order to COD

The bot should send the customer their invoice/order confirmation.

---

## 5. Full Flow Diagram

```
Customer on WhatsApp
        │
        ▼
POST /checkout (Payment_Method: Thawani)
        │
        ▼
Backend creates order (PENDING)
        │
        ├──► Returns { order_id, url, ... } to bot immediately
        │        (bot shows customer the payment link)
        │
        └──► Schedules a delayed job (T + 30 minutes)
                        │
               ┌────────┴────────┐
               ▼                 ▼
        Order PAID?         Still PENDING?
         (Thawani           (customer did
          webhook)           not complete)
               │                 │
           Job exits        Job calls bot:
           silently    pending/payment endpoint
                                 │
                                 ▼
                    Bot shows message to customer:
                    ┌─────────────────────────────┐
                    │ طلبك لم يُكتمل بعد!         │
                    │ [ادفع الآن][كاش][إلغاء]     │
                    └─────────────────────────────┘
                                 │
                 ┌───────────────┼───────────────┐
                 ▼               ▼               ▼
           Pay Now (link)   convert_to_cod   cancel_order
                 │               │               │
          Thawani Page    POST /order-action  POST /order-action
                           Invoice sent to    Order deleted from DB
                           customer           + return_sale in ERP
```

---

## 6. Error Reference

| HTTP Code | Meaning |
|---|---|
| `200` | Success |
| `400` | Bad request — e.g., order already paid or cancelled |
| `404` | Order not found |
| `422` | Validation failed — missing or invalid fields |
| `500` | Server error |

---

### Payment & Collection Methods:

The bot should now distinguish between **how the customer receives the order** (Collection Method) and **how they pay for it** (Payment Method).

#### 1. Collection Method (`Collection_Method`)
| Key | Label (AR) | Logic |
|---|---|---|
| `Delivery` | توصيل | Standard shipping fees apply based on area. |
| `Store_Pickup` | استلام من المحل | **Shipping is 0.000**. Address fields (`billing_state`, `city`, `area`) are **optional**. |

#### 2. Payment Method (`Payment_Method`)
| Key | Initial Status | Sync to ERP? | Logic |
|---|---|---|---|
| `Thawani` | `PENDING` | ❌ Only after payment | Online payment gateway |
| `CashOnDelivery` | `PROCESSING` | ✅ Immediately (as unpaid) | Pay at door |
| `BANK_TRANSFER` | `PROCESSING` | ✅ Immediately (as paid) | Admin confirms transfer |

#### 3. Full Checkout Payload Specification

| Field | Type | Required | Description |
|---|---|---|---|
| `billing_name` | string | ✅ | Customer name |
| `billing_phone` | string | ✅ | Customer phone |
| `billing_country` | string | ✅ | "Oman" |
| `billing_state` | integer | ⚠️ | Required if `Collection_Method` is `Delivery` |
| `billing_city` | integer | ⚠️ | Required if `Collection_Method` is `Delivery` |
| `billing_street_address` | string | ⚠️ | Required if `Collection_Method` is `Delivery` |
| `Collection_Method` | string | ✅ | `Delivery` or `Store_Pickup` |
| `Payment_Method` | string | ✅ | `Thawani`, `CashOnDelivery`, `BANK_TRANSFER` |
| `order_source` | string | ✅ | Must be "whatsapp" |
| `language` | string | ✅ | "ar" or "en" |

---

## 8. Notes for the Developer

- The `order_id` in all payloads refers to the **database primary key** of the order (not the order number).
- The `order_action` endpoint is **public** (no auth required) since the bot calls it server-to-server.
- Language: pass `language: "ar"` or `language: "en"` in the checkout request to receive localized invoice URLs.
- All amounts are in **Omani Rial (OMR)** with 3 decimal places.
