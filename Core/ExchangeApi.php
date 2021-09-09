<?php

namespace CryptoExchanges\Core;

use Symfony\Contracts\HttpClient\HttpClientInterface;

abstract class ExchangeApi implements ExchangeApiInterface
{
    protected HttpClientInterface $client;

    protected ExchangeInterface $exchange;

    public function __construct(ExchangeInterface $exchange, HttpClientInterface $client)
    {
        $this->client = $client;
        $this->exchange = $exchange;
    }

    abstract public function callApi(string $routeName, ?ApiKeyInterface $apiKey, array $params = []);

    /**
     * Method to get the config file path
     *
     * @return string
     */
    abstract protected function getRouteConfigFilePath();

    /**
     * Method to fetch the routes
     *
     * @return array
     */
    protected function fetchRouteConfigs() 
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
    abstract protected function getOpenOrderRouteName();

    /**
     * Method to get the route name to cancel a order
     *
     * @return string
     */
    abstract protected function getCancelOrderRouteName();

    /**
     * Method to get the route name to query a order
     *
     * @return string
     */
    abstract protected function getQueryOrderRouteName();

    /**
     * Method to get the route name to get current open orders
     *
     * @return string
     */
    abstract protected function getCurrentOrderRouteName();

    /**
     * Method to get the route name to get all orders
     *
     * @return string
     */
    abstract protected function getAllOrderRouteName();

    /**
     * Method to get the route name for order book
     *
     * @return string
     */
    abstract protected function getOrderBookRouteName();

    public function openOder(ApiKeyInterface $apiKey, array $params)
    {
        return $this->callApi($this->getOpenOrderRouteName(), $apiKey, $params);
    }

    public function cancelOrder(ApiKeyInterface $apiKey, array $params)
    {
        return $this->callApi($this->getCancelOrderRouteName(), $apiKey, $params);
    }

    public function queryOrder(ApiKeyInterface $apiKey, array $params)
    {
        return $this->callApi($this->getQueryOrderRouteName(), $apiKey, $params);
    }

    public function currentOpenOrders(ApiKeyInterface $apiKey, array $params)
    {
        return $this->callApi($this->getCurrentOrderRouteName(), $apiKey, $params);
    }

    public function allOrders(ApiKeyInterface $apiKey, array $params)
    {
        return $this->callApi($this->getAllOrderRouteName(), $apiKey, $params);
    }

    public function orderBook(array $params)
    {
        return $this->callApi($this->getOrderBookRouteName(), null, $params);
    }
}