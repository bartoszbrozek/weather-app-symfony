<?php

namespace App\Module\Weather\Contract;

use App\Module\Weather\DTO\WeatherForecastData;

interface WeatherService
{
    public function storeWeatherForecast(WeatherForecastData $weatherForecastData): void;
}
