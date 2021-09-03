<?php

namespace CryptoExchanges\Binance;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use CryptoExchanges\Core\RouteConfigNotFoundException;
use CryptoExchanges\Core\ExchangeInterface;
use CryptoExchanges\Core\ExchangeApi;
use CryptoExchanges\Core\ApiKeyInterface;

class BinanceApi extends ExchangeApi
{
    public const EXCHANGE_NAME = "Binance";

    public function __construct(ExchangeInterface $exchange, HttpClientInterface $client, BinanceApiRoute $binanceApiRoute)
    {
        parent::__construct($exchange, $client);

        $this->exchangeApiRoute = $binanceApiRoute;
    }

    public function callApi(string $routeName, ApiKeyInterface $apiKey, array $params = [])
    {
        $routeConfig = $this->exchangeApiRoute->getRoutes()[$routeName];

        if ($routeConfig == null) {
            throw new RouteConfigNotFoundException(self::EXCHANGE_NAME, $routeName);
        }

        $url = $this->exchange->getBaseUrl() . $routeConfig['endpoint'];
        $header = $this->generateHeader($routeConfig, $apiKey);

        if (isset($routeConfig['param']) && isset($routeConfig['param']['timestamp']) && $routeConfig['param']['timestamp'] = true) {
            $params['timestamp'] = time() * 1000; //Add timestamp parameter
        }

        if (isset($routeConfig['param']) && isset($routeConfig['param']['signature']) && $routeConfig['param']['signature'] = true) {
            $params['signature'] = $this->generateSign($this->urlEncode($params), $apiKey);
        }

        return $this->client->request($routeConfig['method'], $url, [
            'headers' => $header,
            'query' => $params
        ]);
    }

    private function generateHeader(array $data, ApiKeyInterface $apiKey)
    {
        $header = [
            'Content-Type' => 'application/json'
        ];

        if ($data['apiKey'] === true) {
            $header['X-MBX-APIKEY'] = $apiKey->getPublicKey();
        }

        return $header;
    }

    private function generateSign($data, ApiKeyInterface $apiKey)
    {
        return \hash_hmac('SHA256', $data, $apiKey->getPrivateKey());
    }
}