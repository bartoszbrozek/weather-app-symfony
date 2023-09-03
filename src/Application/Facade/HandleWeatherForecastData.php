<?php

namespace App\Application\Facade;

use App\Kernel;
use App\Module\Weather\Contract\WeatherQuery;
use App\Module\Weather\Contract\WeatherService;
use App\Module\Weather\DTO\WeatherForecastData;
use App\Module\Weather\Exception\WeatherForecastDataNotFound;
use App\Module\Weather\Filter\WeatherForecastFilter;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Throwable;

/**
 * Handle weather forecast data by checking its existence in local storage.
 * If no data is found in local storage: fetch it from external sources and save to database
 */
final readonly class HandleWeatherForecastData
{
    public function __construct(
        private WeatherService $weatherService,

        #[Autowire(service: 'app.weather.query.adapter.database')]
        private WeatherQuery $weatherLocalDataSource,
        private Connection $dbConnection,
        private Kernel $kernel,
    )
    {
    }

    /**
     * @param WeatherForecastFilter $filter
     * @param array $weatherExternalDataSources
     * @return WeatherForecastData
     * @throws Exception
     * @throws Throwable
     */
    public function handle(WeatherForecastFilter $filter, array $weatherExternalDataSources): WeatherForecastData
    {
        try {
            $this->dbConnection->setNestTransactionsWithSavepoints(true);
            $this->dbConnection->beginTransaction();
            $localData = $this->weatherLocalDataSource->getWeather($filter);

            if (!$localData) {
                /** @var string $weatherExternalDataSource */
                foreach ($weatherExternalDataSources as $weatherExternalDataSource) {
                    /** @var WeatherQuery $t */
                    $weatherQuery = $this->kernel->getContainer()->get($weatherExternalDataSource);
                    $data = $weatherQuery->getWeather($filter);
                    $this->weatherService->storeWeatherForecast($data);
                }

            }
            $this->dbConnection->commit();

            $averageWeather = $this->weatherLocalDataSource->getWeatherAverage($filter);
            if (empty($averageWeather)) {
                throw new WeatherForecastDataNotFound();
            }

            return $averageWeather;
        } catch (Throwable $t) {
            if ($this->dbConnection->isTransactionActive()) {
                $this->dbConnection->rollBack();
            }

            throw $t;
        }
    }
}
