<?php

namespace CryptoExchanges\Core\EntityInterfaces;

interface ExchangeEntityInterface
{
    public function getBaseUrl() : string;

    public function getName() : string;
}