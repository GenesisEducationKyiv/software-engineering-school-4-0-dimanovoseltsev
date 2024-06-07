<?php

namespace app\services;

use app\models\Currency;

/**
 * Interface CurrenciesServiceInterface
 *
 * @package app\services
 */
interface CurrenciesServiceInterface
{
    /**
     * @param string $code
     * @return Currency|null
     */
    public function findByCode(string $code): ?Currency;

    /**
     * @param array $data
     * @return Currency
     */
    public function create(array $data = []): Currency;

    /**
     * @param Currency $model
     * @param array $data
     * @return Currency
     */
    public function update(Currency $model, array $data = []): Currency;
}
