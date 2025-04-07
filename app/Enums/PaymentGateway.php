<?php

namespace App\Enums;

enum PaymentGateway: string
{
    case STRIPE = 'stripe';
    case PAYPAL = 'paypal';

    public function toString(): string
    {
        return $this->value;
    }
}
