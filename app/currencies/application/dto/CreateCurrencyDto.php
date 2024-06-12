<?php


namespace app\currencies\application\dto;


readonly class CreateCurrencyDto
{
    /**
     * @param string $currency
     * @param float $rate
     * @param int|null $createdAt
     */
    public function __construct(
        private string $currency,
        private float $rate,
        private ?int $createdAt = null,
    ) {
    }

    /**
     * @return string
     */
    public function getCurrencyCode(): string
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

    /**
     * @return int
     */
    public function getCreatedAt(): int
    {
        return $this->createdAt;
    }
}
