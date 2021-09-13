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
            foreach($folder as $items) {
                $result = \array_merge($result, $items);
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

    /**
     * Method to get the route name for current price
     *
     * @return string
     */
    abstract protected function getCurrentPriceRouteName();

    public function openOder(ApiKeyEntityInterface $apiKey, array $params)  : array
    {
        return $this->callApi($this->getOpenOrderRouteName(), $apiKey, $params);
    }

    public function cancelOrder(ApiKeyEntityInterface $apiKey, array $params)  : array
    {
        return $this->callApi($this->getCancelOrderRouteName(), $apiKey, $params);
    }

    public function queryOrder(ApiKeyEntityInterface $apiKey, array $params)  : array
    {
        return $this->callApi($this->getQueryOrderRouteName(), $apiKey, $params);
    }

    public function currentOpenOrders(ApiKeyEntityInterface $apiKey, array $params)  : array
    {
        return $this->callApi($this->getCurrentOrderRouteName(), $apiKey, $params);
    }

    public function allOrders(ApiKeyEntityInterface $apiKey, array $params) : array
    {
        return $this->callApi($this->getAllOrderRouteName(), $apiKey, $params);
    }

    public function orderBook(array $params) : array
    {
        return $this->callApi($this->getOrderBookRouteName(), null, $params);
    }

    public function candlestickData(array $params) : array
    {
        return $this->callApi($this->getCandlestickDataRouteName(), null, $params);
    }

    public function currentPrice(array $params) : array
    {
        return $this->callApi($this->getCurrentPriceRouteName(), null, $params);
    }
}