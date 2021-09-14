<?php

namespace CryptoExchanges\Binance\PerpetualFutures\Tests;

use Symfony\Component\HttpClient\HttpClient;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use CryptoExchanges\Core\Utils\UrlEncoder;
use CryptoExchanges\Core\Exceptions\ExchangeApiResponseException;
use CryptoExchanges\Core\EntityInterfaces\ExchangeEntityInterface;
use CryptoExchanges\Core\EntityInterfaces\ApiKeyEntityInterface;
use CryptoExchanges\Binance\PerpetualFutures\BinancePerpetualFuturesApiClient;

class BinancePerpetualFuturesApiClientTest extends TestCase
{
    private BinancePerpetualFuturesApiClient $binancePerpetualFuturesApiClient;

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
        $binanceEntityMock->expects($this->any())->method('getName')->willReturn('Binance Perpetual Futures');
        $binanceEntityMock->expects($this->any())->method('getBaseUrl')->willReturn('https://fapi.binance.com');

        /**
         * @var ExchangeEntityInterface
         */
        $binanceEntity = $binanceEntityMock;

        $this->binancePerpetualFuturesApiClient = new BinancePerpetualFuturesApiClient($binanceEntity, $httpClient, $urlEncoder);


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
            $orders = $this->binancePerpetualFuturesApiClient->allOrders($this->apiKeyEntity, "BTCUSDT", []);
            
            $this->assertIsArray($orders);
        } catch (ExchangeApiResponseException $e) {
            $this->fail($e->getMessage());
        }
    }

    public function testCandlestickData() : void
    {
        try {
            $datas = $this->binancePerpetualFuturesApiClient->candlestickData("BTCUSDT", '1m', []);

            $this->assertGreaterThan(0, \count($datas));
        } catch (ExchangeApiResponseException $e) {
            $this->fail($e->getMessage());
        }
    }

    public function testOrderBook() : void 
    {
        try {
            $datas = $this->binancePerpetualFuturesApiClient->orderBook("BTCUSDT", []);

            $this->assertIsArray($datas);
        } catch (ExchangeApiResponseException $e) {
            $this->fail($e->getMessage());
        }
    }

    public function testCurrentOpenOrders() : void 
    {
        try {
            $openOrders = $this->binancePerpetualFuturesApiClient->currentOpenOrders($this->apiKeyEntity, []);

            $this->assertIsArray($openOrders);
        } catch (ExchangeApiResponseException $e) {
            $this->fail($e->getMessage());
        }
    }
}