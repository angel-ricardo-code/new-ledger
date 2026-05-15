<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class StrongPassword implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (strlen($value) < 8) {
            $fail('La contraseña debe tener al menos 8 caracteres.');
        } elseif (!preg_match('/[A-Z]/', $value)) {
            $fail('La contraseña debe contener al menos una mayúscula.');
        } elseif (!preg_match('/[a-z]/', $value)) {
            $fail('La contraseña debe contener al menos una minúscula.');
        } elseif (!preg_match('/[0-9]/', $value)) {
            $fail('La contraseña debe contener al menos un número.');
        } elseif (!preg_match('/[^a-zA-Z0-9]/', $value)) {
            $fail('La contraseña debe contener al menos un carácter especial.');
        }
    }
}
