<?php

namespace CryptoExchanges\Core\Client;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use CryptoExchanges\Core\EntityInterfaces\ExchangeEntityInterface;
use CryptoExchanges\Core\EntityInterfaces\ApiKeyEntityInterface;

abstract class ExchangeApiClient implements ExchangeApiClientInterface
{
    protected HttpClientInterface $client;

    protected ExchangeEntityInterface $exchange;

    public function __construct(ExchangeEntityInterface $exchange, HttpClientInterface $client)
    {
        $this->client = $client;
        $this->exchange = $exchange;
    }

    abstract public function callApi(string $routeName, ?ApiKeyEntityInterface $apiKey, array $params = []) : array;

    /**
     * Method to get the config file path
     *
     * @return string
     */
    abstract protected function getRouteConfigFilePath() : string;

    /**
     * Method to fetch the routes
     *
     * @return array
     */
    protected function fetchRouteConfigs() : array
    {
        $filePath = $this->getRouteConfigFilePath();

        $data = \file_get_contents($filePath);

        $json = \json_decode($data, true);

        $result = [];

        foreach ($json['item'] as $folder) {
            foreach($folder['item'] as $item) {
                \array_push($result, $item);
            }
        }

        return $result;
    }

    /**
     * Method to get the route name to open a order
     *
     * @return string
     */
    abstract protected function getOpenOrderRouteName() : string;

    /**
     * Method to get the route name to cancel a order
     *
     * @return string
     */
    abstract protected function getCancelOrderRouteName() : string;

    /**
     * Method to get the route name to query a order
     *
     * @return string
     */
    abstract protected function getQueryOrderRouteName() : string;

    /**
     * Method to get the route name to get current open orders
     *
     * @return string
     */
    abstract protected function getCurrentOrderRouteName() : string;

    /**
     * Method to get the route name to get all orders
     *
     * @return string
     */
    abstract protected function getAllOrderRouteName() : string;

    /**
     * Method to get the route name for order book
     *
     * @return string
     */
    abstract protected function getOrderBookRouteName() : string;

    /**
     * Method to get the route name for candlestick datas
     *
     * @return string
     */
    abstract protected function getCandlestickDataRouteName() : string;

    public function openOder(ApiKeyEntityInterface $apiKey, string $symbol, string $side, string $type, array $otherParams)  : array
    {
        $otherParams['symbol'] = $symbol;
        $otherParams['side'] = $side;
        $otherParams['type'] = $type;

        return $this->callApi($this->getOpenOrderRouteName(), $apiKey, $otherParams);
    }

    public function cancelOrder(ApiKeyEntityInterface $apiKey, string $symbol, array $otherParams)  : array
    {
        $otherParams['symbol'] = $symbol;

        return $this->callApi($this->getCancelOrderRouteName(), $apiKey, $otherParams);
    }

    public function queryOrder(ApiKeyEntityInterface $apiKey, string $symbol, array $otherParams)  : array
    {
        $otherParams['symbol'] = $symbol;

        return $this->callApi($this->getQueryOrderRouteName(), $apiKey, $otherParams);
    }

    public function currentOpenOrders(ApiKeyEntityInterface $apiKey, array $params)  : array
    {
        return $this->callApi($this->getCurrentOrderRouteName(), $apiKey, $params);
    }

    public function allOrders(ApiKeyEntityInterface $apiKey, string $symbol, array $otherParams) : array
    {
        $otherParams['symbol'] = $symbol;

        return $this->callApi($this->getAllOrderRouteName(), $apiKey, $otherParams);
    }

    public function orderBook(string $symbol, array $otherParams) : array
    {
        $otherParams['symbol'] = $symbol;

        return $this->callApi($this->getOrderBookRouteName(), null, $otherParams);
    }

    public function candlestickData(string $symbol, string $interval, array $otherParams) : array
    {
        $otherParams['symbol'] = $symbol;
        $otherParams['interval'] = $interval;
        
        return $this->callApi($this->getCandlestickDataRouteName(), null, $otherParams);
    }
}