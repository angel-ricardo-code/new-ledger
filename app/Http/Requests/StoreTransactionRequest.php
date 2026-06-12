<?php

namespace App\Http\Requests;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date' => ['required', 'date_format:Y-m-d', 'before_or_equal:today'],
            'amount' => ['required', 'numeric', 'gt:0'],
            'type' => ['required', 'in:income,expense'],
            'category_id' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if ($value === null) return;
                    $exists = Category::where('id', $value)
                        ->where(function ($q) {
                            $q->where('user_id', auth()->id())
                              ->orWhereNull('user_id');
                        })->exists();
                    if (!$exists) {
                        $fail('La categoría seleccionada no existe o no te pertenece.');
                    }
                },
            ],
            'note' => ['nullable', 'string', 'max:255'],
        ];
    }

    protected function passedValidation(): void
    {
        if ($this->has('note')) {
            $this->merge([
                'note' => strip_tags($this->note),
            ]);
        }
    }
}
