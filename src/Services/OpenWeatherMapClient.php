<?php

namespace App\Services;

use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Communicate with OpenWeatherMap Api
 */
class OpenWeatherMapClient
{
    private $client;
    private $endpoint;
    private $apiKey;


    /**
     * @param HttpClientInterface $client
     * @param string $openWeatherMapUrl
     * @param string $openWeatherMapKey
     */
    public function __construct(HttpClientInterface $client, string $openWeatherMapUrl, string $openWeatherMapKey)
    {
        $this->client = $client;
        $this->endpoint = rtrim($openWeatherMapUrl, '/');
        $this->apiKey = $openWeatherMapKey;
    }

    /**
     * @param string $functionName
     * @param array $params
     * @return object
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function get(string $functionName, array $params = []) : object
    {
        $endpoint = $this->buildFinalEndpoint($functionName, $params);
        $response = $this->client->request(
            'GET', $endpoint
        );

        if ($response->getStatusCode() !== 200) {
            if ($response->getStatusCode() === 404) {
                throw new \RuntimeException("Api Route Not Found");
            }
            throw new \RuntimeException(sprintf('Unable to retrieve weather information with content : %s .', $response->getContent()));
        }

        return json_decode($response->getContent());
    }

    /**
     * @param string $functionName
     * @param array $params
     * @return string
     */
    private function buildFinalEndpoint(string $functionName, array $params) : string
    {
        $params['appid'] = $this->apiKey;
        $params['units'] = "metric";
        return $this->endpoint . $functionName . '?' .http_build_query($params);
    }
}