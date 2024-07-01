<?php

namespace tests\unit\app\currencies\application\actions;

use app\currencies\application\actions\CreateOrUpdateCurrency;
use app\currencies\application\actions\ImportRates;
use app\currencies\application\dto\CurrencyProviderDto;
use app\currencies\application\enums\CurrencyIso;
use app\currencies\application\forms\CurrencyForm;
use app\currencies\application\services\RateService;
use app\shared\application\exceptions\NotValidException;
use app\shared\application\exceptions\UnexpectedValueException;
use PHPUnit\Framework\MockObject\MockObject;
use tests\unit\UnitTestCase;

class ImportRatesTest extends UnitTestCase
{
    private ImportRates $action;
    private CreateOrUpdateCurrency|MockObject $createOrUpdateCurrency;
    private RateService|MockObject $rateService;
    private CurrencyIso $sourceCurrency = CurrencyIso::USD;
    private CurrencyIso $targetCurrency = CurrencyIso::UAH;

    public function setUp(): void
    {
        parent::setUp();
        $this->createOrUpdateCurrency = $this->getActionMock(CreateOrUpdateCurrency::class);
        $this->rateService = $this->getRateServiceMock();
        $this->action = new ImportRates(
            $this->rateService,
            $this->createOrUpdateCurrency,
            $this->sourceCurrency,
            $this->targetCurrency,
        );
    }

    /**
     * @return RateService|MockObject
     */
    protected function getRateServiceMock(): RateService|MockObject
    {
        return $this->getMockBuilder(RateService::class)
            ->disableOriginalConstructor()
            ->onlyMethods(
                [
                    'getRate',
                ]
            )
            ->getMock();
    }

    /**
     * @return void
     * @throws UnexpectedValueException
     */
    public function testExecute()
    {
        $rate = new CurrencyProviderDto(CurrencyIso::UAH->value, 5.0);

        $this->rateService->expects($this->once())
            ->method('getRate')
            ->willReturn($rate);

        $mapInvoke = [
            'createOrUpdateCurrency.execute' => ['params' => [], 'return' => []],
        ];

        $entity = $this->getCurrencyEntity();
        $mapInvoke['createOrUpdateCurrency.execute']['params'][] = [
            new CurrencyForm(
                $rate->getCurrency(),
                $rate->getRoundedRate()
            )
        ];
        $mapInvoke['createOrUpdateCurrency.execute']['return'][] = $entity;
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
        self::assertCount(1, $actual);
    }

    /**
     * @return void
     * @throws UnexpectedValueException
     */
    public function testExecuteNotCreated()
    {
        self::expectException(NotValidException::class);
        self::expectExceptionMessage("Validation Failed");

        $rate = new CurrencyProviderDto(CurrencyIso::USD->value, 1.0);

        $this->rateService->expects($this->once())
            ->method('getRate')
            ->willReturn($rate);

        $this->createOrUpdateCurrency->expects(self::once())
            ->method('execute')
            ->willThrowException(new NotValidException(["Not valid rate"]));

        $actual = $this->action->execute();
    }
}

