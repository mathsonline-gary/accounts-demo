<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ReferenceNonce implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $secret = config('services.mol.nonce_secret');

        if (empty($value)) {
            $fail('validation.required')->translate(['attribute' => 'nonce code']);

            return;
        }

        $a = explode(',', $value);

        if (count($a) != 3) {
            $fail('validation.custom.invalid')->translate(['attribute' => 'nonce code']);

            return;
        }
        $salt = $a[0];
        $maxTime = intval($a[1]);
        $hash = $a[2];
        $back = sha1($salt.$secret.$maxTime);

        if ($back != $hash) {
            $fail('validation.custom.invalid')->translate(['attribute' => 'nonce code']);

            return;
        }

        if (time() > $maxTime) {
            $fail('validation.custom.invalid')->translate(['attribute' => 'nonce code']);

            return;
        }
    }
}
