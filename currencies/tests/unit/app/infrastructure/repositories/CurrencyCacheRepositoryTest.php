<?php

namespace tests\unit\app\infrastructure\repositories;

use app\application\enums\CurrencyIso;
use app\domain\entities\Currency;
use app\infrastructure\repositories\CurrencyCacheRepository;
use app\infrastructure\repositories\CurrencyRepository;
use app\application\exceptions\NotValidException;
use PHPUnit\Framework\MockObject\MockObject;
use tests\unit\UnitTestCase;
use yii\caching\MemCache;
use yii\db\Exception;

class CurrencyCacheRepositoryTest extends UnitTestCase
{
    private CurrencyCacheRepository $repository;
    private CurrencyRepository|MockObject $currencyRepository;
    private MemCache|MockObject $cache;
    private int $ttl = 60;

    public function setUp(): void
    {
        parent::setUp();
        $this->cache = $this->getCacheMock();
        $this->currencyRepository = $this->getCurrencyRepositoryMock();
        $this->repository = new CurrencyCacheRepository($this->currencyRepository, $this->cache, $this->ttl);
    }

    /**
     * @return CurrencyRepository|MockObject
     */
    protected function getCurrencyRepositoryMock(): CurrencyRepository|MockObject
    {
        return $this->getMockBuilder(CurrencyRepository::class)
            ->disableOriginalConstructor()
            ->onlyMethods([
                'findByCode',
                'save',
            ])
            ->getMock();
    }

    /**
     * @return MemCache|MockObject
     */
    protected function getCacheMock(): MemCache|MockObject
    {
        return $this->getMockBuilder(MemCache::class)
            ->disableOriginalConstructor()
            ->onlyMethods([
                'get',
                'set',
            ])
            ->getMock();
    }

    public function testGetByCodeFromCache()
    {
        $code = CurrencyIso::USD->value;
        $cachedData = json_encode(['iso3' => $code, 'rate' => 1.0]);

        $this->cache->expects(self::once())
            ->method('get')
            ->with('currency-rate:' . $code)
            ->willReturn($cachedData);

        $currency = $this->repository->findByCode($code);

        $this->assertInstanceOf(Currency::class, $currency);
        $this->assertEquals($code, $currency->getIso3()->value());
    }

    public function testGetByCodeFromDatabase()
    {
        $code = CurrencyIso::USD->value;
        $currencyEntity = $this->getCurrencyEntity(['iso3' => $code]);

        $this->cache->expects(self::once())
            ->method('get')
            ->with('currency-rate:' . $code)
            ->willReturn(false);

        $this->currencyRepository->expects(self::once())
            ->method('findByCode')
            ->with($code)
            ->willReturn($currencyEntity);

        $this->cache->expects($this->once())
            ->method('set')
            ->with('currency-rate:' . $code, json_encode($currencyEntity->toArray()), $this->ttl)
            ->willReturn(true);

        $currency = $this->repository->findByCode($code);

        $this->assertInstanceOf(Currency::class, $currency);
        $this->assertEquals($code, $currency->getIso3()->value());
    }

    /**
     * @throws NotValidException
     * @throws Exception
     */
    public function testSave()
    {
        $entity = $this->getCurrencyEntity(['iso3' => CurrencyIso::USD->value]);
        $this->currencyRepository->expects(self::once())
            ->method('save')
            ->with($entity)
            ->willReturn($entity);

        $this->cache->expects($this->once())
            ->method('set')
            ->with('currency-rate:' . CurrencyIso::USD->value, json_encode($entity->toArray()), $this->ttl)
            ->willReturn(true);

        $currency = $this->repository->save($entity);
        $this->assertInstanceOf(Currency::class, $currency);
    }
}

