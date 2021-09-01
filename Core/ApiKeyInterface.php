<?php

namespace PHPCryptoExchanges\Core;

interface ApiKeyInterface
{
    public function getPublicKey();

    public function getPrivateKey();
}