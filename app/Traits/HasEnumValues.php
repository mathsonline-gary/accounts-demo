<?php

namespace App\Traits;

trait HasEnumValues
{
    /**
     * Get all available values for the enum.
     *
     * @return array<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get all available labels for the enum.
     *
     * @return array<string, string>
     */
    public static function labels(): array
    {
        return array_combine(
            self::values(),
            array_map(fn ($case) => $case->label(), self::cases())
        );
    }
}
