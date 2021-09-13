<?php

namespace CryptoExchanges\Core\Client;

interface MarketableExchangeClientInterface
{
    /**
     * Method to get order book of a symbol
     *
     * @param array $params
     * @return array
     */
    function orderBook(array $params) : array;

    /**
     * Method to get candlestick datas of a symbol
     *
     * @param array $params
     * @return array
     */
    function candlestickData(array $params) : array;

    /**
     * Method to get the current price of a symbol
     *
     * @param array $params
     * @return array
     */
    function currentPrice(array $params) : array;
}