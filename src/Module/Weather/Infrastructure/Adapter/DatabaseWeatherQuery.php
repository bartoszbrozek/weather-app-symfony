<?php

namespace App\Module\Weather\Infrastructure\Adapter;

use App\Module\Weather\Contract\WeatherQuery;
use App\Module\Weather\DTO\WeatherForecastData;
use App\Module\Weather\Filter\WeatherForecastFilter;
use App\Module\Weather\ValueObject\City;
use App\Module\Weather\ValueObject\Country;
use App\Repository\WeatherForecastRepository;

final readonly class DatabaseWeatherQuery implements WeatherQuery
{
    public function __construct(private WeatherForecastRepository $repository)
    {
    }

    public function getWeather(WeatherForecastFilter $filter): ?WeatherForecastData
    {
        $entity = $this->repository->findOneByFilter($filter);

        if (!$entity) {
            return null;
        }

        return new WeatherForecastData(
            dateTime: $entity->getCreatedAt(),
            city: new City($entity->getCity()),
            country: new Country($entity->getCountry()),
            temperature: $entity->getTemperature(),
        );
    }
}
