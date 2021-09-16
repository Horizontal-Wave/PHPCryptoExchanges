<?php

namespace CryptoExchanges\Core\Exceptions;

class OrderRateLimitException extends RateLimitException
{
    private string $clientClass;

    public function __construct(string $clientClass)
    {
        $this->clientClass = $clientClass;

        $this->message = $clientClass . "as received a order rate limit error";
    }
}