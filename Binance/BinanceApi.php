<?php

namespace PHPCryptoExchanges\Binance;

use PHPCryptoExchanges\Core\ExchangeApi;

class BinanceApi extends ExchangeApi
{
    public const EXCHANGE_NAME = "Binance";

    protected function getConfigFilePath()
    {
        return __DIR__ . "/binance.config.yaml";
    }

    public function callApi(string $endpoint, string $method = 'GET', array $params = [])
    {
        $routeConfig = $this->findRouteConfig($endpoint, $method);

        $url = $this->exchange->getBaseUrl() . $endpoint;
        $header = $this->generateHeader($routeConfig);

        if (isset($routeConfig['param']) && isset($routeConfig['param']['timestamp']) && $routeConfig['param']['timestamp'] = true) {
            $params['timestamp'] = time() * 1000; //Add timestamp parameter
        }

        if (isset($routeConfig['param']) && isset($routeConfig['param']['signature']) && $routeConfig['param']['signature'] = true) {
            $params['signature'] = $this->generateSign($this->urlEncode($params));
        }

        $response = $this->client->request($routeConfig['method'], $url, [
            'headers' => $header,
            'query' => $params
        ]);

        return $response;
    }

    private function generateHeader($data)
    {
        $header = [
            'Content-Type' => 'application/json'
        ];

        if ($data['apiKey'] === true) {
            $header['X-MBX-APIKEY'] = $this->apiKey->getPublicKey();
        }

        return $header;
    }

    private function generateSign($data)
    {
        return \hash_hmac('SHA256', $data, $this->apiKey->getPrivateKey());
    }
}