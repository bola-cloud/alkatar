<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'billing_name' => 'required|string',
            'Payment_Method' => 'required|string',
            'billing_email' => 'nullable|email',
            'billing_street_address' => 'nullable|string',
            'billing_zipcode' => 'required|string',
            'billing_country' => 'required|string',
            'billing_state' => 'required|exists:states,id',
            'billing_city' => 'nullable|exists:cities,id',
            'cart_items' => 'required|array',
            'cart_items.*.product_id' => 'required|exists:products,id',
            'cart_items.*.quantity' => 'required|integer|min:1',
            'cart_items.*.weight' => 'nullable|numeric|min:0',
            'coupon_code' => 'nullable|string|exists:coupons,CouponCode',
            // Add any additional fields as required
        ];
    }
}
