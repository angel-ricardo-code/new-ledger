<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:50'],
            'type' => ['required', 'in:income,expense'],
            'color_hex' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'icon' => ['nullable', 'string', 'in:utensils,car,zap,heart,film,shopping-bag,home,briefcase,book,gift,coffee,credit-card,smartphone,plane,dumbbell,music,paw-print,wallet,graduation-cap,circle,laptop,trending-up'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es requerido.',
            'name.max' => 'El nombre no puede tener más de 50 caracteres.',
            'type.required' => 'Selecciona el tipo de categoría.',
            'type.in' => 'El tipo debe ser ingreso o gasto.',
            'color_hex.required' => 'Selecciona un color.',
            'color_hex.regex' => 'El color debe ser un código hexadecimal válido.',
        ];
    }
}
