<?php

namespace App\Enums;

use App\Traits\HasEnumValues;

enum ExternalService: string
{
    use HasEnumValues;

    case MATHSONLINE = 'mathsonline';
    case GOOGLE = 'google';
    case STRIPE = 'stripe';
    case KEAP = 'keap';

    public function toString(): string
    {
        return match ($this) {
            self::MATHSONLINE => 'mathsonline',
            self::GOOGLE => 'google',
            self::STRIPE => 'stripe',
            self::KEAP => 'keap',
        };
    }
}
