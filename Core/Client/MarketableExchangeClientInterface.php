<?php

namespace CryptoExchanges\Core\Client;

use Symfony\Contracts\HttpClient\ResponseInterface;

interface MarketableExchangeClientInterface
{
    /**
     * Method to get order book of a symbol
     *
     * @param array $params
     * @return ResponseInterface
     */
    function orderBook(array $params) : ResponseInterface;

    /**
     * Method to get candlestick datas of a symbol
     *
     * @param array $params
     * @return ResponseInterface
     */
    function candlestickData(array $params) : ResponseInterface;

    /**
     * Method to get the current price of a symbol
     *
     * @param array $params
     * @return void
     */
    function currentPrice(array $params) : ResponseInterface;
}