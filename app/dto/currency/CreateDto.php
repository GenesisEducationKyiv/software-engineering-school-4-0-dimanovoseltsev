<?php

namespace app\dto\currency;

readonly class CreateDto
{
    /**
     * @param string $iso3
     * @param float $rate
     */
    public function __construct(
        private string $iso3,
        private float $rate,
    ) {
    }

    /**
     * @return string
     */
    public function getIso3(): string
    {
        return $this->iso3;
    }

    /**
     * @return float
     */
    public function getRate(): float
    {
        return $this->rate;
    }
}
