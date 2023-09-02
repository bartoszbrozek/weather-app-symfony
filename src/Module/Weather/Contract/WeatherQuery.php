<?php

namespace App\Module\Weather\Contract;

use App\Module\Weather\DTO\WeatherForecastData;
use App\Module\Weather\Exception\WeatherForecastFetchFailure;
use App\Module\Weather\Filter\WeatherForecastFilter;

interface WeatherQuery
{
    /**
     * @throws WeatherForecastFetchFailure
     */
    public function getWeather(WeatherForecastFilter $filter): WeatherForecastData;
}
