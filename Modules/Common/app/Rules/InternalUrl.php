<?php

namespace Modules\Common\app\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class InternalUrl implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!empty($value)) {
            $value = str_replace('//', '/', $value);
            if (preg_match(PREG_URL_INTERNAL_EX, $value)) {
                $fail('The :attribute is invalid.');
            }
        }
    }
}
