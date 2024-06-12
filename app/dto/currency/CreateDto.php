<?php

namespace app\dto\currency;

use app\enums\CurrencyIso;

readonly class CreateDto
{
    /**
     * @param CurrencyIso $currency
     * @param float $rate
     */
    public function __construct(
        private CurrencyIso $currency,
        private float $rate,
    ) {
    }

    /**
     * @return string
     */
    public function getCurrencyCode(): string
    {
        return $this->currency->value;
    }

    /**
     * @return float
     */
    public function getRate(): float
    {
        return $this->rate;
    }
}
