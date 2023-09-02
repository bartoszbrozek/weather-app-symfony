<?php

namespace App\Module\Weather\ValueObject;

use App\Module\Weather\Exception\CityNameMustNotBeEmpty;

final readonly class City
{
    /**
     * @throws CityNameMustNotBeEmpty
     */
    public function __construct(public string $value)
    {
        if (empty($value)) {
            throw new CityNameMustNotBeEmpty();
        }
    }
}
