<?php

namespace CryptoExchanges\Core;

interface ExchangeInterface
{
    public function getBaseUrl() : string;

    public function getName() : string;
}