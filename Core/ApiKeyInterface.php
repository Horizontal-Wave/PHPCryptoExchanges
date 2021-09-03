<?php

namespace CryptoExchanges\Core;

interface ApiKeyInterface
{
    public function getPublicKey();

    public function getPrivateKey();
}