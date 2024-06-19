<?php

namespace tests\unit\app\currencies\infrastructure\providers;

use app\currencies\application\dto\CurrencyProviderDto;
use app\currencies\application\enums\CurrencyIso;
use app\currencies\infrastructure\providers\ExchangeRateProvider;
use app\shared\application\exceptions\RemoteServiceException;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\MockObject\MockObject;
use tests\unit\UnitTestCase;

class EuropeanCentralBankProviderTest extends UnitTestCase
{
    private ExchangeRateProvider $provider;
    private Client|MockObject $httpClient;

    private string $apiKey = 'test-api-key';
    private string $baseCurrency = CurrencyIso::USD->value;
    private string $importCurrency = CurrencyIso::UAH->value;

    public function setUp(): void
    {
        parent::setUp();
        $this->httpClient = $this->getHttpClientMock();
        $this->provider = new ExchangeRateProvider(
            $this->httpClient,
            $this->apiKey,
            $this->baseCurrency,
            $this->importCurrency,
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
            ->willReturn($response);

        $expectedResult = [
            new CurrencyProviderDto($this->importCurrency, 0.85000),
        ];

        $actualResult = $this->provider->getActualRates();
        $this->assertEquals($expectedResult, $actualResult);
    }

    public function testGetActualRatesServiceUnavailable()
    {
        $this->httpClient
            ->expects(self::once())
            ->method('get')
            ->willThrowException(new Exception('Service down'));

        $this->expectException(RemoteServiceException::class);
        $this->expectExceptionMessage('Service unavailable: Service down');

        $actual = $this->provider->getActualRates();
        self::assertIsArray($actual);
    }

    public function testGetActualRatesStatusNotSuccessful()
    {
        $response = new Response(500, [], '');

        $this->httpClient
            ->expects(self::once())
            ->method('get')
            ->willReturn($response);

        $this->expectException(RemoteServiceException::class);
        $this->expectExceptionMessage('Status code not successfully');

        $actual = $this->provider->getActualRates();
        self::assertIsArray($actual);
    }

    public function testGetActualRatesResultNotSuccess()
    {
        $responseBody = json_encode([
            'result' => 'failure',
        ]);

        $response = new Response(200, [], $responseBody);

        $this->httpClient
            ->expects(self::once())
            ->method('get')
            ->willReturn($response);

        $this->expectException(RemoteServiceException::class);
        $this->expectExceptionMessage('Result not success');

        $actual = $this->provider->getActualRates();
        self::assertIsArray($actual);
    }

    public function testGetActualRatesBadConversionRate()
    {
        $responseBody = json_encode([
            'result' => 'success',
            'conversion_rate' => 0,
        ]);

        $response = new Response(200, [], $responseBody);

        $this->httpClient
            ->expects(self::once())
            ->method('get')
            ->willReturn($response);

        $this->expectException(RemoteServiceException::class);
        $this->expectExceptionMessage('Bad conversion rate');

        $actual = $this->provider->getActualRates();
        self::assertIsArray($actual);
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

        $actualResult = $this->provider->getActualRates();
    }
}
