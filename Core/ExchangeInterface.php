<?php

namespace PHPCryptoExchanges\Core;

interface ExchangeInterface
{
    public function getBaseUrl();

    public function getName();
}