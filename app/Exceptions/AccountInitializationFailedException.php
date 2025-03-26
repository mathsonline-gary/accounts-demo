<?php

namespace App\Exceptions;

use Exception;

class AccountInitializationFailedException extends Exception
{
    protected $message = 'Failed to initialize account';
}
