<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CouponRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'coupon_code'=>'required',
            'amount'=>'required',
            'expire_date'=>'required',
            'usage_count' => request()->user_id ? 'nullable' : 'required|integer|min:1',
            'user_id' => 'nullable|exists:users,id',
        ];
    }

    public function prepareForValidation()
    {
        return $this->merge([
            'usage_count' => request()->user_id ? 1 : request()->usage_count,
        ]);
    } // end of prepareForValidation
}
