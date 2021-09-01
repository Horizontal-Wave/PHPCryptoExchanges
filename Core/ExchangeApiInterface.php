<?php

namespace PHPCryptoExchanges\Core;

interface ExchangeApiInterface
{
    function callApi(string $routeName, ApiKeyInterface $apiKey, array $params = []);
}