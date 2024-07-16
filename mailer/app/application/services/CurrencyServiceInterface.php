<?php

namespace app\application\services;

use app\domain\entities\Currency;

interface CurrencyServiceInterface
{
    /**
     * @param string $code
     * @return Currency|null
     */
    public function getByCode(string $code): ?Currency;

    /**
     * @param Currency $entity
     * @return Currency
     */
    public function save(Currency $entity): Currency;
}
