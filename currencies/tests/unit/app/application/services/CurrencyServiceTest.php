<?php

namespace tests\unit\app\application\services;

use app\application\dto\CreateCurrencyDto;
use app\application\enums\CurrencyIso;
use app\application\mappers\Mapper;
use app\application\services\CurrencyService;
use app\domain\entities\Currency;
use app\infrastructure\repositories\CurrencyRepository;
use PHPUnit\Framework\MockObject\MockObject;
use tests\unit\UnitTestCase;

class CurrencyServiceTest extends UnitTestCase
{
    private CurrencyService $service;
    private CurrencyRepository|MockObject $repository;

    public function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->getCurrencyRepositoryMock();
        $this->service = new CurrencyService($this->repository);
    }

    /**
     * @return CurrencyRepository|MockObject
     */
    protected function getCurrencyRepositoryMock(): CurrencyRepository|MockObject
    {
        return $this->getMockBuilder(CurrencyRepository::class)
            ->disableOriginalConstructor()
            ->onlyMethods(
                ['findByCode', 'save']
            )
            ->getMock();
    }

    public function testGetByCode()
    {
        $code = 'UDS';

        $entity = $this->getCurrencyEntity();
        $this->repository->expects($this->once())
            ->method('findByCode')
            ->with($code)
            ->willReturn($entity);

        $actual = $this->service->getByCode($code);
        self::assertInstanceOf(Currency::class, $actual);
    }

    public function testGetByCodeNull()
    {
        $code = 'UDS';
        $this->repository->expects($this->once())
            ->method('findByCode')
            ->with($code)
            ->willReturn(null);

        $actual = $this->service->getByCode($code);
        self::assertEquals(null, $actual);
    }


    /**
     * @return void
     */
    public function testSave()
    {
        $entity = $this->getCurrencyEntity();
        $this->repository->expects($this->once())
            ->method('save')
            ->with($entity)
            ->willReturn($entity);

        $actual = $this->service->save($entity);
        self::assertInstanceOf(Currency::class, $actual);
    }

    /**
     * @return void
     */
    public function testCreate()
    {
        $dto = new CreateCurrencyDto(CurrencyIso::USD->value, 1.0, time());
        $entity = $this->getCurrencyEntity();
        $this->repository->expects($this->once())
            ->method('save')
            ->with(Mapper::fromCreateDto($dto))
            ->willReturn($entity);

        $actual = $this->service->create($dto);
        self::assertInstanceOf(Currency::class, $actual);
    }
}

