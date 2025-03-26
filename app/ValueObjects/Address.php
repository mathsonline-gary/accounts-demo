<?php

namespace App\ValueObjects;

class Address
{
    public ?string $lineOne;

    public ?string $lineTwo;

    public ?string $city;

    public ?string $state;

    public ?string $postalCode;

    public ?Country $country;

    public function __construct(
        ?string $lineOne = null,
        ?string $lineTwo = null,
        ?string $city = null,
        ?string $state = null,
        ?string $postalCode = null,
        ?Country $country = null
    ) {
        $this->lineOne = $lineOne;
        $this->lineTwo = $lineTwo;
        $this->city = $city;
        $this->state = $state;
        $this->postalCode = $postalCode;
        $this->country = $country;
    }

    public function toString(): string
    {
        $address = $this->lineOne;

        if ($this->lineTwo !== null) {
            $address .= ', '.$this->lineTwo;
        }

        $address .= ', '.$this->city.', '.$this->state.' '.$this->postalCode;

        if ($this->country !== null) {
            $address .= ' '.$this->country->codeAlphaTwo;
        }

        return $address;
    }
}
