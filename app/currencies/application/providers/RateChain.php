<?php

namespace app\currencies\application\providers;

use app\currencies\application\dto\CurrencyProviderDto;
use Throwable;

class RateChain implements RateChainProviderInterface
{
    private ?RateChainProviderInterface $next;

    /**
     * @param ProviderInterface $rateProvider
     */
    public function __construct(private readonly ProviderInterface $rateProvider)
    {
    }

    /**
     * @param RateChainProviderInterface|null $provider
     */
    public function setNext(?RateChainProviderInterface $provider): void
    {
        $this->next = $provider;
    }

    /**
     * @param string $sourceCurrency
     * @param string $targetCurrency
     * @return CurrencyProviderDto|null
     */
    public function getActualRate(string $sourceCurrency, string $targetCurrency): ?CurrencyProviderDto
    {
        try {
            return $this->rateProvider->getRate($sourceCurrency, $targetCurrency);
        } catch (Throwable $e) {
            if ($this->next !== null) {
                return $this->next->getActualRate($sourceCurrency, $targetCurrency);
            }
        }
        return null;
    }
}
