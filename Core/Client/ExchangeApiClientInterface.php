<?php

namespace CryptoExchanges\Core\Client;

use CryptoExchanges\Core\EntityInterfaces\ApiKeyEntityInterface;

interface ExchangeApiClientInterface extends OrderableExchangeClientInterface, MarketableExchangeClientInterface
{
    /**
     * Method to call the api
     *
     * @param string $routeName
     * @param ApiKeyInterface $apiKey
     * @param array $params
     * @return array
     */
    function callApi(string $routeName, ?ApiKeyEntityInterface $apiKey, array $params = []) : array;
}