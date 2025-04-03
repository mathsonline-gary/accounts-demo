<?php

namespace App\Traits;

use App\Enums\Brand;
use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasBrand
{
    /**
     * interact with the brand attribute.
     */
    public function brand(): Attribute
    {
        return Attribute::make(
            get: fn (int $value) => Brand::from($value),
            set: fn (Brand $value) => $value->value,
        );
    }
}
