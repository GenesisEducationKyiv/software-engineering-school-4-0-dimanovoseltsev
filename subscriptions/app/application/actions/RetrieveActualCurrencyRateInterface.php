<?php

namespace app\application\actions;

use app\domain\entities\Currency;

interface RetrieveActualCurrencyRateInterface
{
    /**
     * @return Currency
     */
    public function execute(): Currency;
}
