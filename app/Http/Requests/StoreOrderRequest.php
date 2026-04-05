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
            'billing_name' => 'required|string|max:255',
            'Payment_Method' => 'required|string',
            'billing_email' => 'nullable|email|max:255',
            'order_source' => 'required|string|max:255|in:whatsapp,mobile,web',
            'billing_street_address' => 'nullable|string|max:255',
            'billing_zipcode' => 'required|string|max:10',
            'billing_country' => 'required|string|max:255',
            'billing_state' => 'required|exists:states,id',
            'billing_city' => 'nullable|exists:cities,id',
            'billing_area_id' => 'nullable|exists:areas,id',
            'cart_items' => 'required|array',
            'cart_items.*.product_id' => 'required|exists:products,id',
            'cart_items.*.quantity' => 'required|integer|min:1',
            'cart_items.*.size_id' => 'nullable|exists:sizes,id',
            'cart_items.*.weight_id' => 'nullable|exists:weight_product,id',
            'cart_items.*.addition_ids' => 'nullable|array',
            'cart_items.*.addition_ids.*' => 'exists:additions,id',
            'coupon_code' => 'nullable|string|exists:coupons,CouponCode',
        ];
    }

    /**
     * Custom error messages for validation rules
     *
     * @return array
     */
    public function messages()
    {
        return [
            'cart_items.*.product_id.exists' => 'The selected product does not exist.',
            'cart_items.*.size.exists' => 'The selected size does not exist.',
            'cart_items.*.weight_id.exists' => 'The selected weight does not exist.',
            'cart_items.*.addition_ids.*.exists' => 'One or more of the selected additions do not exist.',
        ];
    }
}
