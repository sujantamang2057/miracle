<?php

namespace Modules\Common\app\Rules;

use Closure;
use Http;
use Illuminate\Contracts\Validation\ValidationRule;

class Recaptcha implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $gResponseToken = (string) $value;

        $response = Http::withOptions(['verify' => false])->asForm()->post('https://www.google.com/recaptcha/api/siteverify',
            ['secret' => env('RECAPTCHAV2_SECRET_KEY', ''), 'response' => $gResponseToken]
        );

        if (!json_decode($response->body(), true)['success']) {
            $fail(__('common::messages.invalid_recaptcha'));
        }
    }
}
