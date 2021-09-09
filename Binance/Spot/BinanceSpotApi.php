<?php

namespace CryptoExchanges\Binance\Spot;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use CryptoExchanges\Core\UrlEncoder;
use CryptoExchanges\Core\RouteConfigNotFoundException;
use CryptoExchanges\Core\ExchangeInterface;
use CryptoExchanges\Core\ExchangeApi;
use CryptoExchanges\Core\ApiKeyInterface;

class BinanceSpotApi extends ExchangeApi
{
    public const EXCHANGE_NAME = "Binance";

    private UrlEncoder $urlEncoder;

    public function __construct(ExchangeInterface $exchange, HttpClientInterface $client, UrlEncoder $urlEncoder)
    {
        parent::__construct($exchange, $client);
        
        $this->urlEncoder = $urlEncoder;
    }

    public function callApi(string $routeName, ?ApiKeyInterface $apiKey, array $params = [])
    {
        $routeConfig = $this->retrieveRoute($routeName);

        if ($routeConfig == null) {
            throw new RouteConfigNotFoundException(self::EXCHANGE_NAME, $routeName);
        }

        $requestConfig = $routeConfig['request'];

        $url = $this->exchange->getBaseUrl() . \join('/', $routeConfig['url']['path']);
        $header = $this->generateHeader($requestConfig['header'], $apiKey);

        if (isset($requestConfig['url']['query'])) {
            foreach ($requestConfig['url']['query'] as $query) {
                if ($query['key'] == 'timestamp') {
                    $params['timestamp'] = time() * 1000; //Add timestamp parameter
                }

                if ($query['key'] == 'signature') {
                    $params['signature'] = $this->generateSign($this->urlEncoder->urlEncode($params), $apiKey);
                }
            }
        }

        return $this->client->request($requestConfig['method'], $url, [
            'headers' => $header,
            'query' => $params
        ]);
    }

    protected function getRouteConfigFilePath()
    {
        return __DIR__ . "/binance_spot_api_v1.json";
    }

    private function generateHeader(array $requiredHeaders, ApiKeyInterface $apiKey)
    {
        $header = [
            'Content-Type' => 'application/json'
        ];

        foreach ($requiredHeaders as $requiredHeader) {
            if ($requiredHeader['X-MBX-APIKEY']) {
                $header['X-MBX-APIKEY'] = $apiKey->getPublicKey();
            }
        }

        return $header;
    }

    private function generateSign($data, ApiKeyInterface $apiKey)
    {
        return \hash_hmac('SHA256', $data, $apiKey->getPrivateKey());
    }

    private function retrieveRoute(string $routeName)
    {
        $routeConfigs = $this->fetchRouteConfigs();

        $result = null;
        $index = 0;

        while ($result === null && $index < \count($routeConfigs)) {
            if ($routeConfigs[$index]['name'] == $routeName) {
                $result = $routeConfigs[$index];
            }

            $index++;
        }
        
        return $result;
    }

    protected function getOpenOrderRouteName()
    {
        return "New Order";
    }

    protected function getCancelOrderRouteName()
    {
        return "Cancel Order";
    }

    protected function getQueryOrderRouteName()
    {
        return "Query Order (USER_DATA)";
    }

    protected function getCurrentOrderRouteName()
    {
        return "Current Open Orders (USER_DATA)";
    }

    protected function getAllOrderRouteName()
    {
        return "All Orders (USER_DATA)";
    }

    protected function getOrderBookRouteName()
    {
        return "Order Book";
    }

    protected function getCandlestickDataRouteName()
    {
        return "Kline/Candlestick Data";
    }

    protected function getCurrentPriceRouteName()
    {
        return "Current Average Price";
    }
}