<?php

namespace tests\unit\app\currencies\infrastructure\providers;

use app\currencies\application\dto\CurrencyProviderDto;
use app\currencies\application\enums\CurrencyIso;
use app\currencies\infrastructure\providers\ExchangeRateProvider;
use app\shared\application\exceptions\RemoteServiceException;
use app\shared\infrastructure\services\YiiLogger;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\MockObject\MockObject;
use tests\unit\UnitTestCase;

class ExchangeRateProviderTest extends UnitTestCase
{
    private ExchangeRateProvider $provider;
    private Client|MockObject $httpClient;
    private YiiLogger|MockObject $logService;

    private string $apiKey = 'test-api-key';
    private string $baseCurrency = CurrencyIso::USD->value;
    private string $importCurrency = CurrencyIso::UAH->value;

    public function setUp(): void
    {
        parent::setUp();
        $this->httpClient = $this->getHttpClientMock();
        $this->logService = $this->getLogServiceMock();
        $this->provider = new ExchangeRateProvider(
            $this->httpClient,
            $this->apiKey,
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
            'result' => 'success',
            'conversion_rate' => 0.85,
        ]);

        $response = new Response(200, [], $responseBody);

        $this->httpClient
            ->expects(self::once())
            ->method('get')
            ->with(sprintf("/v6/%s/pair/%s/%s", $this->apiKey, $this->baseCurrency, $this->importCurrency), [])
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

    public function testGetActualRatesResultNotSuccess()
    {
        $this->expectException(RemoteServiceException::class);
        $this->expectExceptionMessage('Result not success');

        $responseBody = json_encode([
            'result' => 'failure',
        ]);

        $response = new Response(200, [], $responseBody);

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
            'result' => 'success',
            'conversion_rate' => 0,
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
