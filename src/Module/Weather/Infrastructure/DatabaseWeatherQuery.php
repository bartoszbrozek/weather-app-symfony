<?php

namespace App\Module\Weather\Infrastructure;

use App\Module\Weather\Contract\WeatherQuery;
use App\Module\Weather\DTO\WeatherForecastData;
use App\Module\Weather\Filter\WeatherForecastFilter;

final class DatabaseWeatherQuery implements WeatherQuery
{
    public function getWeather(WeatherForecastFilter $filter): WeatherForecastData
    {
        echo 1;
        die;
    }
}
