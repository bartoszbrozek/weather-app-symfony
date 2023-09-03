# Symfony Weather App

A simple weather app, allowing to show temperature based on country and city based on average data from multiple sources.

## Getting Started

- Configure .env file based on .env.dist

1. Run `docker compose build --no-cache` to build fresh images
2. Run `docker compose up --pull --wait` to start the project
3. Open `https://localhost/`
4. Run `docker compose down --remove-orphans` to stop the Docker containers.

## Weather Data Sources

1. `open_weather_api` - gets data from Open Weather API based on country and city
2. `dummy` - provides random temperature

You can easily add a new external data source by configuring `services.yaml` and providing a `WeatherQuery` implementation.

## Data & Cache

Application tries to call available external data sources in order to get weather data (if it is not available in local storage),
which is then saved to configured local storage (in this case it is relational database).

If there are cached results in redis, then app will use redis to provide data.
Otherwise database query will be made, cache will be updated and then user will get results.
