<?php

namespace app\application\providers;

use app\application\dto\CurrencyProviderDto;

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
