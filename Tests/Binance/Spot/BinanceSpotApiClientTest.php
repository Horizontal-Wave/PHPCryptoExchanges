<?php

namespace CryptoExchanges\Binance\Spot\Tests;

use Symfony\Component\HttpClient\HttpClient;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use CryptoExchanges\Core\Utils\UrlEncoder;
use CryptoExchanges\Core\Exceptions\ExchangeApiResponseException;
use CryptoExchanges\Core\EntityInterfaces\ExchangeEntityInterface;
use CryptoExchanges\Core\EntityInterfaces\ApiKeyEntityInterface;
use CryptoExchanges\Binance\Spot\BinanceSpotApiClient;

class BinanceSpotApiClientTest extends TestCase
{
    private BinanceSpotApiClient $binanceApiSpotClient;

    private ApiKeyEntityInterface $apiKeyEntity;

    protected function setUp(): void
    {
        $urlEncoder = new UrlEncoder();
        $httpClient = HttpClient::create();

        // Mock binanceApiSpotClient

        /**
         * @var MockObject
         */
        $binanceEntityMock = $this->createMock(ExchangeEntityInterface::class);
        $binanceEntityMock->expects($this->any())->method('getName')->willReturn('Binance');
        $binanceEntityMock->expects($this->any())->method('getBaseUrl')->willReturn('https://api.binance.com');

        /**
         * @var ExchangeEntityInterface
         */
        $binanceEntity = $binanceEntityMock;

        $this->binanceApiSpotClient = new BinanceSpotApiClient($binanceEntity, $httpClient, $urlEncoder);


        // Mock apiKeyEntity

        $fileContent = \json_decode(\file_get_contents(__DIR__ . "/apiKeys.secret.json"), true);
        
        /**
         * @var MockObject
         */
        $apiKeyEntityMock = $this->createMock(ApiKeyEntityInterface::class);
        $apiKeyEntityMock->expects($this->any())->method('getPublicKey')->willReturn($fileContent['publicKey']);
        $apiKeyEntityMock->expects($this->any())->method('getPrivateKey')->willReturn($fileContent['privateKey']);

        $this->apiKeyEntity = $apiKeyEntityMock;
    }

    public function testAllOrders() : void
    {
        try {
            $orders = $this->binanceApiSpotClient->allOrders($this->apiKeyEntity, [
                'symbol' => "BTCUSDT"
            ]);
            
            $this->assertGreaterThan(0, \count($orders));
        } catch (ExchangeApiResponseException $e) {
            $this->fail($e->getMessage());
        }
    }

    public function testCandlestickData() : void
    {
        try {
            $datas = $this->binanceApiSpotClient->candlestickData("BTCUSDT", '1m', []);

            $this->assertGreaterThan(0, \count($datas));
        } catch (ExchangeApiResponseException $e) {
            $this->fail($e->getMessage());
        }
    }

    public function testOrderBook() : void 
    {
        try {
            $datas = $this->binanceApiSpotClient->orderBook("BTCUSDT", []);

            $this->assertGreaterThan(0, \count($datas));
        } catch (ExchangeApiResponseException $e) {
            $this->fail($e->getMessage());
        }
    }
}