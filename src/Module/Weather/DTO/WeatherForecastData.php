<?php

namespace App\Module\Weather\DTO;

use App\Module\Weather\ValueObject\City;
use App\Module\Weather\ValueObject\Country;
use DateTimeImmutable;

final readonly class WeatherForecastData
{
    public function __construct(
        public DateTimeImmutable $dateTime,
        public City $city,
        public Country $country,
        public float $temperature,
    )
    {
    }
}
