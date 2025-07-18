<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidAgeListInRange implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $ages = array_map('intval', explode(',', $value));

        foreach ($ages as $age) {
            if ($age < 18 || $age > 70) {
                $fail("Age {$age} is not supported. Ages must be between 18 and 70."); 
            }
        }
    }
}
