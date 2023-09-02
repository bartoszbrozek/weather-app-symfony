<?php

namespace App\Module\Weather\Infrastructure;

use App\Entity\WeatherForecast;
use App\Module\Weather\Contract\WeatherPersistance;
use App\Module\Weather\DTO\WeatherForecastData;
use Doctrine\ORM\EntityManagerInterface;

final readonly class DoctrineWeatherPersistance implements WeatherPersistance
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function store(WeatherForecastData $weatherForecastData): void
    {
        $entity = new WeatherForecast();
        $entity
            ->setCountry($weatherForecastData->country->value)
            ->setCity($weatherForecastData->city->value)
            ->setTemperature($weatherForecastData->temperature)
            ->setCreatedAt($weatherForecastData->dateTime);

        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }
}
