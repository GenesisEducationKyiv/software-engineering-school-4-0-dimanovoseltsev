<?php

namespace tests\unit\app\currencies\application\actions;

use app\currencies\application\actions\CreateOrUpdateCurrency;
use app\currencies\application\dto\CreateCurrencyDto;
use app\currencies\application\forms\CurrencyForm;
use app\currencies\application\services\CurrencyService;
use app\currencies\domain\entities\Currency;
use app\shared\application\exceptions\NotValidException;
use PHPUnit\Framework\MockObject\MockObject;
use tests\unit\UnitTestCase;

class CreateOrUpdateCurrencyTest extends UnitTestCase
{
    private CreateOrUpdateCurrency $action;
    private CurrencyService|MockObject $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = $this->getCurrencyServiceMock();
        $this->action = new CreateOrUpdateCurrency($this->service);
    }

    /**
     * @throws NotValidException
     */
    public function testExecute()
    {
        $form = new CurrencyForm("USD", 1);

        $entity = $this->getCurrencyEntity();
        $this->service->expects($this->once())
            ->method("getByCode")
            ->with($form->getCode())
            ->willReturn($entity);

        $this->service->expects($this->once())
            ->method("save")
            ->with($entity)
            ->willReturn($entity);

        $actual = $this->action->execute($form);
        self::assertInstanceOf(Currency::class, $actual);
    }

    public function testExecuteCreate()
    {
        $form = new CurrencyForm("USD", 1);

        $entity = $this->getCurrencyEntity();
        $this->service->expects($this->once())
            ->method("getByCode")
            ->with($form->getCode())
            ->willReturn(null);

        $this->service->expects($this->once())
            ->method("create")
            ->with(
                new CreateCurrencyDto(
                    $form->getCode(),
                    $form->getRate(),
                    $form->getTimestamp(),
                )
            )
            ->willReturn($entity);

        $actual = $this->action->execute($form);
        self::assertInstanceOf(Currency::class, $actual);
    }

    public function testExecuteNotValid()
    {
        self::expectException(NotValidException::class);
        self::expectExceptionMessage("Validation Failed");

        $form = new CurrencyForm("USD_1", "a124");

        $actual = $this->action->execute($form);
    }
}

