<?php

namespace CryptoExchanges\Binance\DeliveryFutures;

use CryptoExchanges\Binance\BinanceApiClient;

class BinanceDeliveryFuturesApiClient extends BinanceApiClient
{
    protected function getRouteConfigFilePath(): string
    {
        return __DIR__ . "/binance_delivery_future_api_v1.json";
    }

    protected function getOpenOrderRouteName(): string
    {
        return "New Order (TRADE)";
    }

    protected function getCancelOrderRouteName(): string
    {
        return "Cancel Order";
    }

    protected function getQueryOrderRouteName(): string
    {
        return "Query Order";
    }

    protected function getCurrentOrderRouteName(): string
    {
        return "Current All Open Orders (USER_DATA)";
    }

    protected function getAllOrderRouteName(): string
    {
        return "All Orders";
    }

    protected function getOrderBookRouteName(): string
    {
        return "Order Book (MARKET_DATA)";
    }

    protected function getCandlestickDataRouteName(): string
    {
        return "Kline/Candlestick Data (MARKET_DATA)";
    }

    protected function getExchangeInformationRouteName(): string
    {
        return "Exchange Information (MARKET_DATA)";
    }
}