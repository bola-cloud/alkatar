# Delivery API Documentation

Base URL: `https://hispeed.muscatappstest.com/api/delivery`

## Standard Headers
Include these headers in all requests:
- `Accept: application/json`
- `Content-Type: application/json`
- `Authorization: Bearer <token>` (for authenticated routes)
- `X-Lang: ar` (or `en`)
- `X-Platform: android` (or `ios`)
- `X-AppVersion: 1.0.0`

## Authentication

### Login
**Endpoint:** `POST /auth/login`

**Example:**
```bash
curl -L -X POST 'https://hispeed.muscatappstest.com/api/delivery/auth/login' \
-H 'Accept: application/json' \
-H 'Content-Type: application/json' \
-d '{
  "phone": "1234567890",
  "password": "password"
}'
```

### Get Current User
**Endpoint:** `GET /auth/user`

**Example:**
```bash
curl -L -X GET 'https://hispeed.muscatappstest.com/api/delivery/auth/user' \
-H 'Accept: application/json' \
-H 'Authorization: Bearer <token>'
```

---

## Orders

### List Available Orders (Ready for Pickup)
**Endpoint:** `GET /orders`
**Status Filter:** `ORDER_PROCESSING` (2)

**Example:**
```bash
curl -L -X GET 'https://hispeed.muscatappstest.com/api/delivery/orders' \
-H 'Accept: application/json' \
-H 'Authorization: Bearer <token>' \
-H 'X-Lang: ar'
```

### My Orders (Active & History)
**Endpoint:** `GET /my-orders`
**Status Filter:** `ORDER_SHIPPED` (3), `ORDER_DELIVERED` (4), `ORDER_DELIVERED_FAILED` (8)

**Example:**
```bash
curl -L -X GET 'https://hispeed.muscatappstest.com/api/delivery/my-orders' \
-H 'Accept: application/json' \
-H 'Authorization: Bearer <token>'
```

### Pick Order (Verify Phone)
**Endpoint:** `POST /pick-order`
Updates status to `ORDER_SHIPPED` (3).

**Example:**
```bash
curl -L -X POST 'https://hispeed.muscatappstest.com/api/delivery/pick-order' \
-H 'Accept: application/json' \
-H 'Content-Type: application/json' \
-H 'Authorization: Bearer <token>' \
-d '{
  "order_id": 1,
  "phone_number": "+96891111111"
}'
```

### Update Order Status (to Delivered)
**Endpoint:** `POST /update-status`
Updates status to `ORDER_DELIVERED` (4) or `ORDER_DELIVERED_FAILED` (8).

**Example:**
```bash
curl -L -X POST 'https://hispeed.muscatappstest.com/api/delivery/update-status' \
-H 'Accept: application/json' \
-H 'Content-Type: application/json' \
-H 'Authorization: Bearer <token>' \
-d '{
  "order_id": 1,
  "status": "delivered"
}'
```

### Order History (Completed Only)
**Endpoint:** `GET /history`
**Status Filter:** `ORDER_DELIVERED` (4), `ORDER_DELIVERED_FAILED` (8)

**Example:**
```bash
curl -L -X GET 'https://hispeed.muscatappstest.com/api/delivery/history' \
-H 'Accept: application/json' \
-H 'Authorization: Bearer <token>'
```

---

## Order Status Values
- **1**: `Pending` (معلق)
- **2**: `Processing` (قيد المعالجة) - *Ready for Pickup*
- **3**: `Shipped` (تم الشحن) - *On the Way / Picked by Driver*
- **4**: `Delivered` (تم التوصيل)
- **5**: `Cancelled` (ملغي)
- **6**: `Returned` (مرجع)
- **7**: `Not Paid Yet` (لم يُدفع بعد)
- **8**: `Delivery Failed` (فشل التسليم)
