<?php

namespace app\currencies\application\providers;

use app\currencies\application\dto\CurrencyProviderDto;

interface RateChainProviderInterface
{
    /**
     * @param RateChainProviderInterface $next
     * @return RateChainProviderInterface
     */
    public function setNext(RateChainProviderInterface $next): RateChainProviderInterface;

    /**
     * @param string $sourceCurrency
     * @param string $targetCurrency
     * @return CurrencyProviderDto|null
     */
    public function getActualRate(string $sourceCurrency, string $targetCurrency): ?CurrencyProviderDto;
}
