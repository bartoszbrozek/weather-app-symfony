<?php

namespace App\Module\Weather\DTO;

use App\Module\Weather\ValueObject\City;
use App\Module\Weather\ValueObject\Country;
use DateTime;

final readonly class WeatherForecastData
{
    public function __construct(
        public DateTime $dateTime,
        public City $city,
        public Country $country,
        public float $temperature,
    )
    {
    }
}
