<?php

namespace CryptoExchanges\Core\Client;

use Symfony\Contracts\HttpClient\ResponseInterface;
use CryptoExchanges\Core\EntityInterfaces\ApiKeyEntityInterface;

interface OrderableExchangeClientInterface 
{
    /**
     * Method to open a order
     *
     * @param ApiKeyEntityInterface $apiKey
     * @param array $params
     * @return ResponseInterface
     */
    function openOder(ApiKeyEntityInterface $apiKey, array $params) : ResponseInterface;

    /**
     * Method to cancel a order
     *
     * @param ApiKeyEntityInterface $apiKey
     * @param array $params
     * @return ResponseInterface
     */
    function cancelOrder(ApiKeyEntityInterface $apiKey, array $params) : ResponseInterface;

    /**
     * Method to query a order
     *
     * @param ApiKeyEntityInterface $apiKey
     * @param array $params
     * @return ResponseInterface
     */
    function queryOrder(ApiKeyEntityInterface $apiKey, array $params) : ResponseInterface;

    /**
     * Method to get current open orders
     *
     * @param ApiKeyEntityInterface $apiKey
     * @param array $params
     * @return ResponseInterface
     */
    function currentOpenOrders(ApiKeyEntityInterface $apiKey, array $params) : ResponseInterface;

    /**
     * Method to get all orders
     *
     * @param ApiKeyEntityInterface $apiKey
     * @param array $params
     * @return ResponseInterface
     */
    function allOrders(ApiKeyEntityInterface $apiKey, array $params) : ResponseInterface;
}