<?php

namespace tests\unit\app\currencies\application\actions;

use app\currencies\application\actions\CreateOrUpdateCurrency;
use app\currencies\application\actions\ImportRates;
use app\currencies\application\dto\CurrencyProviderDto;
use app\currencies\application\enums\CurrencyIso;
use app\currencies\application\forms\CurrencyForm;
use app\currencies\infrastructure\providers\ExchangeRateProvider;
use app\shared\application\exceptions\InvalidCallException;
use app\shared\application\exceptions\NotValidException;
use PHPUnit\Framework\MockObject\MockObject;
use tests\unit\UnitTestCase;

class ImportRatesTest extends UnitTestCase
{
    private ImportRates $action;
    private ExchangeRateProvider|MockObject $provider;
    private CreateOrUpdateCurrency|MockObject $createOrUpdateCurrency;

    public function setUp(): void
    {
        parent::setUp();
        $this->createOrUpdateCurrency = $this->getActionMock(CreateOrUpdateCurrency::class);
        $this->provider = $this->getEuropeanCentralBankProviderMock();
        $this->action = new ImportRates($this->provider, $this->createOrUpdateCurrency);
    }


    /**
     * @return ExchangeRateProvider|MockObject
     */
    protected function getEuropeanCentralBankProviderMock(): ExchangeRateProvider|MockObject
    {
        return $this->getMockBuilder(ExchangeRateProvider::class)
            ->disableOriginalConstructor()
            ->onlyMethods(
                [
                    'getActualRates',
                ]
            )
            ->getMock();
    }

    /**
     * @throws InvalidCallException
     */
    public function testExecute()
    {
        $rates = [
            new CurrencyProviderDto(CurrencyIso::USD->value, 1.0),
            new CurrencyProviderDto(CurrencyIso::UAH->value, 5.0),
        ];

        $this->provider->expects($this->once())
            ->method('getActualRates')
            ->willReturn($rates);


        $mapInvoke = [
            'createOrUpdateCurrency.execute' => ['params' => [], 'return' => []],
        ];

        $entity = $this->getCurrencyEntity();
        foreach ($rates as $rate) {
            $mapInvoke['createOrUpdateCurrency.execute']['params'][] = [
                new CurrencyForm(
                    $rate->getCurrency(),
                    $rate->getRate()
                )
            ];
            $mapInvoke['createOrUpdateCurrency.execute']['return'][] = $entity;
        }

        $matcher = self::exactly(count($mapInvoke['createOrUpdateCurrency.execute']['params']));
        $this->createOrUpdateCurrency->expects($matcher)
            ->method('execute')
            ->willReturnCallback(
                $this->willReturnCallbackPrepare(
                    $matcher,
                    $mapInvoke['createOrUpdateCurrency.execute']['params'],
                    $mapInvoke['createOrUpdateCurrency.execute']['return'],
                )
            );

        $actual = $this->action->execute();
        self::assertIsArray($actual);
        self::assertCount(count($rates), $actual);
    }


    /**
     * @throws InvalidCallException
     */
    public function testExecuteNotCreated()
    {
        self::expectException(NotValidException::class);
        self::expectExceptionMessage("Validation Failed");

        $rates = [
            new CurrencyProviderDto(CurrencyIso::USD->value, 1.0),
        ];

        $this->provider->expects($this->once())
            ->method('getActualRates')
            ->willReturn($rates);

        $this->createOrUpdateCurrency->expects(self::once())
            ->method('execute')
            ->willThrowException(new NotValidException(["Not valid rate"]));

        $actual = $this->action->execute();
    }

    /**
     * @throws InvalidCallException
     */
    public function testExecuteEmptyRates()
    {
        self::expectException(InvalidCallException::class);
        self::expectExceptionMessage("Currency rate provider return empty");

        $this->provider->expects($this->once())
            ->method('getActualRates')
            ->willReturn([]);

        $actual = $this->action->execute();
    }
}

