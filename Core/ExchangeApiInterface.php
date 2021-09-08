<?php

namespace CryptoExchanges\Core;

use Symfony\Contracts\HttpClient\ResponseInterface;

interface ExchangeApiInterface
{
    /**
     * Method to call the api
     *
     * @param string $routeName
     * @param ApiKeyInterface $apiKey
     * @param array $params
     * @return ResponseInterface
     */
    function callApi(string $routeName, ApiKeyInterface $apiKey, array $params = []);

    /**
     * Method to open a order
     *
     * @param ApiKeyInterface $apiKey
     * @param array $params
     * @return ResponseInterface
     */
    function openOder(ApiKeyInterface $apiKey, array $params);

    /**
     * Method to cancel a order
     *
     * @param ApiKeyInterface $apiKey
     * @param array $params
     * @return ResponseInterface
     */
    function cancelOrder(ApiKeyInterface $apiKey, array $params);

    /**
     * Method to query a order
     *
     * @param ApiKeyInterface $apiKey
     * @param array $params
     * @return ResponseInterface
     */
    function queryOrder(ApiKeyInterface $apiKey, array $params);

    /**
     * Method to get current open orders
     *
     * @param ApiKeyInterface $apiKey
     * @param array $params
     * @return ResponseInterface
     */
    function currentOpenOrders(ApiKeyInterface $apiKey, array $params);

    /**
     * Method to get all orders
     *
     * @param ApiKeyInterface $apiKey
     * @param array $params
     * @return ResponseInterface
     */
    function allOrders(ApiKeyInterface $apiKey, array $params);
}