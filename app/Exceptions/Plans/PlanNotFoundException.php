<?php

namespace App\Exceptions\Plans;

use App\Exceptions\Exception;

class PlanNotFoundException extends Exception
{
    protected $message = 'Plan not found.';
}
