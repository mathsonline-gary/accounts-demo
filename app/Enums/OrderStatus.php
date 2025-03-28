<?php

namespace App\Enums;

enum OrderStatus: string
{
    case CREATING = 'creating'; // The order is being created and is not ready for checkout.

    case READY = 'ready'; // The order is created and ready for checkout.

    case PAID = 'paid'; // The order has been successfully paid.

    case CANCELLED = 'cancelled'; // The order has been cancelled. No further action is allowed.
}
