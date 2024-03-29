<?php

namespace App\Module\Weather\Infrastructure\Adapter;

use App\Module\Weather\Contract\WeatherQuery;
use App\Module\Weather\DTO\WeatherForecastData;
use App\Module\Weather\Exception\WeatherForecastFetchFailure;
use App\Module\Weather\Filter\WeatherForecastFilter;
use App\Module\Weather\ValueObject\City;
use App\Module\Weather\ValueObject\Country;
use Cmfcmf\OpenWeatherMap;
use DateTimeImmutable;
use Exception;
use GuzzleHttp\Psr7\HttpFactory;
use Http\Adapter\Guzzle7\Client;
use Throwable;

final readonly class OpenWeatherApiQuery implements WeatherQuery
{
    public function __construct(private string $apiKey)
    {
    }

    public function getWeather(WeatherForecastFilter $filter): WeatherForecastData
    {
        try {
            $weather = $this->client()->getWeather(
                sprintf('%s,%s', $filter->city->value, $filter->country->value),
                'metric',
                'en',
            );

            return new WeatherForecastData(
                dateTime: new DateTimeImmutable(),
                city: new City($weather->city->name),
                country: new Country($weather->city->country),
                temperature: $weather->temperature->now->getValue(),
            );
        } catch (Throwable $t) {
            throw new WeatherForecastFetchFailure(
                message: $t->getMessage(),
                previous: $t,
            );
        }
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function getWeatherAverage(WeatherForecastFilter $filter): ?WeatherForecastData
    {
        return $this->getWeather($filter);
    }

    private function client(): OpenWeatherMap
    {
        return new OpenWeatherMap($this->apiKey, new Client(), new HttpFactory());
    }
}
