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

    public function toString(): string
    {
        return match ($this) {
            self::NEW => 'New',
            self::RENEWAL => 'Renewal',
            self::TRIAL => 'Trial',
            self::COUPON_REDEMPTION => 'Coupon Redemption',
            self::GIFT => 'Gift',
            self::OFFLINE => 'Offline',
        };
    }
}
