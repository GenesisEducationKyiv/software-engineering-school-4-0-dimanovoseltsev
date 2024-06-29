<?php

namespace app\application\services;

use app\application\dto\CurrencyProviderDto;
use app\application\exceptions\InvalidCallException;
use app\application\providers\RateChainProviderInterface;

class RateService implements RateServiceInterface
{
    /**
     * @param RateChainProviderInterface $chain
     */
    public function __construct(private readonly RateChainProviderInterface $chain)
    {
    }

    /**
     * @param string $sourceCurrency
     * @param string $targetCurrency
     * @return CurrencyProviderDto
     * @throws InvalidCallException
     */
    public function getRate(string $sourceCurrency, string $targetCurrency): CurrencyProviderDto
    {
        $rate = $this->chain->getActualRate($sourceCurrency, $targetCurrency);
        if ($rate === null) {
            throw new InvalidCallException('Currency rate provider return empty');
        }

        return $rate;
    }
}
