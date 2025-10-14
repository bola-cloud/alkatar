<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class OrderReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Authorization is handled by route middleware (auth + is_admin). Allow here.
        return true;
    }

    public function rules(): array
    {
        return [
            'range' => 'nullable|in:today,week,month,year,custom',
            'start_date' => 'required_if:range,custom|nullable|date_format:Y-m-d',
            'end_date' => 'required_if:range,custom|nullable|date_format:Y-m-d|after_or_equal:start_date',
        ];
    }

    public function messages(): array
    {
        return [
            'range.in' => 'Invalid range. Allowed: today, week, month, year, custom.',
            'start_date.required_if' => 'Start date is required for custom range.',
            'end_date.required_if' => 'End date is required for custom range.',
            'end_date.after_or_equal' => 'End date must be greater than or equal to Start date.',
        ];
    }
}
