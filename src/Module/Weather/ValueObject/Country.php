<?php

namespace App\Module\Weather\ValueObject;

use App\Module\Weather\Exception\CountryNameMustNotBeEmpty;

final readonly class Country
{
    /**
     * @throws CountryNameMustNotBeEmpty
     */
    public function __construct(public string $value)
    {
        if (empty($value)) {
            throw new CountryNameMustNotBeEmpty();
        }
    }
}
