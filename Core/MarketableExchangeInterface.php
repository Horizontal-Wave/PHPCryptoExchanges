<?php

namespace CryptoExchanges\Core;

interface MarketableExchangeInterface
{
    /**
     * Method to get order book of a symbol
     *
     * @param array $params
     * @return ResponseInterface
     */
    function orderBook(array $params);

    /**
     * Method to get candlestick datas of a symbol
     *
     * @param array $params
     * @return ResponseInterface
     */
    function candlestickData(array $params);

    /**
     * Method to get the current price of a symbol
     *
     * @param array $params
     * @return void
     */
    function currentPrice(array $params);
}