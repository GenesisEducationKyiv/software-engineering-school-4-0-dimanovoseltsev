<?php

namespace tests\unit\app\infrastructure\providers;

use app\application\dto\CurrencyProviderDto;
use app\application\enums\CurrencyIso;
use app\infrastructure\providers\CoinbaseProvider;
use app\application\exceptions\RemoteServiceException;
use app\infrastructure\services\YiiLogger;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\MockObject\MockObject;
use tests\unit\UnitTestCase;

class CoinbaseProviderTest extends UnitTestCase
{
    private CoinbaseProvider $provider;
    private Client|MockObject $httpClient;
    private YiiLogger|MockObject $logService;

    private string $baseCurrency = CurrencyIso::USD->value;
    private string $importCurrency = CurrencyIso::UAH->value;

    public function setUp(): void
    {
        parent::setUp();
        $this->httpClient = $this->getHttpClientMock();
        $this->logService = $this->getLogServiceMock();
        $this->provider = new CoinbaseProvider(
            $this->httpClient,
            $this->logService,
        );
    }

    /**
     * @return Client|MockObject
     */
    protected function getHttpClientMock(): Client|MockObject
    {
        return $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->onlyMethods([
                'get',
            ])
            ->getMock();
    }

    public function testGetActualRatesSuccess()
    {
        $responseBody = json_encode([
            'data' => [
                'amount' => 0.85,
            ]
        ]);

        $response = new Response(200, [], $responseBody);

        $this->httpClient
            ->expects(self::once())
            ->method('get')
            ->with(sprintf("/v2/prices/%s-%s/buy", $this->baseCurrency, $this->importCurrency), [])
            ->willReturn($response);

        $expectedResult = new CurrencyProviderDto($this->importCurrency, 0.85000);

        $actual = $this->provider->getRate($this->baseCurrency, $this->importCurrency);
        self::assertInstanceOf(CurrencyProviderDto::class, $actual);
        self::assertEquals($expectedResult, $actual);
    }

    public function testGetActualRatesServiceUnavailable()
    {
        $this->expectException(RemoteServiceException::class);
        $this->expectExceptionMessage('Service unavailable: Service down');

        $this->httpClient
            ->expects(self::once())
            ->method('get')
            ->willThrowException(new Exception('Service down'));

        $this->provider->getRate($this->baseCurrency, $this->importCurrency);
    }

    public function testGetActualRatesStatusNotSuccessful()
    {
        $this->expectException(RemoteServiceException::class);
        $this->expectExceptionMessage('Status code not successfully');

        $response = new Response(500, [], '');

        $this->httpClient
            ->expects(self::once())
            ->method('get')
            ->willReturn($response);

        $this->provider->getRate($this->baseCurrency, $this->importCurrency);
    }

    public function testGetActualRatesBadConversionRate()
    {
        $this->expectException(RemoteServiceException::class);
        $this->expectExceptionMessage('Bad conversion rate');

        $responseBody = json_encode([
            'data' => [
                'amount' => 0,
            ]
        ]);

        $response = new Response(200, [], $responseBody);

        $this->httpClient
            ->expects(self::once())
            ->method('get')
            ->willReturn($response);

        $actual = $this->provider->getRate($this->baseCurrency, $this->importCurrency);
        self::assertInstanceOf(CurrencyProviderDto::class, $actual);
    }

    public function testGetActualRatesBadJson()
    {
        $this->expectException(RemoteServiceException::class);
        $this->expectExceptionMessage('Invalid JSON response');

        $response = new Response(200, [], '');

        $this->httpClient
            ->expects(self::once())
            ->method('get')
            ->willReturn($response);

        $this->provider->getRate($this->baseCurrency, $this->importCurrency);
    }
}
