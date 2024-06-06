<?php

namespace tests\unit\app\services;

use app\models\Currency;
use app\repositories\CurrencyRepository;
use app\services\CurrenciesService;
use PHPUnit\Framework\MockObject\MockObject;
use tests\unit\UnitTestCase;

class CurrenciesServiceTest extends UnitTestCase
{
    private CurrenciesService $service;
    private CurrencyRepository|MockObject $repository;

    public function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->getCurrencyRepositoryMock();
        $this->service = new CurrenciesService($this->repository);
    }

    /**
     * @return CurrencyRepository|MockObject
     */
    protected function getCurrencyRepositoryMock(): CurrencyRepository|MockObject
    {
        return $this->getMockBuilder(CurrencyRepository::class)
            ->disableOriginalConstructor()
            ->onlyMethods([
                'getByCode',
                'create',
                'update',
            ])
            ->getMock();
    }

    public function testFindByCode()
    {
        $currencyCode = 'USD';
        $currency = $this->getCurrencyModelMock();
        $this->repository->expects($this->once())
            ->method('getByCode')
            ->with($currencyCode)
            ->willReturn($currency);

        $result = $this->service->findByCode($currencyCode);
        self::assertInstanceOf(Currency::class, $result);
    }

    public function testCreate()
    {
        $data = ['iso3' => 'USD', 'rate' => 12.1];
        $currency = $this->getCurrencyModelMock();
        $this->repository->expects($this->once())
            ->method('create')
            ->with($data)
            ->willReturn($currency);

        $result = $this->service->create($data);
        self::assertInstanceOf(Currency::class, $result);
    }

    public function testUpdate()
    {
        $data = ['rate' => 3.2];
        $currency = $this->getCurrencyModelMock();
        $this->repository->expects($this->once())
            ->method('update')
            ->with($currency, $data)
            ->willReturn($currency);

        $result = $this->service->update($currency, $data);
        self::assertInstanceOf(Currency::class, $result);
    }
}

