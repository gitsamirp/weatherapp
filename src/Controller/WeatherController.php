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
     * @Route("/city", name="weather")
     */
    public function showWeather(Environment $twig, OpenWeatherMapService $openWeatherMapService, Request $request): Response
    {
        if ($cityId = $request->query->get("cityId")) {
            return new Response($twig->render('weather/weather.html.twig', [
                'weather' => $openWeatherMapService->findWeatherByCityId($cityId)
            ]));
        }
        return new Response(0);
    }

    /**
     * @Route("/cities", name="citysearch")
     */
    public function retrieveCities(Environment $twig, OpenWeatherMapService $openWeatherMapService, Request $request)
    {
        //$request
        if ($searchQuery = $request->query->get("citySearch")) {
            return new Response($twig->render('weather/citiesDropdown.html.twig', [
                'cities' => $openWeatherMapService->findCitiesByName($searchQuery),
            ]));
        }

        return new Response(0);
    }

}
