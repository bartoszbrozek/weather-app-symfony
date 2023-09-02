<?php

namespace App\Module\Weather\Filter;

use DateTimeImmutable;
use App\Module\Weather\ValueObject\City;
use App\Module\Weather\ValueObject\Country;

final readonly class WeatherForecastFilter
{
    public function __construct(
        public ?City $city = null,
        public ?Country $country = null,
        public ?DateTimeImmutable $createdAt = null,
    )
    {
    }
}
