<?php

namespace app\services;

use app\models\Currency;

interface ImportServiceInterface
{
    /**
     * @return Currency[]
     */
    public function importRates(): array;
}
