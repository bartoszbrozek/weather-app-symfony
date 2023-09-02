<?php

namespace App\Module\Weather\Filter;

use App\Application\Facade\HandleWeatherForecastData;
use App\Module\Weather\ValueObject\City;
use App\Module\Weather\ValueObject\Country;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'weather:fetch:today',
    description: 'Fetches today\'s weather forecast',
    hidden: false,
)]
class FetchWeatherDataCommand extends Command
{
    public function __construct(
        private readonly HandleWeatherForecastData $weatherForecastDataHandler,
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $this->weatherForecastDataHandler->handle(
                new WeatherForecastFilter(
                    city: new City('New York'),
                    country: new Country('US'),
                    createdAt: new \DateTimeImmutable(),
                ),
                [
                    'app.weather.query.adapter.open_weather_api',
                    'app.weather.query.adapter.dummy',
                ],
            );

            return Command::SUCCESS;
        } catch (\Throwable $t) {
            dd($t);
            return Command::INVALID;
        }
    }
}
