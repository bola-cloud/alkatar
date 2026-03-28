# WhatsApp Store API Documentation

This documentation covers the newly updated **/checkout** endpoint specifically structured for the WhatsApp bot. It details how to correctly send the required fields, explaining the transition to optional fields and updated payment methodology.

---

## Endpoint: Checkout
`POST /api/whatsapp/checkout`

Creates a new order from a WhatsApp interaction.

### Request Headers
- `Authorization: Bearer <user_token>`
- `Accept: application/json`
- `Content-Type: application/json`

### Request Body (JSON)
The JSON body schema allows an array of `cart_items` along with billing/shipping data and the choice of `Payment_Method`.

> [!TIP]
> The `weight_id` and `size_id` are now completely **optional** (can be omitted or set to `null`).
> The `Payment_Method` now accepts dynamic strings such as `"Thawani"` or `"CashOnDelivery"`.

#### Example Payload: Online Payment (Thawani) without Weights
```json
{
    "billing_name": "Ahmed Salem",
    "billing_zipcode": "12345",
    "billing_country": "Oman",
    "billing_state": 1,
    "billing_city": 47,
    "order_source": "whatsapp",
    "Payment_Method": "Thawani",
    "cart_items": [
        {
            "product_id": 256,
            "quantity": 1
        }
    ]
}
```

#### Example Payload: Cash On Delivery without Weights
```json
{
    "billing_name": "Ahmed Salem",
    "billing_zipcode": "12345",
    "billing_country": "Oman",
    "billing_state": 1,
    "billing_city": 47,
    "order_source": "whatsapp",
    "Payment_Method": "CashOnDelivery",
    "cart_items": [
        {
            "product_id": 256,
            "quantity": 2
        }
    ]
}
```

---

### Responses

All success responses will now clearly state the `payment_method` utilized. This acts as an identification key bridging what operation took place on the backend.

#### 1. Success Response (Thawani Session Created)
If `"Payment_Method": "Thawani"` was provided, the API creates a payment session via the reliable gateway endpoint. Use the `url` returned directly for redirection.

```json
{
    "message": "Payment session created",
    "order_number": "J8K12L",
    "grand_total": 45.2,
    "payment_method": "Thawani",
    "url": "https://chest.thawani.om/pay/session_id_here?key=public_key_here"
}
```

#### 2. Success Response (Cash On Delivery)
If `"Payment_Method": "CashOnDelivery"` was provided, the order finishes processing immediately.

```json
{
    "message": "Order created successfully",
    "order_number": "J8K12L",
    "grand_total": 45.2,
    "payment_method": "CashOnDelivery"
}
```

#### 3. Error Case (Thawani Gateway Issues)
If the parameters sent to Thawani are improperly formatted, or if the Thawani Gateway server is down, the system no longer dies silently using a generic `500` error. The detailed array is outputted to help diagnose:

```json
{
    "error": "Payment gateway error",
    "details": {
         "code": 4000,
         "description": "Validation Error (quantity is out of range)",
         "data": []
    }
}
```

---

### Field Requirements Table

| Field Name | Type | Constraints | Description |
|---|---|---|---|
| `Payment_Method` | `string` | **Required** | i.e., `"CashOnDelivery"`, `"Thawani"` |
| `order_source` | `string` | **Required** | Must be: `"whatsapp"`, `"mobile"`, or `"web"` |
| `cart_items` | `array` | **Required** | The array containing user purchases |
| `product_id` | `integer` | **Required** | In each `cart_item` |
| `quantity` | `integer` | **Required** (Min: 1) | In each `cart_item` |
| `weight_id` | `integer` | **Optional** | Omit safely if product carries no weight selection. |
| `size_id` | `integer` | **Optional** | Omit safely if product has no size selection. |
