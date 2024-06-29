<?php

namespace tests\components;

use app\currencies\application\dto\CurrencyProviderDto;

class CoinbaseProvider extends \app\currencies\infrastructure\providers\CoinbaseProvider
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
