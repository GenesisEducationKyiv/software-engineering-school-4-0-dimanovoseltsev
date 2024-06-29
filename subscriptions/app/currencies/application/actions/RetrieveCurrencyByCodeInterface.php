<?php

namespace app\currencies\application\actions;

use app\domain\entities\Currency;

interface RetrieveCurrencyByCodeInterface
{
    /**
     * @param string $code
     * @return Currency
     */
    public function execute(string $code): Currency;
}
