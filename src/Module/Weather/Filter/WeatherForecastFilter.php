<?php

namespace App\Module\Weather\Filter;

use DateTime;
use App\Module\Weather\ValueObject\City;
use App\Module\Weather\ValueObject\Country;

final readonly class WeatherForecastFilter
{
    public function __construct(
        public City $city,
        public Country $country,
    )
    {
    }
}
