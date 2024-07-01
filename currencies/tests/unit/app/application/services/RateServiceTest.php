<?php

namespace tests\unit\app\application\services;

use app\application\dto\CurrencyProviderDto;
use app\application\enums\CurrencyIso;
use app\application\providers\RateChain;
use app\application\services\RateService;
use app\infrastructure\repositories\CurrencyRepository;
use app\application\exceptions\InvalidCallException;
use PHPUnit\Framework\MockObject\MockObject;
use tests\unit\UnitTestCase;

class RateServiceTest extends UnitTestCase
{
    private RateService $service;
    private RateChain|MockObject $rateChain;

    public function setUp(): void
    {
        parent::setUp();
        $this->rateChain = $this->getRateChainMock();
        $this->service = new RateService($this->rateChain);
    }

    /**
     * @return CurrencyRepository|MockObject
     */
    protected function getRateChainMock(): RateChain|MockObject
    {
        return $this->getMockBuilder(RateChain::class)
            ->disableOriginalConstructor()
            ->onlyMethods(
                ['getActualRate']
            )
            ->getMock();
    }

    /**
     * @throws InvalidCallException
     */
    public function testGetRate()
    {
        $source = CurrencyIso::USD->value;
        $target = CurrencyIso::UAH->value;

        $rate = new CurrencyProviderDto($source, 5.0);

        $this->rateChain->expects($this->once())
            ->method('getActualRate')
            ->with($source, $target)
            ->willReturn($rate);

        $actual = $this->service->getRate($source, $target);
        self::assertInstanceOf(CurrencyProviderDto::class, $actual);
    }

    /**
     * @throws InvalidCallException
     */
    public function testGetRateEmpty()
    {
        self::expectException(InvalidCallException::class);
        self::expectExceptionMessage('Currency rate provider return empty');

        $source = CurrencyIso::USD->value;
        $target = CurrencyIso::UAH->value;

        $this->rateChain->expects($this->once())
            ->method('getActualRate')
            ->with($source, $target)
            ->willReturn(null);

        $actual = $this->service->getRate($source, $target);
    }
}

