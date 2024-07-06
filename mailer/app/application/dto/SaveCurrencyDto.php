<?php

namespace app\application\dto;

readonly class SaveCurrencyDto
{
    /**
     * @param string $iso3
     * @param float $rate
     * @param int $updatedAt
     */
    public function __construct(
        private string $iso3,
        private float $rate,
        private int $updatedAt,
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

    /**
     * @return int
     */
    public function getUpdatedAt(): int
    {
        return $this->updatedAt;
    }
}
