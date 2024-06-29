<?php

namespace app\currencies\application\providers;

use app\application\dto\CurrencyProviderDto;

interface ProviderInterface
{
    /**
     * @param string $sourceCurrency
     * @param string $targetCurrency
     * @return CurrencyProviderDto
     */
    public function getRate(string $sourceCurrency, string $targetCurrency): CurrencyProviderDto;
}
