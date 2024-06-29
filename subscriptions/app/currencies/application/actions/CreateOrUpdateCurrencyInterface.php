<?php

namespace app\currencies\application\actions;

use app\currencies\application\forms\CurrencyForm;
use app\currencies\domain\entities\Currency;

interface CreateOrUpdateCurrencyInterface
{
    /**
     * @param CurrencyForm $form
     * @return Currency
     */
    public function execute(CurrencyForm $form): Currency;
}
