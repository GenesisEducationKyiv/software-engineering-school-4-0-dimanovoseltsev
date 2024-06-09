<?php

namespace tests\unit\app\services\providers;

use app\exceptions\RemoteServiceException;
use app\repositories\CurrencyRepository;
use app\services\providers\EuropeanCentralBankProvider;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\MockObject\MockObject;
use tests\unit\UnitTestCase;

class ExchangerateApiProviderTest extends UnitTestCase
{
    private EuropeanCentralBankProvider $provider;
    private Client|MockObject $httpClient;

    private string $apiKey = 'test-api-key';
    private string $baseCurrency = 'USD';
    private string $importCurrency = 'EUR';

    public function setUp(): void
    {
        parent::setUp();
        $this->httpClient = $this->getHttpClientMock();
        $this->provider = new EuropeanCentralBankProvider(
            $this->httpClient,
            $this->apiKey,
            $this->baseCurrency,
            $this->importCurrency,
        );
    }

    /**
     * @return CurrencyRepository|MockObject
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

        $expectedResult = [$this->importCurrency => 0.85000];

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

        $this->provider->getActualRates();
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

        $this->provider->getActualRates();
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

        $this->provider->getActualRates();
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

        $this->provider->getActualRates();
    }

}

