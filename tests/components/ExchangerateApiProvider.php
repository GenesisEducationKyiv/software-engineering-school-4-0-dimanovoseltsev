<?php

namespace tests\components;

class ExchangerateApiProvider extends \app\services\providers\ExchangerateApiProvider
{
    public function getActualRates(): array
    {
        return [
            "UAH" => 39.4119
        ];
    }
}
