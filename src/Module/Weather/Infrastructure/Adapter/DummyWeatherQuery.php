<?php

namespace App\Module\Weather\Infrastructure\Adapter;

use App\Module\Weather\Contract\WeatherQuery;
use App\Module\Weather\DTO\WeatherForecastData;
use App\Module\Weather\Filter\WeatherForecastFilter;
use Exception;

final readonly class DummyWeatherQuery implements WeatherQuery
{
    /**
     * @throws Exception
     */
    public function getWeather(WeatherForecastFilter $filter): ?WeatherForecastData
    {
        return new WeatherForecastData(
            dateTime: new \DateTimeImmutable(),
            city: $filter->city,
            country: $filter->country,
            temperature: (float)random_int(-25, 40),
        );
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function getWeatherAverage(WeatherForecastFilter $filter): ?WeatherForecastData
    {
        return $this->getWeather($filter);
    }
}
