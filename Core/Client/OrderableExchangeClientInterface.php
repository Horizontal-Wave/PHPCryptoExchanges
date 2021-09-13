<?php

namespace CryptoExchanges\Core\Client;

use CryptoExchanges\Core\EntityInterfaces\ApiKeyEntityInterface;

interface OrderableExchangeClientInterface 
{
    /**
     * Method to open a order
     *
     * @param ApiKeyEntityInterface $apiKey
     * @param array $params
     * @return array
     */
    function openOder(ApiKeyEntityInterface $apiKey, array $params) : array;

    /**
     * Method to cancel a order
     *
     * @param ApiKeyEntityInterface $apiKey
     * @param array $params
     * @return array
     */
    function cancelOrder(ApiKeyEntityInterface $apiKey, array $params) : array;

    /**
     * Method to query a order
     *
     * @param ApiKeyEntityInterface $apiKey
     * @param array $params
     * @return array
     */
    function queryOrder(ApiKeyEntityInterface $apiKey, array $params) : array;

    /**
     * Method to get current open orders
     *
     * @param ApiKeyEntityInterface $apiKey
     * @param array $params
     * @return array
     */
    function currentOpenOrders(ApiKeyEntityInterface $apiKey, array $params) : array;

    /**
     * Method to get all orders
     *
     * @param ApiKeyEntityInterface $apiKey
     * @param array $params
     * @return array
     */
    function allOrders(ApiKeyEntityInterface $apiKey, array $params) : array;
}