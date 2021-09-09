<?php

namespace CryptoExchanges\Core;

interface MarketableExchangeInterface
{
    /**
     * Method to get order book
     *
     * @param array $params
     * @return ResponseInterface
     */
    function orderBook(array $params);
}