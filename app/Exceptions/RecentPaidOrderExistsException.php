<?php

namespace App\Exceptions;

use Exception;

class RecentPaidOrderExistsException extends Exception
{
    protected $message = 'You have recently purchased an order. Please wait before creating another order.';

    protected $code = 429;
}
