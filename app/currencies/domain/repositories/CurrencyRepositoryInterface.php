<?php

namespace app\currencies\domain\repositories;

use app\currencies\domain\entities\Currency;

interface CurrencyRepositoryInterface
{
    /**
     * @param string $code
     * @return Currency|null
     */
    public function getByCode(string $code): ?Currency;

    /**
     * @param Currency $currency
     * @return Currency
     */
    public function save(Currency $currency): Currency;
}
