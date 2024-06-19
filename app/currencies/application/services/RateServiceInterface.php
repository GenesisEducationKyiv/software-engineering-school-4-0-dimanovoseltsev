<?php

namespace app\currencies\application\services;

use app\currencies\application\dto\CurrencyProviderDto;
use app\shared\application\exceptions\UnexpectedValueException;

interface RateServiceInterface
{
    /**
     * @param string $sourceCurrency
     * @param string $targetCurrency
     * @return CurrencyProviderDto
     * @throws UnexpectedValueException
     */
    public function getRate(string $sourceCurrency, string $targetCurrency): CurrencyProviderDto;
}
