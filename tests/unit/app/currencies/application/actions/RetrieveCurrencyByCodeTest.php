<?php

namespace tests\unit\app\currencies\application\actions;

use app\currencies\application\actions\RetrieveCurrencyByCode;
use app\currencies\application\enums\CurrencyIso;
use app\currencies\application\services\CurrencyService;
use app\currencies\domain\entities\Currency;
use app\shared\application\exceptions\NotExistException;
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
        $code = CurrencyIso::USD->value;

        $entity = $this->getCurrencyEntity();
        $this->service->expects($this->once())
            ->method("getByCode")
            ->with($code)
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
        $code = CurrencyIso::USD->value;

        $this->service->expects($this->once())
            ->method("getByCode")
            ->with($code)
            ->willReturn(null);

        $actual = $this->action->execute($code);
    }
}

