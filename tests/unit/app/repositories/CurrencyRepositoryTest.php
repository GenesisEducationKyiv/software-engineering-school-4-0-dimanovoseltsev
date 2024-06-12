<?php

namespace tests\unit\app\repositories;

use app\dto\currency\CreateDto;
use app\dto\currency\UpdateDto;
use app\enums\CurrencyIso;
use app\exceptions\EntityException;
use app\models\Currency;
use app\models\query\CurrencyQuery;
use app\repositories\CurrencyRepository;
use PHPUnit\Framework\MockObject\MockObject;
use tests\unit\UnitTestCase;

class CurrencyRepositoryTest extends UnitTestCase
{
    private CurrencyQuery|MockObject $currencyQuery;
    private CurrencyRepository $currencyRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->currencyQuery = $this->getCurrencyQuery();
        $this->currencyRepository = new CurrencyRepository($this->currencyQuery);
    }

    /**
     * @return CurrencyQuery|MockObject
     */
    protected function getCurrencyQuery(): CurrencyQuery|MockObject
    {
        return $this->getMockBuilder(CurrencyQuery::class)
            ->disableOriginalConstructor()
            ->onlyMethods([
                'clear',
                'findByCode',
                'createModel',
            ])
            ->getMock();
    }

    public function testGetByCode()
    {
        $code = 'USD';
        $currencyModel = $this->getCurrencyModelMock();

        $this->currencyQuery->expects(self::once())
            ->method('clear')
            ->willReturn($this->currencyQuery);
        $this->currencyQuery->expects(self::once())
            ->method('findByCode')->with($code)
            ->willReturn($currencyModel);

        $result = $this->currencyRepository->getByCode($code);

        $this->assertInstanceOf(Currency::class, $result);
    }

    public function testCreate()
    {
        $dto = new CreateDto(CurrencyIso::USD, 1.0);
        $currencyModel = $this->getCurrencyModelMock(['iso3' => $dto->getCurrencyCode(), 'rate' => $dto->getRate()]);
        $this->currencyQuery->expects(self::once())->method('createModel')->willReturn($currencyModel);
        $currencyModel->expects(self::once())->method('save')->willReturn(true);

        $result = $this->currencyRepository->create($dto);

        $this->assertInstanceOf(Currency::class, $result);
    }

    public function testCreateFailure()
    {
        $dto = new CreateDto(CurrencyIso::USD, 1.0);

        $currencyModel = $this->getCurrencyModelMock([
            'iso3' => $dto->getCurrencyCode(),
            'rate' => $dto->getRate()
        ]);

        $this->currencyQuery->expects(self::once())->method('createModel')->willReturn($currencyModel);
        $currencyModel->expects(self::once())->method('save')->willReturn(false);

        $this->expectException(EntityException::class);
        $this->expectExceptionMessage('Currency not saved');

        $this->currencyRepository->create($dto);
    }

    public function testUpdate()
    {
        $dto = new UpdateDto(1.0);
        $currencyModel = $this->getCurrencyModelMock(['rate' => $dto->getRate()]);

        $currencyModel->expects(self::once())->method('save')->willReturn(true);

        $result = $this->currencyRepository->update($currencyModel, $dto);

        $this->assertInstanceOf(Currency::class, $result);
    }

    public function testUpdateFailure()
    {
        $dto = new UpdateDto(1.1);
        $currencyModel = $this->getCurrencyModelMock(['rate' => $dto->getRate()]);
        $currencyModel->expects(self::once())->method('save')->willReturn(false);

        $this->expectException(EntityException::class);
        $this->expectExceptionMessage('Currency not saved');

        $this->currencyRepository->update($currencyModel, $dto);
    }
}
