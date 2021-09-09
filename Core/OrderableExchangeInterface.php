<?php

namespace CryptoExchanges\Core;

use Symfony\Contracts\HttpClient\ResponseInterface;

interface OrderableExchangeInterface 
{
    /**
     * Method to open a order
     *
     * @param ApiKeyInterface $apiKey
     * @param array $params
     * @return ResponseInterface
     */
    function openOder(ApiKeyInterface $apiKey, array $params) : ResponseInterface;

    /**
     * Method to cancel a order
     *
     * @param ApiKeyInterface $apiKey
     * @param array $params
     * @return ResponseInterface
     */
    function cancelOrder(ApiKeyInterface $apiKey, array $params) : ResponseInterface;

    /**
     * Method to query a order
     *
     * @param ApiKeyInterface $apiKey
     * @param array $params
     * @return ResponseInterface
     */
    function queryOrder(ApiKeyInterface $apiKey, array $params) : ResponseInterface;

    /**
     * Method to get current open orders
     *
     * @param ApiKeyInterface $apiKey
     * @param array $params
     * @return ResponseInterface
     */
    function currentOpenOrders(ApiKeyInterface $apiKey, array $params) : ResponseInterface;

    /**
     * Method to get all orders
     *
     * @param ApiKeyInterface $apiKey
     * @param array $params
     * @return ResponseInterface
     */
    function allOrders(ApiKeyInterface $apiKey, array $params) : ResponseInterface;
}