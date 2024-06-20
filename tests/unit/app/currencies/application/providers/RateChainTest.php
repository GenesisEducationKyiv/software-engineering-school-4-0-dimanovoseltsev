<?php

namespace tests\unit\app\currencies\application\providers;

use app\currencies\application\dto\CurrencyProviderDto;
use app\currencies\application\enums\CurrencyIso;
use app\currencies\application\providers\RateChain;
use app\currencies\infrastructure\providers\ExchangeRateProvider;
use app\shared\application\exceptions\InvalidCallException;
use PHPUnit\Framework\MockObject\MockObject;
use tests\unit\UnitTestCase;

class RateChainTest extends UnitTestCase
{
    private RateChain $rateChain;
    private ExchangeRateProvider|MockObject $rateProvider1;
    private ExchangeRateProvider|MockObject $rateProvider2;
    private ExchangeRateProvider|MockObject $rateProvider3;
    private int $retries = 3;

    public function setUp(): void
    {
        parent::setUp();
        $this->rateProvider1 = $this->getExchangeRateProviderMock();
        $this->rateProvider2 = $this->getExchangeRateProviderMock();
        $this->rateProvider3 = $this->getExchangeRateProviderMock();
        $this->rateChain = new RateChain($this->rateProvider1, $this->retries);

        $chainSub = new RateChain($this->rateProvider2, $this->retries);
        $chainSub->setNext(new RateChain($this->rateProvider3, $this->retries));
        $this->rateChain->setNext($chainSub);
    }

    /**
     * @return ExchangeRateProvider|MockObject
     */
    protected function getExchangeRateProviderMock(): ExchangeRateProvider|MockObject
    {
        return $this->getMockBuilder(ExchangeRateProvider::class)
            ->disableOriginalConstructor()
            ->onlyMethods(
                ['getRate']
            )
            ->getMock();
    }

    /**
     */
    public function testGetActualRate()
    {
        $source = CurrencyIso::USD->value;
        $target = CurrencyIso::UAH->value;

        $rate = new CurrencyProviderDto($target, 5);

        $this->rateProvider1->expects($this->once())
            ->method('getRate')
            ->with($source, $target)
            ->willReturn($rate);

        $actual = $this->rateChain->getActualRate($source, $target);
        self::assertEquals($rate, $actual);
        self::assertInstanceOf(CurrencyProviderDto::class, $actual);
    }

    /**
     */
    public function testGetActualRateFallback()
    {
        $source = CurrencyIso::USD->value;
        $target = CurrencyIso::UAH->value;

        $rate = new CurrencyProviderDto($target, 5);

        $this->rateProvider1->expects($this->exactly($this->retries))
            ->method('getRate')
            ->with($source, $target)
            ->willThrowException(new InvalidCallException("Empty response"));

        $this->rateProvider2->expects($this->exactly(1))
            ->method('getRate')
            ->with($source, $target)
            ->willReturn($rate);

        $actual = $this->rateChain->getActualRate($source, $target);
        self::assertEquals($rate, $actual);
        self::assertInstanceOf(CurrencyProviderDto::class, $actual);
    }


    /**
     */
    public function testGetActualRateAllProvidersFail()
    {
        $source = CurrencyIso::USD->value;
        $target = CurrencyIso::UAH->value;

        $rate = new CurrencyProviderDto($target, 5);

        $this->rateProvider1->expects($this->exactly($this->retries))
            ->method('getRate')
            ->with($source, $target)
            ->willThrowException(new InvalidCallException("Empty response"));

        $this->rateProvider2->expects($this->exactly($this->retries))
            ->method('getRate')
            ->with($source, $target)
            ->willThrowException(new InvalidCallException("Empty response"));

        $this->rateProvider3->expects($this->exactly($this->retries))
            ->method('getRate')
            ->with($source, $target)
            ->willThrowException(new InvalidCallException("Empty response"));

        $actual = $this->rateChain->getActualRate($source, $target);
        self::assertEquals(null, $actual);
    }
}

