<?php

namespace tests\components;

class EuropeanCentralBankProvider extends \app\services\providers\EuropeanCentralBankProvider
{
    public function getActualRates(): array
    {
        return [
            "UAH" => 39.4119
        ];
    }
}
