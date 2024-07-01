<?php

namespace tests\unit\app\application\services;

use app\application\services\CurrencyService;
use app\domain\entities\Currency;
use app\domain\entities\Subscription;
use app\infrastructure\repositories\CurrencyRepository;
use app\infrastructure\services\YiiLogger;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use tests\unit\UnitTestCase;

class CurrencyServiceTest extends UnitTestCase
{
    private CurrencyService $service;
    private CurrencyRepository|MockObject $repository;
    private YiiLogger|MockObject $logger;

    public function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->getCurrencyRepositoryMock();
        $this->logger = $this->getLogServiceMock();
        $this->service = new CurrencyService($this->repository, $this->logger);
    }

    /**
     * @return CurrencyRepository|MockObject
     */
    protected function getCurrencyRepositoryMock(): CurrencyRepository|MockObject
    {
        return $this->getMockBuilder(CurrencyRepository::class)
            ->disableOriginalConstructor()
            ->onlyMethods(
                ['findActual']
            )
            ->getMock();
    }

    public function testGetActual()
    {
        $entity = $this->getCurrencyEntity();
        $this->repository->expects($this->once())
            ->method('findActual')
            ->willReturn($entity);

        $actual = $this->service->getActual();
        self::assertInstanceOf(Currency::class, $actual);
    }

    public function testGetActualFail()
    {
        $errorMessage = 'error message';
        $entity = $this->getCurrencyEntity();

        $this->repository->expects($this->once())
            ->method('findActual')
            ->willThrowException(new Exception($errorMessage));

        $this->logger->expects(self::once())
            ->method('log')
            ->with($errorMessage);

        $actual = $this->service->getActual();
        self::assertEquals(null, $actual);
    }
}

