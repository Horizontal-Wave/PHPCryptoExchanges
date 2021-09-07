<?php

namespace CryptoExchanges\Core;

use Symfony\Contracts\HttpClient\HttpClientInterface;

abstract class ExchangeApi implements ExchangeApiInterface
{
    protected HttpClientInterface $client;

    protected ExchangeInterface $exchange;

    public function __construct(ExchangeInterface $exchange, HttpClientInterface $client)
    {
        $this->client = $client;
        $this->exchange = $exchange;
    }

    abstract public function callApi(string $routeName, ApiKeyInterface $apiKey, array $params = []);

    abstract protected function getFilePath();

    /**
     * Function to generate the body
     *
     * @param array $params
     * @return string
     */
    protected function urlEncode(array $params = [])
    {
        foreach ($params as $key => $value) {
            if (is_bool($value)) {
                $params[$key] = var_export($value, true);
            }
        }

        return http_build_query($params);
    }

    /**
     * Method to fetch the routes
     *
     * @return array
     */
    protected function fetchRouteConfigs() 
    {
        $filePath = $this->getFilePath();

        $data = \file_get_contents($filePath);

        $json = \json_decode($data, true);

        $result = [];

        foreach ($json['item'] as $folder) {
            foreach($folder as $items) {
                $result = \array_merge($result, $items);
            }
        }

        return $result;
    }
}