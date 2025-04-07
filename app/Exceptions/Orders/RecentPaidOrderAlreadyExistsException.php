<?php

namespace App\Exceptions\Orders;

use Exception;

class RecentPaidOrderAlreadyExistsException extends Exception
{
    protected $message = 'A recent paid order already exists.';
}
