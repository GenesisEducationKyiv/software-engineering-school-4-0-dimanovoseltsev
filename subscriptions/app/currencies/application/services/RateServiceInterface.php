<?php

namespace app\currencies\application\services;

use app\application\dto\CurrencyProviderDto;
use app\application\exceptions\UnexpectedValueException;

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
