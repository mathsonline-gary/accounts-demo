<?php

namespace App\Exceptions;

use Exception;

class PromoExpiredException extends Exception
{
    protected $message = 'Promo code is expired.';

    protected $code = 422;
}
