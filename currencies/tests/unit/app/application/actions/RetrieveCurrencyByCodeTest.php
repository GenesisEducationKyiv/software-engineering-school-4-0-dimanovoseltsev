<?php

namespace tests\unit\app\application\actions;

use app\application\actions\RetrieveCurrencyByCode;
use app\application\enums\CurrencyIso;
use app\application\services\CurrencyService;
use app\domain\entities\Currency;
use app\application\exceptions\NotExistException;
use PHPUnit\Framework\MockObject\MockObject;
use tests\unit\UnitTestCase;

class RetrieveCurrencyByCodeTest extends UnitTestCase
{
    private RetrieveCurrencyByCode $action;
    private CurrencyService|MockObject $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = $this->getCurrencyServiceMock();
        $this->action = new RetrieveCurrencyByCode($this->service);
    }

    /**
     * @throws NotExistException
     */
    public function testExecute()
    {
        $code = CurrencyIso::USD;

        $entity = $this->getCurrencyEntity();
        $this->service->expects($this->once())
            ->method("getByCode")
            ->with($code->value)
            ->willReturn($entity);

        $actual = $this->action->execute($code);
        self::assertInstanceOf(Currency::class, $actual);
    }

    /**
     * @throws NotExistException
     */
    public function testExecuteNotExits()
    {
        self::expectException(NotExistException::class);
        self::expectExceptionMessage("Currency not found");
        $code = CurrencyIso::USD;

        $this->service->expects($this->once())
            ->method("getByCode")
            ->with($code->value)
            ->willReturn(null);

        $actual = $this->action->execute($code);
    }
}

