<?php

namespace CryptoExchanges\Core\Exceptions;

use Symfony\Contracts\HttpClient\ResponseInterface;

class WeightRateLimitException extends RateLimitException
{
    private int $retryAfter;

    public function __construct(string $exchangeName, string $route, ResponseInterface $response, string $retryAfterHeader)
    {
        parent::__construct($exchangeName, $route, $response);

        $this->retryAfter = $response->getHeaders()[$retryAfterHeader];
    }

    /**
     * Get the value of retryAfter
     */ 
    public function getRetryAfter() : int
    {
        return $this->retryAfter;
    }
}