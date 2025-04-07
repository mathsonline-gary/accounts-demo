<?php

namespace App\Exceptions\Orders;

use Exception;

class OrderAlreadyCancelledException extends Exception
{
    protected $message = 'The order has already been cancelled.';
}
