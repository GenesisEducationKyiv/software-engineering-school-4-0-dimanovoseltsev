<?php

namespace app\currencies\application\providers;

use app\currencies\application\dto\CurrencyProviderDto;

interface ProviderInterface
{
    /**
     * @return CurrencyProviderDto[]
     */
    public function getActualRates(): array;
}
