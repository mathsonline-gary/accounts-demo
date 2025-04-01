<?php

namespace App\Enums;

use App\Traits\HasEnumValues;

enum CampaignCode: string
{
    use HasEnumValues;

    case REGULAR = 'REGULAR';
    case ORIGINAL = 'ORIGINAL';
    case PLUS6 = '12PLUS6';
    case PLUS3 = '12PLUS3';
    case DISCOUNT_10 = '10DISC';
    case DISCOUNT_20 = '20DISC';
    case DISCOUNT_50 = '50DISC';
}
