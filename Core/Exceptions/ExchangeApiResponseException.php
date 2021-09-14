<?php

namespace CryptoExchanges\Core\Exceptions;

class ExchangeApiResponseException extends \Exception 
{
    public function __construct(int $statusCode, string $exchangeName, string $route, string $errorMessage)
    {
        $this->message = "Error while requesting " . $exchangeName . " endpoint " . $route . ". The status code receive is : " . $statusCode . ", with error message : \n" . $errorMessage;
    }
}