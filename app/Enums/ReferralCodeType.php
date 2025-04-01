<?php

namespace App\Enums;

use App\Traits\HasEnumValues;

enum ReferralCodeType: int
{
    use HasEnumValues;

    case PROMO = 1;
    case RENEWAL_COUPON = 2;
    case COUPON = 3;
    case SALES_CODE = 4;
}
