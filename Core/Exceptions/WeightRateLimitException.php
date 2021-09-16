<?php

namespace CryptoExchanges\Core\Exceptions;

class WeightRateLimitException extends RateLimitException
{
    private ?int $retryAfter;

    private string $clientName;

    public function __construct(string $clientName, ?int $retryAfter)
    {
        $this->clientName = $clientName;
        $this->retryAfter = $retryAfter;
    }

    public function getClientName() : string
    {
        return $this->clientName;
    }

    public function getRetryAfter() : ?int
    {
        return $this->retryAfter;
    }
}