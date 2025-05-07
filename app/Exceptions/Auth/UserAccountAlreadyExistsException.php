<?php

namespace App\Exceptions\Auth;

use Exception;

class UserAccountAlreadyExistsException extends Exception
{
    protected $message = 'User account already exists.';
}
