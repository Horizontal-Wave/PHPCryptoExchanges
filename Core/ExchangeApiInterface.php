<?php

namespace PHPCryptoExchanges\Core;

interface ExchangeApiInterface
{
    function callApi(string $endpoint, string $method = 'GET', array $params = []);
}