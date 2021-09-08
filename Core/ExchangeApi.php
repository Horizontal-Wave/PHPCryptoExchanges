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

    abstract public function callApi(string $routeName, ApiKeyInterface $apiKey, array $params = []);

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
     * Method to call callApi function
     *
     * @param ApiKeyInterface $apiKey
     * @param array $params
     * @param callable $funcToGetRouteName
     * @return void
     */
    private function processShortcut(ApiKeyInterface $apiKey, array $params, callable $funcToGetRouteName)
    {
        $routeName = $funcToGetRouteName();
        return $this->callApi($routeName, $apiKey, $params);
    }

    public function openOder(ApiKeyInterface $apiKey, array $params)
    {
        return $this->processShortcut($apiKey, $params, $this->getOpenOrderRouteName);
    }

    public function cancelOrder(ApiKeyInterface $apiKey, array $params)
    {
        return $this->processShortcut($apiKey, $params, $this->getCancelOrderRouteName);
    }

    public function queryOrder(ApiKeyInterface $apiKey, array $params)
    {
        return $this->processShortcut($apiKey, $params, $this->getQueryOrderRouteName);
    }

    public function currentOpenOrders(ApiKeyInterface $apiKey, array $params)
    {
        return $this->processShortcut($apiKey, $params, $this->getCurrentOrderRouteName);
    }

    public function allOrders(ApiKeyInterface $apiKey, array $params)
    {
        return $this->processShortcut($apiKey, $params, $this->getAllOrderRouteName);
    }
}