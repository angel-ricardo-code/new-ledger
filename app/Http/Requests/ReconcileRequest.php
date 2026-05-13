<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReconcileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'counted_amount' => ['required', 'numeric', 'gte:0'],
            'app_balance' => ['required', 'numeric'],
            'date' => ['required', 'date_format:Y-m-d'],
        ];
    }
}
