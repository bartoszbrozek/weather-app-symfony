<?php

namespace App\Module\Weather\Filter;

use App\Module\Weather\Contract\WeatherQuery;
use App\Module\Weather\Contract\WeatherQuery as WeatherQuery2;
use App\Module\Weather\Contract\WeatherService;
use App\Module\Weather\Infrastructure\DatabaseWeatherQuery;
use App\Module\Weather\ValueObject\City;
use App\Module\Weather\ValueObject\Country;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

#[AsCommand(
    name: 'weather:fetch:today',
    description: 'Fetches today\'s weather forecast',
    hidden: false,
)]
class FetchWeatherDataCommand extends Command
{
    public function __construct(
        #[Autowire(service: 'app.weather.query.open_weather_api')]
        private readonly WeatherQuery $weatherQuery,

        #[Autowire(service: 'app.weather.query.database')]
        private readonly WeatherQuery2 $weatherQuery2,

        private readonly WeatherService $weatherService,
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {

            $dbData = $this->weatherQuery2->getWeather(
                new WeatherForecastFilter(
                    city: new City('New York'),
                    country: new Country('US'),
                    createdAt: new \DateTimeImmutable(),
                )
            );

            dd($dbData);

            $data = $this->weatherQuery->getWeather(
                new WeatherForecastFilter(
                    city: new City('New York'),
                    country: new Country('US'),
                )
            );

            $this->weatherService->storeWeatherForecast($data);

            return Command::SUCCESS;
        } catch (\Throwable $t) {
            dd($t);
            return Command::INVALID;
        }
    }
}
