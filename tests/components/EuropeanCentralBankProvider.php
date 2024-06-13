<?php

namespace tests\components;

class EuropeanCentralBankProvider extends \app\currencies\infrastructure\providers\EuropeanCentralBankProvider
{
    public function getActualRates(): array
    {
        return [
            "UAH" => 39.4119
        ];
    }
}
