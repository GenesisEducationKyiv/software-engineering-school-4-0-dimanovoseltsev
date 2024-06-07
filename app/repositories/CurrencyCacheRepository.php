<?php

namespace app\repositories;

use app\exceptions\EntityException;
use app\models\Currency;
use yii\caching\CacheInterface;

class CurrencyCacheRepository implements CurrencyRepositoryInterface
{
    /**
     * @param CurrencyRepository $currencyRepository
     * @param CacheInterface $cache
     * @param int $ttl
     */
    public function __construct(
        private readonly CurrencyRepository $currencyRepository,
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
     * @param Currency $model
     * @return bool
     */
    private function saveToCache(Currency $model): bool
    {
        $cacheKey = $this->getCacheKey((string)$model->iso3);
        return $this->cache->set($cacheKey, json_encode($model->getAttributes()), $this->ttl);
    }

    /**
     * @param string $code
     * @return Currency|null
     */
    public function getByCode(string $code): ?Currency
    {
        $cacheValue = $this->cache->get($this->getCacheKey($code));
        if (!empty($cacheValue) && is_string($cacheValue)) {
            $model = new Currency();
            $model->load((array)json_decode($cacheValue, true), '');
            return $model;
        }
        $model = $this->currencyRepository->getByCode($code);

        if ($model !== null) {
            $this->saveToCache($model);
        }
        return $model;
    }

    /**
     * @param array $data
     * @return Currency
     * @throws EntityException
     */
    public function create(array $data = []): Currency
    {
        $model = $this->currencyRepository->create($data);
        $this->saveToCache($model);
        return $model;
    }

    /**
     * @param Currency $model
     * @param array $data
     * @return Currency
     * @throws EntityException
     */
    public function update(Currency $model, array $data = []): Currency
    {
        $model = $this->currencyRepository->update($model, $data);
        $this->saveToCache($model);
        return $model;
    }
}