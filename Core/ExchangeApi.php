<?php

namespace PHPCryptoExchanges\Core;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Yaml\Yaml;

abstract class ExchangeApi implements ExchangeApiInterface
{
    protected HttpClientInterface $client;

    protected ExchangeInterface $exchange;

    protected ApiKeyInterface $apiKey;

    public function __construct(ExchangeInterface $exchange, HttpClientInterface $client, ApiKeyInterface $apiKey)
    {
        $this->client = $client;
        $this->exchange = $exchange;
        $this->apiKey = $apiKey;
    }

    abstract public function callApi(string $endpoint, string $method = 'GET', array $params = []);

    /**
     * Function to get the config file path
     *
     * @return string
     */
    abstract protected function getConfigFilePath();

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
     * Function to find the config route
     *
     * @param string $endpoint
     * @param string $method
     * @return array|null
     */
    protected function findRouteConfig(string $endpoint, string $method)
    {
        $url = $this->getConfigFilePath();

        $configs = Yaml::parseFile($url);

        foreach ($configs['routes'] as $route) {
            if ($endpoint === $route['route'] && $method === $route['method']) {
                return $route;
            }
        }

        throw new \ConfigFileNotFoundException($this->exchange->getName());
    }
}