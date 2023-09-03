<?php

namespace App\Controller;

use App\Application\Facade\HandleWeatherForecastData;
use App\Entity\WeatherForecast;

use App\Form\Type\WeatherForecastType;
use App\Module\Weather\Filter\WeatherForecastFilter;
use App\Module\Weather\ValueObject\City;
use App\Module\Weather\ValueObject\Country;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

class WeatherController extends AbstractController
{
    /**
     * @throws Throwable
     */
    #[Route(path: '/', name: 'weather_main')]
    public function index(Request $request, HandleWeatherForecastData $weatherForecastData): Response
    {
        $weatherForecastEntity = new WeatherForecast();

        $form = $this->createForm(WeatherForecastType::class, $weatherForecastEntity);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                /** @var WeatherForecast $weatherForecastEntity */
                $weatherForecastEntity = $form->getData();

                $results = $weatherForecastData->handle(
                    new WeatherForecastFilter(
                        city: new City($weatherForecastEntity->getCity()),
                        country: new Country($weatherForecastEntity->getCountry()),
                        createdAt: new \DateTimeImmutable(),
                    ),
                    [
                        'app.weather.query.adapter.open_weather_api',
                        'app.weather.query.adapter.dummy',
                    ],
                );

                return $this->render('weather/results.html.twig', [
                    'results' => $results,
                ]);
            } catch (Throwable $t) {
                return $this->render('weather/error.html.twig', [
                    'error' => $t->getMessage(),
                ]);
            }
        }

        return $this->render('weather/main.html.twig', [
            'form' => $form,
        ]);
    }
}
