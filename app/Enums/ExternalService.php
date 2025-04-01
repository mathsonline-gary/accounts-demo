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

    /**
     * Get the display name of the service.
     */
    public function label(): string
    {
        return match($this) {
            self::MATHSONLINE => 'MathsOnline',
            self::GOOGLE => 'Google',
            self::STRIPE => 'Stripe',
            self::KEAP => 'Keap',
        };
    }
} 