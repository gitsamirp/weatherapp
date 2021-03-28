<?php

namespace App\Services;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class OpenWeatherMapClient
{
    private $client;
    private $endpoint;
    private $apiKey;

    public function __construct(HttpClientInterface $client, string $openWeatherMapUrl, string $openWeatherMapKey)
    {
        $this->client = $client;
        $this->endpoint = rtrim($openWeatherMapUrl, '/');
        $this->apiKey = $openWeatherMapKey;
    }

    public function get(string $functionName, array $params = []) : object
    {
        $endpoint = $this->buildFinalEndpoint($functionName, $params);
        $response = $this->client->request(
            'GET', $endpoint
        );

        if ($response->getStatusCode() !== 200) {
            if ($response->getStatusCode() === 404) {
                throw \RuntimeException("Api Route Not Found");
            }
            throw new \RuntimeException(sprintf('Unable to retrieve weather information with content : %s .', $response->getContent()));
        }

        return json_decode($response->getContent());
    }

    private function buildFinalEndpoint(string $functionName, array $params) : string
    {
        $params['appid'] = $this->apiKey;
        $params['units'] = "metric";
        return $this->endpoint . $functionName . '?' .http_build_query($params);
    }
}