<?php

namespace app\currencies\application\actions;

use app\currencies\application\forms\CreateCurrencyForm;
use app\currencies\domain\entities\Currency;

interface CreateOrUpdateCurrencyInterface
{
    /**
     * @param CreateCurrencyForm $form
     * @return Currency
     */
    public function execute(CreateCurrencyForm $form): Currency;
}
