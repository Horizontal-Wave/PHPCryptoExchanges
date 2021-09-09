<?php

namespace CryptoExchanges\Core;

interface ApiKeyInterface
{
    public function getPublicKey() : string;

    public function getPrivateKey() : string;
}