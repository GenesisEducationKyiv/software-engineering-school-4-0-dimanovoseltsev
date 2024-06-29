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
        private readonly CurrencyRepositoryInterface $repository,
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
    public function findByCode(string $code): ?Currency
    {
        $cacheValue = $this->cache->get($this->getCacheKey($code));
        if (!empty($cacheValue) && is_string($cacheValue) && json_validate($cacheValue)) {
            /** @var array{"id": int|null, "iso3": string|null, "rate": float|null, "createdAt": int|null, "updatedAt": int|null} $attributes */
            $attributes = json_decode($cacheValue, true);

            return Mapper::fromPrimitive($attributes);
        }
        $entity = $this->repository->findByCode($code);

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
