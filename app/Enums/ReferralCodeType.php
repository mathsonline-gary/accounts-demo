<?php

namespace App\Enums;

enum ReferralCodeType: int
{
    case PROMO = 1;
    case RENEWAL_COUPON = 2;
    case COUPON = 3;
    case SALES_CODE = 4;
}
