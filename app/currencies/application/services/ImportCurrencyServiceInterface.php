<?php

namespace app\currencies\application\services;

use app\currencies\domain\entities\Currency;
use app\shared\application\exceptions\NotSupportedException;

interface ImportCurrencyServiceInterface
{
    /**
     * @return Currency[]
     * @throws NotSupportedException
     */
    public function importRates(): array;
}
