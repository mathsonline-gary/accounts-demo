<?php

namespace App\Exceptions\Orders;

use Exception;

class OrderAlreadyExpiredException extends Exception
{
    protected $message = 'The order has already expired.';
}
