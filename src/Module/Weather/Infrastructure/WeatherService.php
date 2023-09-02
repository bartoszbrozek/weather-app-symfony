<?php

namespace App\Module\Weather\Infrastructure;

use App\Module\Weather\Contract\WeatherPersistance;
use App\Module\Weather\Contract\WeatherService as WeatherServiceInterface;
use App\Module\Weather\DTO\WeatherForecastData;
use App\Module\Weather\Exception\CouldNotStoreWeatherForecast;
use Throwable;

final readonly class WeatherService implements WeatherServiceInterface
{
    public function __construct(private WeatherPersistance $weatherPersistance)
    {
    }

    /**
     * @throws CouldNotStoreWeatherForecast
     */
    public function storeWeatherForecast(WeatherForecastData $weatherForecastData): void
    {
        try {
            $this->weatherPersistance->store($weatherForecastData);
        } catch (Throwable $t) {
            throw new CouldNotStoreWeatherForecast(previous: $t);
        }
    }
}
