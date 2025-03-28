<?php

namespace App\ValueObjects;

class Price
{
    public string $currency;

    public float $amount;

    /**
     * Construct a new Price instance.
     */
    public function __construct(float $amount, string $currency)
    {
        $this->amount = $amount;
        $this->currency = $currency;
    }
}
