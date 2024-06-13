<?php

namespace tests\components;

use app\currencies\application\dto\CurrencyProviderDto;

class EuropeanCentralBankProvider extends \app\currencies\infrastructure\providers\EuropeanCentralBankProvider
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
