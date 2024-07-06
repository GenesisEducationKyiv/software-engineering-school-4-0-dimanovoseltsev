<?php

namespace tests\unit\app\application\actions;

use app\application\actions\RetrieveCurrencyByCode;
use app\application\actions\SaveCurrency;
use app\application\dto\SaveCurrencyDto;
use app\application\enums\CurrencyIso;
use app\application\exceptions\NotExistException;
use app\application\services\CurrencyService;
use app\domain\entities\Currency;
use PHPUnit\Framework\MockObject\MockObject;
use tests\unit\UnitTestCase;

class
SaveCurrencyTest extends UnitTestCase
{
    private SaveCurrency $action;
    private CurrencyService|MockObject $currencyService;

    public function setUp(): void
    {
        parent::setUp();
        $this->subscriptionService = $this->getCurrencyServiceMock();
        $this->action = new SaveCurrency($this->subscriptionService);
    }

    public function testExecute()
    {
        $dto = new SaveCurrencyDto(
            CurrencyIso::UAH->value,
            5.1,
            time()
        );

        $entity = $this->getCurrencyEntity();

        $this->subscriptionService->expects($this->once())
            ->method('getByCode')
            ->with($dto->getIso3())
            ->willReturn($entity);

        $this->subscriptionService->expects($this->once())
            ->method('save')
            ->with($entity)
            ->willReturn($entity);

        $actual = $this->action->execute($dto);
        self::assertInstanceOf(Currency::class, $actual);
    }

    public function testExecuteCreate()
    {
        $dto = new SaveCurrencyDto(
            CurrencyIso::UAH->value,
            5.1,
            time()
        );

        $entity = $this->getCurrencyEntity();

        $this->subscriptionService->expects($this->once())
            ->method('getByCode')
            ->with($dto->getIso3())
            ->willReturn(null);

        $this->subscriptionService->expects($this->once())
            ->method('save')
            ->willReturn($entity);

        $actual = $this->action->execute($dto);
        self::assertInstanceOf(Currency::class, $actual);
    }
}

