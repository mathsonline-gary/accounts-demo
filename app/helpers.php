<?php

if (! function_exists('verify_nonce_code')) {
    /**
     * Validate a Nonce code.
     */
    function verify_nonce_code(?string $nonce): bool
    {
        $secret = config('services.mol.nonce_secret');

        if (empty($nonce)) {
            return false;
        }

        $a = explode(',', $nonce);
        if (count($a) != 3) {
            return false;
        }
        $salt = $a[0];
        $maxTime = intval($a[1]);
        $hash = $a[2];
        $back = sha1($salt.$secret.$maxTime);
        if ($back != $hash) {
            return false;
        }
        if (time() > $maxTime) {
            return false;
        }

        return true;
    }
}
