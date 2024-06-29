<?php

namespace app\currencies\application\providers;

use app\application\dto\CurrencyProviderDto;

interface RateChainProviderInterface
{
    /**
     * @param \app\application\providers\RateChainProviderInterface $next
     * @return \app\application\providers\RateChainProviderInterface
     */
    public function setNext(
        \app\application\providers\RateChainProviderInterface $next): \app\application\providers\RateChainProviderInterface;

    /**
     * @param string $sourceCurrency
     * @param string $targetCurrency
     * @return CurrencyProviderDto|null
     */
    public function getActualRate(string $sourceCurrency, string $targetCurrency): ?CurrencyProviderDto;
}
