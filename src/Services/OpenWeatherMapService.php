<?php

namespace App\Services;

class OpenWeatherMapService
{
    private $openWeatherMapClient;

    public function __construct(OpenWeatherMapClient $openWeatherMapClient)
    {
        $this->openWeatherMapClient = $openWeatherMapClient;
    }

    public function findCitiesByName(string $name) : array
    {
        //api.openweathermap.org/data/2.5/find?q=Warwick&appid={API key}
        $data = [];
        $cities = $this->openWeatherMapClient->get("/find", ['q' => $name]);

        foreach ($cities->list as $city) {
            $data[$city->id]['name'] = $city->name;
            $data[$city->id]['country'] = $city->sys->country;
        }

        return $data;
    }

    public function findWeatherByCityId(int $cityId) : array
    {
        //api.openweathermap.org/data/2.5/weather?id={city id}&appid={API key}
        $cities = $this->openWeatherMapClient->get("/weather", ['id'=> $cityId]);
        $data = [];

        //Current weather conditions
        $data['weather'] = $cities->weather[0];

        //The current temperature, in celsius, “Feels like” temperature, in celsius, Humidity percentage.
        //Minimum temperature, in celsius, Maximum temperature, in celsius.
        $data['temperature'] = $cities->main;

        //Wind speed, in miles per hour.
        if (property_exists($cities, "wind")) {
            $data['wind'] = $cities->wind->speed * 2.23693; //currently in meters per second, need to convert to miles/hour
        }

        //Rain volume for the last hour, in millimeters.
        if (property_exists($cities, "rain")) {
            $data['rain'] = $cities->rain->{'1h'};
        }

        return $data;
    }
}