<?php

namespace CryptoExchanges\Core;

use Symfony\Contracts\HttpClient\ResponseInterface;

interface ExchangeApiInterface extends OrderableExchangeInterface, MarketableExchangeInterface
{
    /**
     * Method to call the api
     *
     * @param string $routeName
     * @param ApiKeyInterface $apiKey
     * @param array $params
     * @return ResponseInterface
     */
    function callApi(string $routeName, ?ApiKeyInterface $apiKey, array $params = []) : ResponseInterface;
}