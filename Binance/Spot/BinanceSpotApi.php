<?php

namespace CryptoExchanges\Binance\Spot;

use CryptoExchanges\Core\RouteConfigNotFoundException;
use CryptoExchanges\Core\ExchangeApi;
use CryptoExchanges\Core\ApiKeyInterface;

class BinanceSpotApi extends ExchangeApi
{
    public const EXCHANGE_NAME = "Binance";

    public function callApi(string $routeName, ApiKeyInterface $apiKey, array $params = [])
    {
        $routeConfig = $this->fetchConfig()['name'][$routeName];

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

    protected function getFilePath()
    {
        return __DIR__ . "/binance_spot_api_v1.json";
    }
}