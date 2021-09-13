<?php

namespace CryptoExchanges\Core\Utils\Tests;

use PHPUnit\Framework\TestCase;
use CryptoExchanges\Core\Utils\UrlEncoder;

class UrlEncoderTest extends TestCase
{
    private UrlEncoder $urlEncoder;

    protected function setUp() : void
    {
        $this->urlEncoder = new UrlEncoder();
    }

    public function testUrlEncode()
    {
        $this->assertEquals("key1=value&key2=false", $this->urlEncoder->urlEncode([
            'key1' => 'value',
            'key2' => false
        ]));
    }
}