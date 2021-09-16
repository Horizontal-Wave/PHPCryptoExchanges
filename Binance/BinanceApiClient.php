<?php

namespace CryptoExchanges\Binance;

use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use CryptoExchanges\Core\Utils\UrlEncoder;
use CryptoExchanges\Core\Exceptions\WeightRateLimitException;
use CryptoExchanges\Core\Exceptions\RouteConfigNotFoundException;
use CryptoExchanges\Core\Exceptions\ExchangeApiErrorResponseException;
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

    /**
     * Method to call the api
     *
     * @param string $routeName
     * @param ApiKeyEntityInterface|null $apiKey
     * @param array $params
     * @return array
     * 
     * @throws RouteConfigNotFoundException
     * @throws WeightRateLimitException
     * @throws ExchangeApiErrorResponseException
     */
    public function callApi(string $routeName, ?ApiKeyEntityInterface $apiKey, array $params = []): array
    {
        $routeConfig = $this->retrieveRoute($routeName);
        $requestConfig = $routeConfig['request'];

        $response = $this->buildRequest($params, $requestConfig, $apiKey);
        return $this->handleResponse($response, $routeConfig);
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

    /**
     * Method to retrieve the route in the config file
     *
     * @param string $routeName
     * @return array
     * 
     * @throws RouteConfigNotFoundException
     */
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

        if ($result == null) {
            throw new RouteConfigNotFoundException($this->exchange->getName(), $routeName);
        }

        return $result;
    }

    /**
     * Method to build the parameters
     *
     * @param array $params
     * @param array $requestConfig
     * @param ApiKeyEntityInterface|null $apiKey
     * @return array
     */
    private function buildParams(array $params, array $requestConfig, ?ApiKeyEntityInterface $apiKey) : array
    {
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

        return $params;
    }

    /**
     * Method to build the request
     *
     * @param array $params
     * @param array $requestConfig
     * @param ApiKeyEntityInterface|null $apiKey
     * @return ResponseInterface
     */
    private function buildRequest(array $params, array $requestConfig, ?ApiKeyEntityInterface $apiKey) : ResponseInterface
    {
        $url = $this->exchange->getBaseUrl() . "/" . \join('/', $requestConfig['url']['path']);
        $headers = $this->generateHeader($requestConfig['header'], $apiKey);

        $params = $this->buildParams($params, $requestConfig, $apiKey);

        return $this->client->request($requestConfig['method'], $url, [
            'headers' => $headers,
            'query' => $requestConfig['method'] === 'GET' ? $params : null,
            'body' => $requestConfig['method'] === 'GET' ? null : $params,
        ]);
    }

    /**
     * Method to handle the response
     *
     * @param ResponseInterface $response
     * @return array
     * 
     * @throws WeightRateLimitException
     * @throws ExchangeApiErrorResponseException
     */
    private function handleResponse(ResponseInterface $response, array $routeConfig) : array
    {
        $responseCode = $response->getStatusCode();

        if ($responseCode >= 300 || $responseCode < 200) {
            if ($responseCode == 429 || $responseCode == 418) {
                throw new WeightRateLimitException($this->exchange->getName(), $routeConfig['name'], $response, "Retry-After");
            }

            throw new ExchangeApiErrorResponseException($this->exchange->getName(), $routeConfig['name'], $response);
        }

        return $response->toArray(false);
    }
}