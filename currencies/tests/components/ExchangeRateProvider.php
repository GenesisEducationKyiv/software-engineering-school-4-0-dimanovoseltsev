<?php

namespace tests\components;

use app\application\dto\CurrencyProviderDto;

class ExchangeRateProvider extends \app\infrastructure\providers\ExchangeRateProvider
{
    /**
     * @param string $sourceCurrency
     * @param string $targetCurrency
     * @return CurrencyProviderDto
     */
    public function getRate(string $sourceCurrency, string $targetCurrency): CurrencyProviderDto
    {
        return new CurrencyProviderDto($targetCurrency, 39.411);
    }
}
