# WhatsApp Store API Documentation

This documentation provides details for the WhatsApp Store Bot APIs integration.

**Base URL:** `https://hispeed.om/api/whatsapp`

---

## 1. User Registration
Registers a new user account.

- **Endpoint:** `POST /register`
- **Auth Required:** No

### CURL Example
```bash
curl --location 'https://hispeed.om/api/whatsapp/register' \
--header 'Content-Type: application/json' \
--header 'Accept: application/json' \
--data '{
    "name": "John Doe",
    "email": "john.doe@example.com",
    "phone": "71234567",
    "country_code": "968",
    "password": "password123"
}'
```

### Expected Response (Success - 201)
```json
{
    "message": "User registered successfully",
    "user": {
        "id": 123,
        "name": "John Doe",
        "email": "john.doe@example.com",
        "Number": "96871234567",
        "email_verified_at": "2026-03-15T15:23:06.000000Z",
        "created_at": "2026-03-15T15:23:06.000000Z",
        "updated_at": "2026-03-15T15:23:06.000000Z"
    },
    "token": "1|AbCdeFgHiJkLmNoP..."
}
```

---

## 2. User Login
Logs in an existing user and returns an authentication token.

- **Endpoint:** `POST /login`
- **Auth Required:** No

### CURL Example
```bash
curl --location 'https://hispeed.om/api/whatsapp/login' \
--header 'Content-Type: application/json' \
--header 'Accept: application/json' \
--data '{
    "login_id": "96871234567",
    "password": "password123"
}'
```

### Expected Response (Success - 200)
```json
{
    "user": {
        "id": 123,
        "name": "John Doe",
        "email": "john.doe@example.com",
        "Number": "96871234567",
        ...
    },
    "token": "2|XyZ123KaBcDe..."
}
```

---

## 3. Get Categories
Retrieves all active product categories.

- **Endpoint:** `GET /categories`
- **Auth Required:** No

### CURL Example
```bash
curl --location 'https://hispeed.om/api/whatsapp/categories' \
--header 'Accept: application/json'
```

### Expected Response (Success - 200)
```json
{
    "data": [
        {
            "id": 1,
            "en_Category_Name": "Fruits",
            "fr_Category_Name": "فواكه",
            "Category_Icon": "https://hispeed.om/uploaded_files/category_image/icon.png",
            ...
        }
    ]
}
```

---

## 4. Get Products
Retrieves a paginated list of products.

- **Endpoint:** `GET /products`
- **Auth Required:** No
- **Query Params:**
    - `category_id`: (optional) Filter by category ID.
    - `search`: (optional) Search by name.

### CURL Example
```bash
curl --location 'https://hispeed.om/api/whatsapp/products?category_id=1&per_page=10' \
--header 'Accept: application/json'
```

### Expected Response (Success - 200)
```json
{
    "data": [
        {
            "id": 50,
            "en_Product_Name": "Fresh Mango",
            "fr_Product_Name": "مانجو طازج",
            "category": { "id": 1, "en_Category_Name": "Fruits" },
            "weights": [...],
            "sizes": [...],
            "additions": [...],
            ...
        }
    ],
    "links": { ... },
    "meta": { ... }
}
```

---

## 5. Shipping Locations & Charges
Retrieves the location hierarchy and delivery charges.

- **Endpoint:** `GET /shipping-locations`
- **Auth Required:** No

### CURL Example
```bash
curl --location 'https://hispeed.om/api/whatsapp/shipping-locations' \
--header 'Accept: application/json'
```

### Expected Response (Success - 200)
```json
[
    {
        "id": 1,
        "name": "Oman",
        "name_ar": "عمان",
        "delivery_charge": 2.000,
        "states": [
            {
                "id": 10,
                "name": "Muscat",
                "name_ar": "مسقط",
                "delivery_charge": 1.500,
                "cities": [
                    {
                        "id": 100,
                        "name": "Seeb",
                        "name_ar": "السيب",
                        "delivery_charge": 1.000
                    }
                ]
            }
        ]
    }
]
```

---

## 6. Checkout
Places an order and returns a payment link.

- **Endpoint:** `POST /checkout`
- **Auth Required:** Yes (Sanctum Token)

### CURL Example
```bash
curl --location 'https://hispeed.om/api/whatsapp/checkout' \
--header 'Content-Type: application/json' \
--header 'Accept: application/json' \
--header 'Authorization: Bearer YOUR_TOKEN_HERE' \
--data '{
    "billing_name": "John Doe",
    "billing_email": "john.doe@example.com",
    "billing_country": "Oman",
    "billing_state": 10,
    "billing_city": 100,
    "billing_street_address": "Street 123",
    "billing_zipcode": "123",
    "order_source": "whatsapp",
    "Payment_Method": "Thawani", // Options: Thawani, CashOnDelivery, BANK_TRANSFER, STORE_PICKUP
    "cart_items": [
        {
            "product_id": 50,
            "quantity": 2,
            "weight_id": 5,
            "addition_ids": [1, 2]
        }
    ]
}'
```

### Expected Response (Thawani - 200)
If `Payment_Method` is `Thawani`, returns a redirect URL to the payment gateway:
```json
{
    "url": "https://checkout.thawani.om/session/ss_abc123_xyz?key=pk_test_..."
}
```

### Expected Response (COD / BANK_TRANSFER / STORE_PICKUP - 200)
For offline or pre-paid methods, returns order details immediately:
```json
{
    "message": "Order created successfully",
    "order_number": "10050",
    "grand_total": 25.500,
    "payment_method": "STORE_PICKUP",
    "language": "en",
    "receipt_url": "https://hispeed.om/order/print/123",
    "invoice_pdf_url": "https://hispeed.om/api/v1/whatsapp/invoice/pdf/123"
}
```

### Payment Logic Notes:
- **Thawani**: Order is created as `PENDING` payment. Bot must redirect user to the `url`.
- **CashOnDelivery (COD)**: Order is created as `PROCESSING`.
- **BANK_TRANSFER**: Order is created as `PROCESSING` and marked as `PAID` (Admin confirms transfer).
- **STORE_PICKUP**:
    - **Shipping Fee**: Automatically set to `0.000` regardless of location.
    - **Status**: Created as `PROCESSING` and marked as `PAID` (Customer pays in-store).
    - **Logic**: Use this when customer chooses to collect from shop.

