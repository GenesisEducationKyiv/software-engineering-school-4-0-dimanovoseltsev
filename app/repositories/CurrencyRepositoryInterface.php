<?php

namespace app\repositories;

use app\models\Currency;

interface CurrencyRepositoryInterface
{
    /**
     * @param string $code
     * @return Currency|null
     */
    public function getByCode(string $code): ?Currency;

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
