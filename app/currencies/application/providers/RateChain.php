<?php

namespace app\currencies\application\providers;

use app\currencies\application\dto\CurrencyProviderDto;
use Throwable;

class RateChain implements RateChainProviderInterface
{
    private ?RateChainProviderInterface $next;

    /**
     * @param ProviderInterface $rateProvider
     * @param int $retries
     */
    public function __construct(
        private readonly ProviderInterface $rateProvider,
        private readonly int $retries
    ) {
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
        $attempt = 0;
        do {
            $attempt++;
            try {
                return $this->rateProvider->getRate($sourceCurrency, $targetCurrency);
            } catch (Throwable $e) {
                if ($attempt === $this->retries) {
                    if ($this->next === null) {
                        break;
                    }
                    return $this->next->getActualRate($sourceCurrency, $targetCurrency);
                }
            }
        } while (true);
        return null;
    }
}
