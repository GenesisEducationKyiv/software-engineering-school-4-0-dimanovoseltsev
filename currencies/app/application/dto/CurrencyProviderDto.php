<?php

namespace app\application\dto;

readonly class CurrencyProviderDto
{
    public const int RATE_PRECISION = 5;

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
    public function getRoundedRate(): float
    {
        return round($this->rate, self::RATE_PRECISION);
    }
}
