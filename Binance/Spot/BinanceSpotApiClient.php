<?php

namespace CryptoExchanges\Binance\Spot;

use CryptoExchanges\Binance\BinanceApiClient;

class BinanceSpotApiClient extends BinanceApiClient
{
    protected function getRouteConfigFilePath() : string
    {
        return __DIR__ . "/binance_spot_api_v1.json";
    }

    protected function getOpenOrderRouteName() : string
    {
        return "New Order";
    }

    protected function getCancelOrderRouteName() : string
    {
        return "Cancel Order";
    }

    protected function getQueryOrderRouteName() : string
    {
        return "Query Order (USER_DATA)";
    }

    protected function getCurrentOrderRouteName() : string
    {
        return "Current Open Orders (USER_DATA)";
    }

    protected function getAllOrderRouteName() : string
    {
        return "All Orders (USER_DATA)";
    }

    protected function getOrderBookRouteName() : string
    {
        return "Order Book";
    }

    protected function getCandlestickDataRouteName() : string
    {
        return "Kline/Candlestick Data";
    }

    protected function getCurrentPriceRouteName() : string
    {
        return "Current Average Price";
    }

    protected function getExchangeInformationRouteName(): string
    {
        return "Exchange Information";
    }
}