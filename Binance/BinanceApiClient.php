<?php

namespace CryptoExchanges\Binance;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use CryptoExchanges\Core\Utils\UrlEncoder;
use CryptoExchanges\Core\Exceptions\RouteConfigNotFoundException;
use CryptoExchanges\Core\Exceptions\ExchangeApiResponseException;
use CryptoExchanges\Core\EntityInterfaces\ExchangeEntityInterface;
use CryptoExchanges\Core\EntityInterfaces\ApiKeyEntityInterface;
use CryptoExchanges\Core\Client\ExchangeApiClient;

abstract class BinanceApiClient extends ExchangeApiClient
{
    private UrlEncoder $urlEncoder;

    public function __construct(ExchangeEntityInterface $exchange, HttpClientInterface $client, UrlEncoder $urlEncoder)
    {
        parent::__construct($exchange, $client);

        $this->urlEncoder = $urlEncoder;
    }

    public function callApi(string $routeName, ?ApiKeyEntityInterface $apiKey, array $params = []): array
    {
        $routeConfig = $this->retrieveRoute($routeName);

        if ($routeConfig == null) {
            throw new RouteConfigNotFoundException($this->exchange->getName(), $routeName);
        }

        $requestConfig = $routeConfig['request'];

        $url = $this->exchange->getBaseUrl() . "/" . \join('/', $requestConfig['url']['path']);
        $headers = $this->generateHeader($requestConfig['header'], $apiKey);

        if (isset($requestConfig['url']['query'])) {
            foreach ($requestConfig['url']['query'] as $query) {
                if ($query['key'] == 'timestamp') {
                    $params['timestamp'] = round(microtime(true) * 1000); //Add timestamp parameter
                }

                if ($query['key'] == 'signature') {
                    $params['signature'] = $this->generateSign($this->urlEncoder->urlEncode($params), $apiKey);
                }
            }
        }

        $response = $this->client->request($requestConfig['method'], $url, [
            'headers' => $headers,
            'query' => $requestConfig['method'] === 'GET' ? $params : null,
            'body' => $requestConfig['method'] === 'GET' ? null : $params,
        ]);

        if ($response->getStatusCode() >= 300 || $response->getStatusCode() < 200) {
            throw new ExchangeApiResponseException($response->getStatusCode(), $this->exchange->getName(), $url);
        }

        return \json_decode($response->getContent(), true);
    }

    private function generateHeader(array $requiredHeaders, ?ApiKeyEntityInterface $apiKey): array
    {
        $header = [
            'Content-Type' => 'application/json'
        ];

        foreach ($requiredHeaders as $requiredHeader) {
            if ($requiredHeader['key'] == 'X-MBX-APIKEY' && $apiKey !== null) {
                $header['X-MBX-APIKEY'] = $apiKey->getPublicKey();
            }
        }

        return $header;
    }

    private function generateSign($data, ApiKeyEntityInterface $apiKey): string
    {
        return \hash_hmac('SHA256', $data, $apiKey->getPrivateKey());
    }

    private function retrieveRoute(string $routeName): array
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
}