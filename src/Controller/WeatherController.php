<?php

namespace App\Controller;

use App\Services\OpenWeatherMapService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class WeatherController extends AbstractController
{

    /**
     * @Route("/", name="home")
     */
    public function index(Environment $twig): Response
    {
        return new Response($twig->render('weather/index.html.twig', [
            'controller_name' => 'WeatherController',
        ]));
    }

    /**
     * @Route("/weather/city/{cityId}", name="weather_city", options={"expose"=true})
     */
    public function showWeather(int $cityId, Environment $twig, OpenWeatherMapService $openWeatherMapService, Request $request): Response
    {
        if ($cityId) {
            return new Response($twig->render('weather/weather.html.twig', [
                'weather' => $openWeatherMapService->findWeatherByCityId($cityId)
            ]));
        }
        return new Response(0);
    }

    /**
     * @Route("/city/find", name="search_city_name", options={"expose"=true})
     */
    public function retrieveCities(Environment $twig, OpenWeatherMapService $openWeatherMapService, Request $request) : Response
    {
        if ($searchQuery = $request->query->get("citySearch")) {
            return new Response($twig->render('weather/citiesDropdown.html.twig', [
                'cities' => $openWeatherMapService->findCitiesByName($searchQuery),
            ]));
        }

        return new Response(0);
    }

}
