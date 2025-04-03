<?php

namespace App\Enums;

use App\Traits\HasEnumValues;

enum OrderType: int
{
    use HasEnumValues;

    case NEW = 1;
    case RENEWAL = 2;
    case TRIAL = 3;
    case COUPON_REDEMPTION = 4;
    case GIFT = 5;
    case OFFLINE = 6;

    public function toString(): string
    {
        return match ($this) {
            self::NEW => 'new',
            self::RENEWAL => 'renewal',
            self::TRIAL => 'trial',
            self::COUPON_REDEMPTION => 'coupon_redemption',
            self::GIFT => 'gift',
            self::OFFLINE => 'offline',
        };
    }
}
