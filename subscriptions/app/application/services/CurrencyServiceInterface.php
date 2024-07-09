<?php

namespace app\application\services;

use app\domain\entities\Currency;

interface CurrencyServiceInterface
{
    /**
     * @return Currency|null
     */
    public function getActual(): ?Currency;
}
