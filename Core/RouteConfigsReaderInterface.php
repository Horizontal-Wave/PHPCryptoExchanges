<?php

namespace CryptoExchanges\Core;

interface RouteConfigsReaderInterface
{
    function ReadRouteConfigs(string $filePath);
}