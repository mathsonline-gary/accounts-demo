<?php

namespace App\Exceptions;

use Exception;

class InvalidOrderException extends Exception
{
    protected $message = 'Invalid order';

    protected $code = 422;
}
