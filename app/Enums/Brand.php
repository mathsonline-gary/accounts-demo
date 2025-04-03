<?php

namespace App\Enums;

use App\Traits\HasEnumValues;

enum Brand: int
{
    use HasEnumValues;

    case MATHSONLINE_AU = 1;
    case CONQUERMATHS_UK = 2;
    case MATHSBUDDY_NZ = 3;
    case MATHONLINE_US = 4;
    case MATHSONLINE_KE = 5;
    case MATHSONLINE_DEV_AU = 6;
    case MATHSBUDDY_ZA = 7;
    case CTCMATH_US = 8;
    case MATHSONLINE_IN = 9;

    public function toString(): string
    {
        return match ($this) {
            self::MATHSONLINE_AU => 'MathsOnline (AU)',
            self::CONQUERMATHS_UK => 'ConquerMaths (UK)',
            self::MATHSBUDDY_NZ => 'MathsBuddy (NZ)',
            self::MATHONLINE_US => 'MathOnline (US)',
            self::MATHSONLINE_KE => 'MathsOnline (KE)',
            self::MATHSONLINE_DEV_AU => 'MathsOnline (DEV AU)',
            self::MATHSBUDDY_ZA => 'MathsBuddy (ZA)',
            self::CTCMATH_US => 'CTCMath (US)',
            self::MATHSONLINE_IN => 'MathsOnline (IN)',
        };
    }
}
