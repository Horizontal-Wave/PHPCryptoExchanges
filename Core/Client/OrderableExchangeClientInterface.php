<?php

namespace CryptoExchanges\Core\Client;

use CryptoExchanges\Core\EntityInterfaces\ApiKeyEntityInterface;

interface OrderableExchangeClientInterface 
{
    /**
     * Method to open a order
     *
     * @param ApiKeyEntityInterface $apiKey
     * @param string $symbol
     * @param string $side
     * @param string $type
     * @param array $otherParams
     * @return array
     */
    function openOder(ApiKeyEntityInterface $apiKey, string $symbol, string $side, string $type, array $otherParams) : array;

    /**
     * Method to cancel a order
     *
     * @param ApiKeyEntityInterface $apiKey
     * @param string $symbol
     * @param array $otherParams
     * @return array
     */
    function cancelOrder(ApiKeyEntityInterface $apiKey, string $symbol, array $otherParams) : array;

    /**
     * Method to query a order
     *
     * @param ApiKeyEntityInterface $apiKey
     * @param string $symbol
     * @param array $otherParams
     * @return array
     */
    function queryOrder(ApiKeyEntityInterface $apiKey, string $symbol, array $otherParams) : array;

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
     * @param string $symbol
     * @param array $params
     * @return array
     */
    function allOrders(ApiKeyEntityInterface $apiKey, string $symbol, array $params) : array;
}