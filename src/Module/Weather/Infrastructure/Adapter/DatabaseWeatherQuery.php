<?php

namespace App\Module\Weather\Infrastructure\Adapter;

use App\Module\Weather\Contract\WeatherQuery;
use App\Module\Weather\DTO\WeatherForecastData;
use App\Module\Weather\Filter\WeatherForecastFilter;
use App\Module\Weather\ValueObject\City;
use App\Module\Weather\ValueObject\Country;
use App\Repository\WeatherForecastRepository;
use Exception;

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

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function getWeatherAverage(WeatherForecastFilter $filter): ?WeatherForecastData
    {
        $entities = $this->repository->findAllByFilter($filter);

        if (empty($entities)) {
            return null;
        }

        return new WeatherForecastData(
            dateTime: $entities[0]['created_at'],
            city: new City($entities[0]['city']),
            country: new Country($entities[0]['country']),
            temperature: array_sum(
                array_map(
                    fn(array $entity) => $entity['temperature'],
                    $entities,
                ),
            ) / count($entities),
        );
    }
}
