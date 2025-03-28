<?php

namespace App\Enums;

enum OrderType: int
{
    case NEW = 1;
    case RENEWAL = 2;
    case TRIAL = 3;
    case COUPON_REDEMPTION = 4;
    case GIFT = 5;
    case OFFLINE = 6;
}
