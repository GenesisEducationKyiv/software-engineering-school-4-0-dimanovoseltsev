<?php

namespace app\application\actions;

use app\application\dto\SaveCurrencyDto;
use app\domain\entities\Currency;

interface SaveCurrencyInterface
{
    /**
     * @param SaveCurrencyDto $dto
     * @return Currency
     */
    public function execute(SaveCurrencyDto $dto): Currency;
}
