<?php

namespace App\Exceptions;

use Exception;

class InvalidNonceCodeException extends Exception
{
    protected $message = 'Invalid nonce code.';

    protected $code = 422;
}
