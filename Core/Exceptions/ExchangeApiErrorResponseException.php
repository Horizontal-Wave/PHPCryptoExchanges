<?php

namespace CryptoExchanges\Core\Exceptions;

use Symfony\Contracts\HttpClient\ResponseInterface;

class ExchangeApiResponseErrorException extends \Exception 
{
    private int $statusCode;

    private array $content;

    private string $exchangeName;

    private string $route;

    private ResponseInterface $response;

    public function __construct(string $exchangeName, string $route, ResponseInterface $response)
    {
        $this->route = $route;
        $this->response = $response;
        $this->exchangeName = $exchangeName;
        $this->statusCode = $response->getStatusCode();
        $this->content = \json_decode($response->getContent(), true);

        $this->message = "Error while requesting " . $exchangeName . " endpoint " . $route . ". The status code receive is : " . $this->statusCode;
    }

    /**
     * Get the value of exchangeName
     */ 
    public function getExchangeName() : string
    {
        return $this->exchangeName;
    }

    /**
     * Get the value of statusCode
     */ 
    public function getStatusCode() : int
    {
        return $this->statusCode;
    }

    /**
     * Get the value of content
     */ 
    public function getContent() : array
    {
        return $this->content;
    }

    /**
     * Get the value of route
     */ 
    public function getRoute() : string
    {
        return $this->route;
    }

    /**
     * Get the value of response
     */ 
    public function getResponse() : ResponseInterface
    {
        return $this->response;
    }
}