<?php

namespace App\Exceptions\Orders;

use Exception;

class OrderAlreadyPaidException extends Exception
{
    protected $message = 'The order has already been paid.';
}
