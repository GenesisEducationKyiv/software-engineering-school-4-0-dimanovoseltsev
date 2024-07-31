<?php

namespace tests\unit\app\application\actions;

use app\application\actions\RetrieveCurrencyByCode;
use app\application\enums\CurrencyIso;
use app\application\exceptions\NotExistException;
use app\application\services\CurrencyService;
use app\domain\entities\Currency;
use PHPUnit\Framework\MockObject\MockObject;
use tests\unit\UnitTestCase;

class
RetrieveCurrencyByCodeTest extends UnitTestCase
{
    private RetrieveCurrencyByCode $action;
    private CurrencyService|MockObject $currencyService;

    public function setUp(): void
    {
        parent::setUp();
        $this->subscriptionService = $this->getCurrencyServiceMock();
        $this->action = new RetrieveCurrencyByCode($this->subscriptionService);
    }

    /**
     */
    public function testExecute()
    {
        $currency = CurrencyIso::USD;
        $entity = $this->getCurrencyEntity();

        $this->subscriptionService->expects($this->once())
            ->method('getByCode')
            ->with($currency->value)
            ->willReturn($entity);

        $actual = $this->action->execute($currency);
        self::assertInstanceOf(Currency::class, $actual);
    }

    /**
     */
    public function testExecuteNotExist()
    {
        self::expectException(NotExistException::class);
        self::expectExceptionMessage("Currency not found");

        $currency = CurrencyIso::USD;

        $this->subscriptionService->expects($this->once())
            ->method('getByCode')
            ->with($currency->value)
            ->willReturn(null);

        $actual = $this->action->execute($currency);
    }
}

