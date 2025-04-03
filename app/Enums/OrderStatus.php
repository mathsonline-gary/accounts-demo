<?php

namespace App\Enums;

use App\Traits\HasEnumValues;

enum OrderStatus: string
{
    use HasEnumValues;

    case PENDING = 'pending'; // The order is pending checkout.

    case PAID = 'paid'; // The order has been successfully paid.

    case CANCELLED = 'cancelled'; // The order has been cancelled. No further action is allowed.

    case EXPIRED = 'expired'; // The order has expired. No further action is allowed.
}
