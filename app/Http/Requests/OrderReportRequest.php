<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'range' => ['nullable', Rule::in(['today', 'week', 'month', 'year', 'custom'])],
            'start_date' => ['required_if:range,custom', 'nullable', 'date_format:Y-m-d'],
            'end_date' => ['required_if:range,custom', 'nullable', 'date_format:Y-m-d', 'after_or_equal:start_date'],
        ];
    }
}
