<?php

namespace CryptoExchanges\Core;

interface ExchangeApiInterface
{
    function callApi(string $routeName, ApiKeyInterface $apiKey, array $params = []);
}