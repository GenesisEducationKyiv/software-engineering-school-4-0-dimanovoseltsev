<?php

namespace app\currencies\application\services;

use app\currencies\application\dto\CurrencyProviderDto;
use app\currencies\application\providers\RateChainProviderInterface;
use app\shared\application\exceptions\InvalidCallException;

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
