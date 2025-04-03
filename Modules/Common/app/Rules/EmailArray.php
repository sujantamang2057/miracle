<?php

namespace Modules\Common\app\Rules;

use Closure;
use Egulias\EmailValidator\EmailLexer;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Concerns\FilterEmailValidation;

class EmailArray implements ValidationRule
{
    private $fieldName;

    private $limit;

    public function __construct(string $fieldName = '', int $limit = 0)
    {
        $this->fieldName = $fieldName;
        $this->limit = $limit;
    }

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!empty($value) && is_array($value)) {
            $error = '';
            $fieldName = !empty($this->fieldName) ? $this->fieldName : $attribute;
            $emailValidation = new FilterEmailValidation;
            $emailLexer = new EmailLexer;
            $count = count($value);
            foreach ($value as $key => $email) {
                if ($count == 1 && empty($email)) {
                    $fail(__('common::messages.is_required', ['field' => $fieldName]));
                }
                if (!empty($email) && !$emailValidation->isValid($email, $emailLexer)) {
                    $fail(__('common::messages.is_invalid', ['field' => $fieldName]));
                }
            }
            if (!empty($this->limit) && $count > $this->limit) {
                $fail(__('common::messages.exceeded_no_of_emails_allowed', ['field' => $fieldName]));
            }
        }
    }
}
