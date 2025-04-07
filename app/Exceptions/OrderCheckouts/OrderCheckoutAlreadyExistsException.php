<?php

namespace App\Exceptions\OrderCheckouts;

use Exception;

class OrderCheckoutAlreadyExistsException extends Exception
{
    protected $message = 'The order checkout already exists.';
}
