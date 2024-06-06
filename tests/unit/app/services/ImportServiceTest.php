<?php

namespace tests\unit\app\services;

use app\models\Currency;
use app\services\CurrenciesService;
use app\services\ImportService;
use app\services\providers\ExchangerateApiProvider;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use tests\unit\UnitTestCase;
use yii\base\InvalidCallException;

class ImportServiceTest extends UnitTestCase
{
    private ImportService $service;
    private ExchangerateApiProvider|MockObject $currencyRateProvider;
    private CurrenciesService|MockObject $currenciesService;

    public function setUp(): void
    {
        parent::setUp();
        $this->currencyRateProvider = $this->getExchangerateApiProviderMock();
        $this->currenciesService = $this->getCurrenciesServiceMock();
        $this->service = new ImportService(
            $this->currencyRateProvider,
            $this->currenciesService,
        );
    }

    /**
     * @return ExchangerateApiProvider|MockObject
     */
    protected function getExchangerateApiProviderMock(): ExchangerateApiProvider|MockObject
    {
        return $this->getMockBuilder(ExchangerateApiProvider::class)
            ->disableOriginalConstructor()
            ->onlyMethods([
                'getActualRates',
            ])
            ->getMock();
    }

    /**
     * @return CurrenciesService|MockObject
     */
    protected function getCurrenciesServiceMock(): CurrenciesService|MockObject
    {
        return $this->getMockBuilder(CurrenciesService::class)
            ->disableOriginalConstructor()
            ->onlyMethods([
                'findByCode',
                'create',
                'update',
            ])
            ->getMock();
    }

    /**
     * @throws Exception
     */
    public function testImportRatesSuccess()
    {
        $rates = ['EUR' => 0.85, 'GBP' => 0.75];
        $this->currencyRateProvider
            ->expects(self::once())
            ->method('getActualRates')
            ->willReturn($rates);

        $currencyModel = $this->createMock(Currency::class);

        $this->currenciesService
            ->expects(self::exactly(2))
            ->method('findByCode')
            ->willReturnOnConsecutiveCalls(null, $currencyModel);

        $this->currenciesService
            ->expects(self::once())
            ->method('create')
            ->willReturn($currencyModel);

        $this->currenciesService
            ->expects(self::once())
            ->method('update')
            ->willReturn($currencyModel);

        $result = $this->service->importRates();

        $this->assertCount(2, $result);
        $this->assertContainsOnlyInstancesOf(Currency::class, $result);
    }

    public function testImportRatesProviderReturnsEmpty()
    {
        $this->currencyRateProvider
            ->expects(self::once())
            ->method('getActualRates')
            ->willReturn([]);

        $this->expectException(InvalidCallException::class);
        $this->expectExceptionMessage('Currency rate provider return empty');

        $this->service->importRates();
    }

    public function testImportRatesWithNewAndExistingCurrency()
    {
        $rates = ['EUR' => 0.85];
        $this->currencyRateProvider
            ->expects(self::once())
            ->method('getActualRates')
            ->willReturn($rates);

        $existingCurrencyModel = $this->getCurrencyModelMock();
        $newCurrencyModel = $this->getCurrencyModelMock();

        $this->currenciesService
            ->expects(self::once())
            ->method('findByCode')
            ->willReturnOnConsecutiveCalls(null, $existingCurrencyModel);

        $this->currenciesService
            ->expects(self::once())
            ->method('create')
            ->willReturn($newCurrencyModel);

        $result = $this->service->importRates();

        $this->assertCount(1, $result);
        $this->assertContainsOnlyInstancesOf(Currency::class, $result);
        $this->assertSame($newCurrencyModel, $result[0]);
    }

}

