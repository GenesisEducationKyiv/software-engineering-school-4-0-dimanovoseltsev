<?php

namespace app\dto\currency;

readonly class UpdateDto
{
    /**
     * @param float $rate
     */
    public function __construct(
        private float $rate,
    ) {
    }

    /**
     * @return float
     */
    public function getRate(): float
    {
        return $this->rate;
    }
}
