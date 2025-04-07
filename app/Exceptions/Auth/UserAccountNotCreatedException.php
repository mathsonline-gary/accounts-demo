<?php

namespace App\Exceptions\Auth;

use Exception;

class UserAccountNotCreatedException extends Exception
{
    protected $message = 'Failed to create an account.';
}
