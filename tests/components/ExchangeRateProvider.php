<?php

namespace tests\components;

use app\currencies\application\dto\CurrencyProviderDto;

class ExchangeRateProvider extends \app\currencies\infrastructure\providers\ExchangeRateProvider
{
    /**
     * @return CurrencyProviderDto[]
     */
    public function getActualRates(): array
    {
        return [
            new CurrencyProviderDto('UAH', 39.411)
        ];
    }
}
