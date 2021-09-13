<?php

namespace CryptoExchanges\Core\EntityInterfaces;

interface ApiKeyEntityInterface
{
    public function getPublicKey() : string;

    public function getPrivateKey() : string;
}