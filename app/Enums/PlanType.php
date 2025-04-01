<?php

namespace App\Enums;

use App\Traits\HasEnumValues;

enum PlanType: int
{
    use HasEnumValues;

    case STANDARD = 1;
    case HOMESCHOOL = 2;
    case OFFLINE = 3;   // Type of custom plans that for offline payment and coupon redemption.
    case DEPRECATED = 4;    // Deprecated plans that are no longer available for purchase.
}
