<?php

namespace PHPCryptoExchanges\Core;

class RouteConfigNotFoundException extends \Exception 
{
    public function __construct(string $exchangeName, string $routeName)
    {
        $this->message = "The config for " . $routeName . " route for " . $exchangeName . " exchange was not found";
    }
}