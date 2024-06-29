<?php

namespace app\currencies\application\actions;

use app\application\forms\CurrencyForm;
use app\domain\entities\Currency;

interface CreateOrUpdateCurrencyInterface
{
    /**
     * @param CurrencyForm $form
     * @return Currency
     */
    public function execute(CurrencyForm $form): Currency;
}
