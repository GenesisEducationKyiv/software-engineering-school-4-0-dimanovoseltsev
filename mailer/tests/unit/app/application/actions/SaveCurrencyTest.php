<?php

namespace tests\unit\app\application\actions;

use app\application\actions\SaveCurrency;
use app\application\dto\SaveCurrencyDto;
use app\application\enums\CurrencyIso;
use app\application\services\CurrencyService;
use app\domain\entities\Currency;
use app\infrastructure\services\YiiLogger;
use PHPUnit\Framework\MockObject\MockObject;
use tests\unit\UnitTestCase;

class
SaveCurrencyTest extends UnitTestCase
{
    private SaveCurrency $action;
    private CurrencyService|MockObject $currencyService;
    private YiiLogger|MockObject $logger;

    public function setUp(): void
    {
        parent::setUp();
        $this->subscriptionService = $this->getCurrencyServiceMock();
        $this->logger = $this->getLogServiceMock();
        $this->action = new SaveCurrency($this->subscriptionService, $this->logger);
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

        $this->logger->expects(self::once())
            ->method('debug')
            ->with(
                sprintf(
                    "Save currency %s %f",
                    $dto->getIso3(),
                    $dto->getRate(),
                )
            );

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

        $this->logger->expects(self::once())
            ->method('debug')
            ->with(
                sprintf(
                    "Save currency %s %f",
                    $dto->getIso3(),
                    $dto->getRate(),
                )
            );

        $actual = $this->action->execute($dto);
        self::assertInstanceOf(Currency::class, $actual);
        self::assertIsArray($actual->toArray());
    }
}

