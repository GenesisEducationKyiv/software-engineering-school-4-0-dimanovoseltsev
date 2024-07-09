<?php

namespace app\application\actions;

use app\application\enums\CurrencyIso;
use app\domain\entities\Currency;

interface RetrieveCurrencyByCodeInterface
{
    /**
     * @param CurrencyIso $currency
     * @return Currency
     */
    public function execute(CurrencyIso $currency): Currency;
}
