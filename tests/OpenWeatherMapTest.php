<?php

namespace App\Tests;

use App\Services\OpenWeatherMapClient;
use App\Services\OpenWeatherMapService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\ResponseInterface;

class OpenWeatherMapTest extends TestCase
{

    public function testFindCityInvalidResponse(): void
    {
        $client = new MockHttpClient([
            new MockResponse('...', ['http_code' => 404]),
        ]);
        $openWeatherClient = new OpenWeatherMapClient($client, 'http://example.com', 'abcde');
        $this->expectException(\RuntimeException::class);
        $openWeatherClient->get("/notfind", []);
    }

    /**
     * @dataProvider getCities
     */
    public function testFindCities(array $expectedResult, ResponseInterface $response)
    {
        $client = new MockHttpClient([
            $response, ['http_code' => 200],
        ]);

        $openWeatherMapClient = new OpenWeatherMapClient($client, 'http://example.com', 'abcde');
        $openWeatherService = new OpenWeatherMapService($openWeatherMapClient);
        $result = $openWeatherService->findCitiesByName("Coventry");

        $this->assertSame($expectedResult, $result);
    }

    public function getCities()
    {
        $expectedResult[1] = ["name" => "Coventry", "country" => "GB"];
        $expectedResult[2] = ["name" => "Coventry2", "country" => "GB"];

        $data['cod'] = 200;
        $data['list'][] = ['id' => 1, 'name'=> "Coventry", "sys" => ["country"=>"GB"]];
        $data['list'][] = ['id' => 2, 'name'=> "Coventry2", "sys" => ["country"=>"GB"]];
        $data = json_encode($data);
        $response = new MockResponse($data, ['http_code' => 200]);

        yield 'test' =>[$expectedResult, $response];
    }

}
