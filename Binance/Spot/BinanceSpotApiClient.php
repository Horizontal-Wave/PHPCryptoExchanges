<?php

namespace CryptoExchanges\Binance\Spot;

use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use CryptoExchanges\Core\Utils\UrlEncoder;
use CryptoExchanges\Core\Exceptions\RouteConfigNotFoundException;
use CryptoExchanges\Core\Exceptions\ExchangeApiResponseException;
use CryptoExchanges\Core\EntityInterfaces\ExchangeEntityInterface;
use CryptoExchanges\Core\EntityInterfaces\ApiKeyEntityInterface;
use CryptoExchanges\Core\Client\ExchangeApiClient;

class BinanceSpotApiClient extends ExchangeApiClient
{
    public const EXCHANGE_NAME = "Binance";

    private UrlEncoder $urlEncoder;

    public function __construct(ExchangeEntityInterface $exchange, HttpClientInterface $client, UrlEncoder $urlEncoder)
    {
        parent::__construct($exchange, $client);
        
        $this->urlEncoder = $urlEncoder;
    }

    public function callApi(string $routeName, ?ApiKeyEntityInterface $apiKey, array $params = []) : array
    {
        $routeConfig = $this->retrieveRoute($routeName);

        if ($routeConfig == null) {
            throw new RouteConfigNotFoundException(self::EXCHANGE_NAME, $routeName);
        }

        $requestConfig = $routeConfig['request'];

        $url = $this->exchange->getBaseUrl() . \join('/', $routeConfig['url']['path']);
        $headers = $this->generateHeader($requestConfig['header'], $apiKey);

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

        $response = $this->client->request($requestConfig['method'], $url, [
            'headers' => $headers,
            'body' => $params
        ]);

        if ($response->getStatusCode() >= 300 || $response->getStatusCode() < 200) {
            throw new ExchangeApiResponseException($response->getStatusCode(), $this->exchange->getName(), $url);
        }

        return \json_decode($response->getContent(), true);
    }

    protected function getRouteConfigFilePath() : string
    {
        return __DIR__ . "/binance_spot_api_v1.json";
    }

    private function generateHeader(array $requiredHeaders, ApiKeyEntityInterface $apiKey) : array
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

    private function generateSign($data, ApiKeyEntityInterface $apiKey) : string
    {
        return \hash_hmac('SHA256', $data, $apiKey->getPrivateKey());
    }

    private function retrieveRoute(string $routeName) : array
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

    protected function getOpenOrderRouteName() : string
    {
        return "New Order";
    }

    protected function getCancelOrderRouteName() : string
    {
        return "Cancel Order";
    }

    protected function getQueryOrderRouteName() : string
    {
        return "Query Order (USER_DATA)";
    }

    protected function getCurrentOrderRouteName() : string
    {
        return "Current Open Orders (USER_DATA)";
    }

    protected function getAllOrderRouteName() : string
    {
        return "All Orders (USER_DATA)";
    }

    protected function getOrderBookRouteName() : string
    {
        return "Order Book";
    }

    protected function getCandlestickDataRouteName() : string
    {
        return "Kline/Candlestick Data";
    }

    protected function getCurrentPriceRouteName() : string
    {
        return "Current Average Price";
    }
}