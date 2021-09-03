<?php

namespace CryptoExchanges\Core;

interface ExchangeInterface
{
    public function getBaseUrl();

    public function getName();
}