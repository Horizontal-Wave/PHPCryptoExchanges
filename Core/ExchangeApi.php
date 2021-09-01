<?php

namespace PHPCryptoExchanges\Core;

use Symfony\Contracts\HttpClient\HttpClientInterface;

abstract class ExchangeApi implements ExchangeApiInterface
{
    protected HttpClientInterface $client;

    protected ExchangeInterface $exchange;

    protected ExchangeApiRouteInterface $exchangeApiRoute;

    public function __construct(ExchangeInterface $exchange, HttpClientInterface $client)
    {
        $this->client = $client;
        $this->exchange = $exchange;
    }

    abstract public function callApi(string $routeName, ApiKeyInterface $apiKey, array $params = []);

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
}