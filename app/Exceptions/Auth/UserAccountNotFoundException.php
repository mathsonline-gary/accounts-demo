<?php

namespace App\Exceptions\Auth;

use Exception;

class UserAccountNotFoundException extends Exception
{
    protected $message = 'User account not found.';
}
