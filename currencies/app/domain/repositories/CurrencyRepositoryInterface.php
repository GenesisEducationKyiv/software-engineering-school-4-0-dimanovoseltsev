<?php

namespace app\domain\repositories;

use app\domain\entities\Currency;

interface CurrencyRepositoryInterface
{
    /**
     * @param string $code
     * @return Currency|null
     */
    public function findByCode(string $code): ?Currency;

    /**
     * @param Currency $currency
     * @return Currency
     */
    public function save(Currency $currency): Currency;
}
