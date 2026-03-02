<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdvertiseRequest extends FormRequest
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
            'image_one' => 'nullable|image',
            'image_two' => 'nullable|image',
            'image' => 'nullable|image',
            'en_title' => 'nullable|string|max:191',
            'ar_title' => 'nullable|string|max:191',
            'en_subtitle' => 'nullable|string',
            'ar_subtitle' => 'nullable|string',
            'en_small_description' => 'nullable|string',
            'ar_small_description' => 'nullable|string',
            'link' => 'nullable|url',
            'display_order' => 'nullable|integer',
            'status' => 'nullable|boolean',
            'location' => 'nullable|string|max:100'
        ];
    }
}
