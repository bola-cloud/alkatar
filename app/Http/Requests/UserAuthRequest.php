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
            'email' => 'required_if:verification_method,email|nullable|email|unique:users',
            'phone' => 'required_if:verification_method,whatsapp|nullable|numeric',
            'full_phone' => 'nullable|unique:users,Number',
            'password' => 'required|min:5|max:12',
            'confirm_password' => 'required|min:5|max:12|same:password',
            'country_code' => 'required_if:verification_method,whatsapp|nullable',
            'verification_method' => 'required|in:email,whatsapp'
        ];
    }
}
