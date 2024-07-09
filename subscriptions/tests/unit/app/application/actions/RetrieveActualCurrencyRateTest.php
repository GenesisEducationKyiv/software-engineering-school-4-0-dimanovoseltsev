<?php

namespace tests\unit\app\application\actions;

use app\application\actions\RetrieveActualCurrencyRate;
use app\application\exceptions\NotExistException;
use app\application\services\SubscriptionService;
use app\domain\entities\Currency;
use PHPUnit\Framework\MockObject\MockObject;
use tests\unit\UnitTestCase;

class RetrieveActualCurrencyRateTest extends UnitTestCase
{
    private RetrieveActualCurrencyRate $action;
    private SubscriptionService|MockObject $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = $this->getCurrencyServiceMock();
        $this->action = new RetrieveActualCurrencyRate($this->service,);
    }

    /**
     * @throws NotExistException
     */
    public function testExecute()
    {
        $currency = $this->getCurrencyEntity();
        $this->service->expects($this->once())
            ->method('getActual')
            ->willReturn($currency);

        $actual = $this->action->execute();
        self::assertInstanceOf(Currency::class, $actual);
    }

    /**
     * @throws NotExistException
     */
    public function testExecuteNotExist()
    {
        self::expectException(NotExistException::class);
        self::expectExceptionMessage("Currency not exist");

        $this->service->expects($this->once())
            ->method('getActual')
            ->willReturn(null);

        $actual = $this->action->execute();
    }
}

