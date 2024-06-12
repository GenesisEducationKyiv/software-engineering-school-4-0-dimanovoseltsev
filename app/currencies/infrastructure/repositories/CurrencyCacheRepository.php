<?php

namespace app\currencies\infrastructure\repositories;

use app\currencies\domain\entities\Currency;
use app\currencies\domain\repositories\CurrencyRepositoryInterface;
use app\currencies\infrastructure\mappers\Mapper;
use app\shared\application\exceptions\NotValidException;
use yii\caching\CacheInterface;
use yii\db\Exception;

class CurrencyCacheRepository implements CurrencyRepositoryInterface
{
    /**
     * @param CurrencyRepository $repository
     * @param CacheInterface $cache
     * @param int $ttl
     */
    public function __construct(
        private readonly CurrencyRepository $repository,
        private readonly CacheInterface $cache,
        private readonly int $ttl,
    ) {
    }

    /**
     * @param string $code
     * @return string
     */
    private function getCacheKey(string $code): string
    {
        return 'currency-rate:' . $code;
    }

    /**
     * @param Currency $entity
     * @return void
     */
    private function saveToCache(Currency $entity): void
    {
        $cacheKey = $this->getCacheKey($entity->getIso3()->value());
        $this->cache->set($cacheKey, json_encode($entity->toArray()), $this->ttl);
    }

    /**
     * @param string $code
     * @return Currency|null
     */
    public function getByCode(string $code): ?Currency
    {
        $cacheValue = $this->cache->get($this->getCacheKey($code));
        if (!empty($cacheValue) && is_string($cacheValue) && json_validate($cacheValue)) {
            return Mapper::fromPrimitive((array)json_decode($cacheValue, true));
        }
        $entity = $this->repository->getByCode($code);

        if ($entity !== null) {
            $this->saveToCache($entity);
        }
        return $entity;
    }

    /**
     * @param Currency $currency
     * @return Currency
     * @throws NotValidException
     * @throws Exception
     */
    public function save(Currency $currency): Currency
    {
        $entity = $this->repository->save($currency);
        $this->saveToCache($entity);
        return $entity;
    }
}
