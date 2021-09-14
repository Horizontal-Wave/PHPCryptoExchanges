<?php

namespace CryptoExchanges\Core\Client;

interface MarketableExchangeClientInterface
{
    /**
     * Method to get order book of a symbol
     *
     * @param string $symbol
     * @param array $otherParams
     * @return array
     */
    function orderBook(string $symbol, array $otherParams) : array;

    /**
     * Method to get candlestick datas of a symbol
     *
     * @param string $symbol
     * @param string $interval
     * @param array $otherParams
     * @return array
     */
    function candlestickData(string $symbol, string $interval, array $otherParams) : array;

    /**
     * Method to get the current price of a symbol
     *
     * @param string $symbol
     * @param array $otherParams
     * @return array
     */
    function currentPrice(string $symbol, array $otherParams) : array;
}