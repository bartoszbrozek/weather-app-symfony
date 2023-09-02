<?php

namespace App\Module\Weather\Filter;

use App\Module\Weather\Contract\WeatherQuery;
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
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $data = $this->weatherQuery->getWeather(
                new WeatherForecastFilter(
                    city: new City('New York'),
                    country: new Country('USA'),
                )
            );

            return Command::SUCCESS;
        } catch (\Throwable $t) {
            return Command::INVALID;
        }
    }
}
