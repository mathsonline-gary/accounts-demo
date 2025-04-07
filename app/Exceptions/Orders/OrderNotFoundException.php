<?php

namespace App\Exceptions\Orders;

use App\Exceptions\Exception;

class OrderNotFoundException extends Exception
{
    protected $message = 'Order not found.';
}
