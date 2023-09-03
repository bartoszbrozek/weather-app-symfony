<?php

namespace App\Module\Weather\Contract;

use App\Module\Weather\DTO\WeatherForecastData;
use App\Module\Weather\Exception\CityNameMustNotBeEmpty;
use App\Module\Weather\Exception\CountryNameMustNotBeEmpty;
use App\Module\Weather\Exception\WeatherForecastFetchFailure;
use App\Module\Weather\Filter\WeatherForecastFilter;

interface WeatherQuery
{
    /**
     * @throws WeatherForecastFetchFailure
     * @throws CityNameMustNotBeEmpty
     * @throws CountryNameMustNotBeEmpty
     */
    public function getWeather(WeatherForecastFilter $filter): ?WeatherForecastData;

    /**
     * @throws WeatherForecastFetchFailure
     * @throws CityNameMustNotBeEmpty
     * @throws CountryNameMustNotBeEmpty
     */
    public function getWeatherAverage(WeatherForecastFilter $filter): ?WeatherForecastData;
}
