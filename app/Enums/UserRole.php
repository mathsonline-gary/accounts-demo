<?php

namespace App\Enums;

use App\Traits\HasEnumValues;

enum UserRole: int
{
    use HasEnumValues;

    case ADMIN = 1;
    case STUDENT = 2;
    case TEACHER = 3;
    case CUSTOMER = 4;

    public function toString(): string
    {
        return match ($this) {
            self::ADMIN => 'Admin',
            self::STUDENT => 'Student',
            self::TEACHER => 'Teacher',
            self::CUSTOMER => 'Customer',
        };
    }
}
