<?php

namespace app\currencies\application\providers;

use app\currencies\application\dto\CurrencyProviderDto;

interface RateChainProviderInterface
{
    /**
     * @param RateChainProviderInterface|null $provider
     * @return void
     */
    public function setNext(?RateChainProviderInterface $provider): void;

    /**
     * @param string $sourceCurrency
     * @param string $targetCurrency
     * @return CurrencyProviderDto|null
     */
    public function getActualRate(string $sourceCurrency, string $targetCurrency): ?CurrencyProviderDto;
}
