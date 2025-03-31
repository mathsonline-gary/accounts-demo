<?php

namespace App\Exceptions;

use Exception;

class InvalidOrderItemException extends Exception
{
    protected $message = 'The order item is invalid.';

    protected $code = 422;
}
