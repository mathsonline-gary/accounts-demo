<?php

namespace App\Exceptions\OrderCheckouts;

use Exception;

class OrderCheckoutNotCreatedException extends Exception
{
    protected $message = 'Failed to create checkout.';
}
