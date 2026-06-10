<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserAuthRequest extends FormRequest
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

    protected function prepareForValidation()
    {
        if ($this->has('country_code') && $this->has('phone')) {
            $this->merge([
                'full_phone' => $this->country_code . $this->phone,
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'phone' => 'required|numeric',
            'full_phone' => 'nullable|unique:users,Number',
            'country_code' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'full_phone.unique' => __('new_design.auth.phone_taken'),
            'phone.required' => __('new_design.auth.phone_required'),
            'name.required' => __('new_design.auth.name_required'),
        ];
    }
}
