<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SyrianNumberRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //
        $value = (string) $value;
        if (
            strlen($value) !== 10 ||
            !str_starts_with($value, '09') ||
            !ctype_digit($value)
        ) {
            $fail('The phone number must start with 09 and be exactly 10 digits.');
        }
    }
}
