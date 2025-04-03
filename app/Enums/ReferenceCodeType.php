<?php

namespace App\Enums;

use App\Traits\HasEnumValues;

enum ReferenceCodeType: int
{
    use HasEnumValues;

    case PROMO = 1;
    case RENEWAL_COUPON = 2;
    case COUPON = 3;
    case OFFLINE_SALES = 4;

    public function toString(): string
    {
        return match ($this) {
            self::PROMO => 'promo',
            self::RENEWAL_COUPON => 'renewal_coupon',
            self::COUPON => 'coupon',
            self::OFFLINE_SALES => 'offline_sales',
        };
    }
}
