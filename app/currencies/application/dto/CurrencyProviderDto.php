<?php

namespace app\currencies\application\dto;

readonly class CurrencyProviderDto
{
    /**
     * @param string $currency
     * @param float $rate
     */
    public function __construct(
        private string $currency,
        private float $rate,
    ) {
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @return float
     */
    public function getRate(): float
    {
        return $this->rate;
    }
}
