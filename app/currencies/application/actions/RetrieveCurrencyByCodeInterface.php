<?php

namespace app\currencies\application\actions;

use app\currencies\domain\entities\Currency;

interface RetrieveCurrencyByCodeInterface
{
    /**
     * @param string $code
     * @return Currency
     */
    public function execute(string $code): Currency;
}
