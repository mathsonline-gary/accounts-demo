<?php

namespace App\Exceptions;

use Exception;

class PromoNotActiveException extends Exception
{
    protected $message = 'Promo code is not active.';

    protected $code = 422;
}
