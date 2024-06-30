<?php

namespace app\domain\repositories;

use app\domain\entities\Currency;

interface CurrencyRepositoryInterface
{
    /**
     * @return Currency|null
     */
    public function findActual(): ?Currency;
}
