<?php

namespace App\Module\Weather\Contract;

use App\Module\Weather\DTO\WeatherForecastData;

interface WeatherPersistance
{
    public function store(WeatherForecastData $weatherForecastData): void;
}
