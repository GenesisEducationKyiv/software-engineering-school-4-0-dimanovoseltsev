<?php

namespace tests\unit\app\repositories;

use app\exceptions\EntityException;
use app\models\Currency;
use app\repositories\CurrencyCacheRepository;
use app\repositories\CurrencyRepository;
use PHPUnit\Framework\MockObject\MockObject;
use tests\unit\UnitTestCase;
use yii\caching\MemCache;


class CurrencyCacheRepositoryTest extends UnitTestCase
{
    private CurrencyCacheRepository|MockObject $currencyRepository;
    private MemCache|MockObject $cache;
    private CurrencyCacheRepository $currencyCacheRepository;
    private int $ttl = 3600;

    protected function setUp(): void
    {
        parent::setUp();
        $this->currencyRepository = $this->getCurrencyRepository();
        $this->cache = $this->getCache();
        $this->currencyCacheRepository = new CurrencyCacheRepository(
            $this->currencyRepository,
            $this->cache,
            $this->ttl
        );
    }

    /**
     * @return CurrencyRepository|MockObject
     */
    protected function getCurrencyRepository(): CurrencyRepository|MockObject
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

    /**
     * @return MemCache|MockObject
     */
    protected function getCache(): MemCache|MockObject
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
        $code = 'USD';
        $cachedData = json_encode(['iso3' => $code, 'rate' => 1.0]);

        $this->cache->expects(self::once())->method('get')
            ->with('currency-rate:' . $code)
            ->willReturn($cachedData);

        $currency = $this->currencyCacheRepository->getByCode($code);

        $this->assertInstanceOf(Currency::class, $currency);
        $this->assertEquals($code, $currency->iso3);
    }

    public function testGetByCodeFromDatabase()
    {
        $code = 'USD';
        $this->cache->expects(self::once())->method('get')
            ->with('currency-rate:' . $code)
            ->willReturn(false);

        $currencyModel = $this->getCurrencyModelMock();
        $currencyModel->iso3 = $code;

        $this->currencyRepository->expects(self::once())->method('getByCode')
            ->with($code)
            ->willReturn($currencyModel);

        $this->cache->expects($this->once())
            ->method('set')
            ->with('currency-rate:' . $code, json_encode($currencyModel->getAttributes()), $this->ttl)
            ->willReturn(true);

        $currency = $this->currencyCacheRepository->getByCode($code);

        $this->assertInstanceOf(Currency::class, $currency);
        $this->assertEquals($code, $currency->iso3);
    }

    public function testCreateCurrency()
    {
        $data = ['iso3' => 'USD', 'rate' => 1.0];
        $currencyModel = $this->getCurrencyModelMock($data);

        $this->currencyRepository->expects(self::once())->method('create')
            ->with($data)
            ->willReturn($currencyModel);

        $this->cache->expects($this->once())
            ->method('set')
            ->with('currency-rate:USD', json_encode($currencyModel->getAttributes()), $this->ttl)
            ->willReturn(true);

        $currency = $this->currencyCacheRepository->create($data);

        $this->assertInstanceOf(Currency::class, $currency);
    }

    public function testCreateCurrencyFailure()
    {
        $data = ['iso3' => 'USD', 'rate' => 1.0];

        $this->currencyRepository->expects(self::once())->method('create')
            ->with($data)
            ->willThrowException(new EntityException(new Currency(), 'Currency not saved'));

        $this->expectException(EntityException::class);
        $this->expectExceptionMessage('Currency not saved');

        $this->currencyCacheRepository->create($data);
    }

    public function testUpdateCurrency()
    {
        $data = ['rate' => 1.1];
        $currencyModel = $this->getCurrencyModelMock();

        $this->currencyRepository->expects(self::once())->method('update')
            ->with($currencyModel, $data)
            ->willReturn($currencyModel);

        $this->cache->expects($this->once())
            ->method('set')
            ->with('currency-rate:' . $currencyModel->iso3, json_encode($currencyModel->getAttributes()), $this->ttl)
            ->willReturn(true);

        $currency = $this->currencyCacheRepository->update($currencyModel, $data);

        $this->assertInstanceOf(Currency::class, $currency);
    }

    public function testUpdateCurrencyFailure()
    {
        $data = ['rate' => 1.1];
        $currencyModel = $this->getCurrencyModelMock();

        $this->currencyRepository->expects(self::once())->method('update')
            ->with($currencyModel, $data)
            ->willThrowException(new EntityException($currencyModel, 'Currency not saved'));

        $this->expectException(EntityException::class);
        $this->expectExceptionMessage('Currency not saved');

        $this->currencyCacheRepository->update($currencyModel, $data);
    }
}
